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
            // 1. Phân tích cảm xúc đánh giá
            $sentimentResult = $gemini->analyzeSentiment($this->review->comment);

            // 2. Kiểm duyệt nội dung tự động chống XSS, Spam, Kích động, Kỳ thị
            $moderationResult = $gemini->detectReviewSpam($this->review->comment);
            $isSpam = (bool)($moderationResult['is_spam'] ?? false);
            $confidence = (float)($moderationResult['confidence'] ?? 0.0);

            // Quyết định trạng thái phê duyệt tự động của AI
            // - Phát hiện vi phạm với độ tin cậy >= 0.85: Tự động từ chối (rejected)
            // - Đánh giá sạch với độ tin cậy >= 0.7: Tự động duyệt (approved)
            // - Các trường hợp còn lại: Chờ duyệt (pending)
            if ($isSpam && $confidence >= 0.85) {
                $status = 'rejected';
            } elseif (!$isSpam && $confidence >= 0.7) {
                $status = 'approved';
            } else {
                $status = 'pending';
            }

            $oldStatus = $this->review->status;

            $this->review->update([
                'sentiment' => $sentimentResult['sentiment'] ?? 'neutral',
                'sentiment_score' => $sentimentResult['score'] ?? 0.5,
                'is_spam' => $isSpam,
                'ai_reason' => $moderationResult['reason'] ?? null,
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
