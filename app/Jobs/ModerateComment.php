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
            $result = $gemini->detectSpam($this->comment->content);

            $isSpam = (bool) ($result['is_spam'] ?? false);
            $confidence = (float) ($result['confidence'] ?? 0.0);

            // Quyết định trạng thái dựa vào confidence
            // - is_spam=true + confidence>=0.85: tự reject
            // - is_spam=false + confidence>=0.7: tự approve
            // - còn lại: giữ pending để admin duyệt
            if ($isSpam && $confidence >= 0.85) {
                $status = 'rejected';
            } elseif (! $isSpam && $confidence >= 0.7) {
                $status = 'approved';
            } else {
                $status = 'pending';
            }

            $this->comment->update([
                'is_spam' => $isSpam,
                'ai_reason' => $result['reason'] ?? null,
                'status' => $status,
            ]);

        } catch (\Exception $e) {
            Log::error('Comment moderation failed: '.$e->getMessage());
            throw $e;
        }
    }
}
