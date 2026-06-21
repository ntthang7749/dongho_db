<?php

namespace App\Jobs;

use App\Models\Comment;
use App\Services\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ModerateComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 30;

    public function __construct(public Comment $comment) {}

    public function handle(GeminiService $gemini): void
    {
        if (! $this->comment->content) {
            return;
        }

        try {
            // Phân tích và kiểm duyệt bình luận bằng quy trình tối ưu
            $result = $gemini->processComment($this->comment);

            $this->comment->update([
                'is_spam' => $result['is_spam'],
                'ai_reason' => $result['reason'],
                'status' => $result['status'],
                'sentiment' => $result['sentiment'],
                'sentiment_score' => $result['sentiment_score'],
            ]);

        } catch (\Exception $e) {
            Log::error('Comment moderation failed: '.$e->getMessage());
            throw $e;
        }
    }
}
