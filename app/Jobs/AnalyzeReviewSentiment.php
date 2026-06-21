<?php

namespace App\Jobs;

use App\Models\Review;
use App\Services\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeReviewSentiment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(public Review $review) {}

    public function handle(GeminiService $gemini): void
    {
        if (! $this->review->comment) {
            return;
        }

        try {
            // Phân tích và kiểm duyệt đánh giá bằng quy trình tối ưu
            $result = $gemini->processReview($this->review);

            $oldStatus = $this->review->status;
            $status = $result['status'];

            $this->review->update([
                'sentiment' => $result['sentiment'],
                'sentiment_score' => $result['sentiment_score'],
                'is_spam' => $result['is_spam'],
                'ai_reason' => $result['reason'],
                'status' => $status,
            ]);

            // Cập nhật điểm rating của sản phẩm nếu trạng thái được AI tự duyệt hoặc từ chối
            if ($oldStatus !== $status) {
                $productId = $this->review->product_id;
                $avg = \App\Models\Review::where('product_id', $productId)
                    ->where('status', 'approved')->avg('rating');
                $count = \App\Models\Review::where('product_id', $productId)
                    ->where('status', 'approved')->count();

                \App\Models\Product::where('id', $productId)->update([
                    'rating_avg' => round($avg ?? 0, 2),
                    'rating_count' => $count,
                ]);

                // Kích hoạt tóm tắt đánh giá sản phẩm tự động nếu được duyệt
                if ($status === 'approved') {
                    \App\Jobs\SummarizeProductReviews::dispatch($productId)
                        ->delay(now()->addSeconds(10));
                }
            }

        } catch (\Exception $e) {
            Log::error('Review analysis and moderation failed: '.$e->getMessage());
            throw $e;
        }
    }
}
