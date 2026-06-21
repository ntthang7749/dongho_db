<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SummarizeProductReviews;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;
use App\Services\GeminiService;


class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(int $id)
    {
        $review = Review::findOrFail($id);

        $review->update([
            'status' => $review->status === 'approved' ? 'pending' : 'approved',
        ]);

        $statusStr = $review->status === 'approved' ? 'Duyệt đánh giá' : 'Bỏ duyệt đánh giá';
        ActivityLogger::log($statusStr, 'Đánh giá', $review->id, 'Đã ' . mb_strtolower($statusStr) . ' ID #' . $review->id . ' của khách hàng: "' . ($review->user->name ?? 'Khách') . '" cho sản phẩm: "' . ($review->product->name ?? 'N/A') . '" (' . $review->rating . ' sao, Nội dung: "' . \Illuminate\Support\Str::limit($review->content ?? '', 40) . '")');

        // Cập nhật rating
        $this->updateProductRating($review->product_id);


        // ── SAU KHI DUYỆT: TÓM TẮT REVIEW TỰ ĐỘNG ──
        if ($review->status === 'approved') {
            SummarizeProductReviews::dispatch($review->product_id)
                ->delay(now()->addSeconds(10));
        }

        $msg = $review->status === 'approved' ? '✅ Đã duyệt đánh giá!' : 'Đã bỏ duyệt!';

        return back()->with('success', $msg);
    }

    public function destroy(int $id)
    {
        $review = Review::findOrFail($id);
        $productId = $review->product_id;
        
        ActivityLogger::log('Xóa đánh giá', 'Đánh giá', $review->id, 'Đã xóa đánh giá ID #' . $review->id . ' của khách hàng: "' . ($review->user->name ?? 'Khách') . '" cho sản phẩm ID #' . $productId);

        $review->delete();


        $this->updateProductRating($productId);

        return back()->with('success', '✅ Đã xoá đánh giá!');
    }

    public function aiCheck(int $id, GeminiService $gemini)
    {
        $review = Review::with(['user', 'product'])->findOrFail($id);
        
        if (!$review->comment) {
            return response()->json(['success' => false, 'message' => 'Đánh giá không có nội dung bình luận.']);
        }

        try {
            $result = $gemini->processReview($review);

            $oldStatus = $review->status;
            $status = $result['status'];

            $review->update([
                'is_spam' => $result['is_spam'],
                'ai_reason' => $result['reason'],
                'status' => $status,
                'sentiment' => $result['sentiment'],
                'sentiment_score' => $result['sentiment_score'],
            ]);

            // Cập nhật lại Rating sản phẩm nếu trạng thái thay đổi
            if ($oldStatus !== $status) {
                $this->updateProductRating($review->product_id);
                
                if ($status === 'approved') {
                    \App\Jobs\SummarizeProductReviews::dispatch($review->product_id)
                        ->delay(now()->addSeconds(10));
                }
            }

            // Ghi nhật ký
            $statusLog = $result['is_spam'] ? "Phát hiện VI PHẠM (Lý do: {$result['reason']})" : "Hợp lệ và an toàn";
            ActivityLogger::log('AI quét đánh giá', 'Đánh giá', $review->id, 
                "AI đã quét đánh giá #{$review->id} của '{$review->user->name}' cho sản phẩm '{$review->product->name}'. Kết quả: {$statusLog} (Độ tin cậy: " . ($result['confidence'] * 100) . "%)"
            );

            return response()->json([
                'success' => true,
                'is_spam' => $result['is_spam'],
                'confidence' => $result['confidence'],
                'reason' => $result['reason'],
                'status' => $status,
                'status_text' => ['pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'rejected' => 'Từ chối'][$status],
                'sentiment' => $result['sentiment'],
                'sentiment_score' => $result['sentiment_score'],
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi gọi AI: ' . $e->getMessage()]);
        }
    }

    public function bulkAiCheck(Request $request, GeminiService $gemini)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'success' => true,
                'scanned_count' => 0,
                'results' => []
            ]);
        }

        $reviews = Review::whereIn('id', $ids)
            ->where(function ($query) {
                $query->where('status', '!=', 'approved')
                      ->orWhereNull('sentiment')
                      ->orWhere('sentiment', '');
            })->get();
        $updatedCount = 0;
        $results = [];
        $affectedProducts = [];

        foreach ($reviews as $review) {
            if (!$review->comment) continue;
            
            try {
                $result = $gemini->processReview($review);

                $oldStatus = $review->status;
                $status = $result['status'];

                $review->update([
                    'is_spam' => $result['is_spam'],
                    'ai_reason' => $result['reason'],
                    'status' => $status,
                    'sentiment' => $result['sentiment'],
                    'sentiment_score' => $result['sentiment_score'],
                ]);

                if ($oldStatus !== $status) {
                    $affectedProducts[$review->product_id] = $status;
                }

                $results[] = [
                    'id' => $review->id,
                    'is_spam' => $result['is_spam'],
                    'reason' => $result['reason'],
                    'status' => $status,
                    'status_text' => ['pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'rejected' => 'Từ chối'][$status],
                    'sentiment' => $result['sentiment'],
                    'sentiment_score' => $result['sentiment_score'],
                ];
                $updatedCount++;

            } catch (\Exception $e) {
                continue;
            }
        }

        // Cập nhật rating và tóm tắt cho các sản phẩm bị ảnh hưởng
        foreach ($affectedProducts as $productId => $finalStatus) {
            $this->updateProductRating($productId);
            if ($finalStatus === 'approved') {
                \App\Jobs\SummarizeProductReviews::dispatch($productId)
                    ->delay(now()->addSeconds(10));
            }
        }

        ActivityLogger::log('AI quét hàng loạt đánh giá', 'Đánh giá', 0, "Đã chạy AI quét hàng loạt kiểm duyệt tất cả đánh giá chờ duyệt. Tổng số đánh giá đã quét: {$updatedCount}");

        return response()->json([
            'success' => true,
            'scanned_count' => $updatedCount,
            'results' => $results
        ]);
    }

    private function updateProductRating(int $productId)
    {
        $avg = Review::where('product_id', $productId)
            ->where('status', 'approved')->avg('rating');
        $count = Review::where('product_id', $productId)
            ->where('status', 'approved')->count();

        Product::where('id', $productId)->update([
            'rating_avg' => round($avg ?? 0, 2),
            'rating_count' => $count,
        ]);
    }
}
