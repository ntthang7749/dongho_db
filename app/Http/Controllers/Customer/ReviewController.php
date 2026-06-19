<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Jobs\AnalyzeReviewSentiment;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Kiểm tra đã mua chưa
        $hasBought = auth()->user()->orders()
            ->whereHas('items', fn ($q) => $q->where('product_id', $request->product_id))
            ->where('status', 'delivered')->exists();

        if (! $hasBought) {
            return back()->with('error', 'Bạn cần mua và nhận hàng trước khi đánh giá!');
        }

        // Kiểm tra đã đánh giá chưa
        $exists = Review::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi!');
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        // ── DISPATCH JOB PHÂN TÍCH CẢM XÚC ──
        if ($review->comment) {
            AnalyzeReviewSentiment::dispatch($review)->delay(now()->addSeconds(5));
        }

        return back()->with('success',
            '✅ Cảm ơn bạn! Đánh giá đang chờ duyệt.');
    }
}
