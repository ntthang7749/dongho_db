<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()->wishlists()
            ->with(['product.brand', 'product.images'])
            ->paginate(12);

        return view('customer.wishlist.index', compact('wishlists'));
    }

    public function toggle(int $productId)
    {
        $existing = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
            $msg = 'Đã xoá khỏi danh sách yêu thích!';
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
            ]);
            $msg = '❤️ Đã thêm vào danh sách yêu thích!';
        }

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return back()->with('success', $msg);
    }
}
