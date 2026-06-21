<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;
use App\Services\GeminiService;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'news'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $comments = $query->paginate(15)->withQueryString();

        return view('admin.comments.index', compact('comments'));
    }

    public function approve(int $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update([
            'status' => $comment->status === 'approved' ? 'pending' : 'approved',
        ]);

        $statusStr = $comment->status === 'approved' ? 'Duyệt bình luận' : 'Bỏ duyệt bình luận';
        ActivityLogger::log($statusStr, 'Bình luận', $comment->id, 'Đã ' . mb_strtolower($statusStr) . ' ID #' . $comment->id . ' của thành viên: "' . ($comment->user->name ?? 'N/A') . '" trong bài viết: "' . ($comment->news->title ?? 'N/A') . '" (Nội dung: "' . \Illuminate\Support\Str::limit($comment->content ?? '', 40) . '")');

        return back()->with('success', '✅ Đã cập nhật trạng thái bình luận!');
    }

    public function aiCheck(int $id, GeminiService $gemini)
    {
        $comment = Comment::with(['user', 'news'])->findOrFail($id);
        
        if (!$comment->content) {
            return response()->json(['success' => false, 'message' => 'Bình luận không có nội dung.']);
        }

        try {
            $result = $gemini->processComment($comment);
            $status = $result['status'];

            $comment->update([
                'is_spam' => $result['is_spam'],
                'ai_reason' => $result['reason'],
                'status' => $status,
                'sentiment' => $result['sentiment'],
                'sentiment_score' => $result['sentiment_score'],
            ]);

            // Ghi nhật ký
            $statusLog = $result['is_spam'] ? "Phát hiện VI PHẠM (Lý do: {$result['reason']})" : "Hợp lệ và an toàn";
            ActivityLogger::log('AI quét bình luận', 'Bình luận', $comment->id, 
                "AI đã quét bình luận #{$comment->id} của '{$comment->user->name}'. Kết quả: {$statusLog} (Độ tin cậy: " . ($result['confidence'] * 100) . "%)"
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

        $comments = Comment::whereIn('id', $ids)
            ->where(function ($query) {
                $query->where('status', '!=', 'approved')
                      ->orWhereNull('sentiment')
                      ->orWhere('sentiment', '');
            })->get();
        $updatedCount = 0;
        $results = [];

        foreach ($comments as $comment) {
            if (!$comment->content) continue;
            
            try {
                $result = $gemini->processComment($comment);
                $status = $result['status'];

                $comment->update([
                    'is_spam' => $result['is_spam'],
                    'ai_reason' => $result['reason'],
                    'status' => $status,
                    'sentiment' => $result['sentiment'],
                    'sentiment_score' => $result['sentiment_score'],
                ]);

                $results[] = [
                    'id' => $comment->id,
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

        ActivityLogger::log('AI quét hàng loạt', 'Bình luận', 0, "Đã chạy AI quét hàng loạt kiểm duyệt tất cả bình luận trên trang. Tổng số bình luận đã quét: {$updatedCount}");

        return response()->json([
            'success' => true,
            'scanned_count' => $updatedCount,
            'results' => $results
        ]);
    }

    public function destroy(int $id)
    {
        $comment = Comment::findOrFail($id);
        
        ActivityLogger::log('Xóa bình luận', 'Bình luận', $comment->id, 'Đã xóa bình luận ID #' . $comment->id . ' của thành viên: "' . ($comment->user->name ?? 'N/A') . '"');

        $comment->delete();

        return back()->with('success', '✅ Đã xoá bình luận!');
    }
}
