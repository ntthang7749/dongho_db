<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Review;
use App\Services\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SummarizeProductReviews implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(public int $productId) {}

    public function handle(GeminiService $gemini): void
    {
        $product = Product::find($this->productId);
        if (! $product) {
            return;
        }

        try {
            $reviews = Review::where('product_id', $this->productId)
                ->where('status', 'approved')
                ->whereNotNull('comment')
                ->latest()
                ->take(20)
                ->get(['rating', 'comment'])
                ->toArray();

            if (empty($reviews)) {
                return;
            }

            $summary = $gemini->summarizeReviews($reviews, $product->name);

            $product->update(['ai_description' => $summary]);

        } catch (\Exception $e) {
            Log::error('Review summary failed: '.$e->getMessage());
            throw $e;
        }
    }
}
