<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        session()->forget('buy_now');
        $cart = session('cart', []);
        $coupon = session('coupon');
        $summary = $this->calcSummary($cart, $coupon);

        return view('customer.cart.index', compact('cart', 'coupon', 'summary'));
    }

    // Thêm vào giỏ
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        try {
            $result = $this->validateProductAndPrepareItem($request->product_id, $request->quantity);
            $product = $result['product'];
            $item = $result['item'];
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }
            return back()->with('error', $e->getMessage());
        }

        $cart = session('cart', []);
        $id = $product->id;

        if (isset($cart[$id])) {
            // Đã có trong giỏ → tăng số lượng
            $newQty = $cart[$id]['quantity'] + $request->quantity;
            if ($newQty > $product->stock) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => "Chỉ còn {$product->stock} sản phẩm trong kho!"], 400);
                }
                return back()->with('error', "Chỉ còn {$product->stock} sản phẩm trong kho!");
            }
            $cart[$id]['quantity'] = $newQty;
        } else {
            // Thêm mới
            $cart[$id] = $item;
        }

        session(['cart' => $cart]);

        if ($request->expectsJson() || $request->ajax()) {
            $cartCount = collect($cart)->sum('quantity');
            return response()->json([
                'success' => true,
                'message' => "✅ Đã thêm \"{$product->name}\" vào giỏ hàng!",
                'cart_count' => $cartCount
            ]);
        }

        return back()->with('success', "✅ Đã thêm \"{$product->name}\" vào giỏ hàng!");
    }

    // Mua ngay
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        try {
            $result = $this->validateProductAndPrepareItem($request->product_id, $request->quantity);
            $item = $result['item'];
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        session(['buy_now' => $item]);

        return redirect()->route('orders.checkout');
    }

    // Helper: Xác thực trạng thái và chuẩn bị thông tin sản phẩm
    private function validateProductAndPrepareItem(int $productId, int $quantity): array
    {
        $product = Product::findOrFail($productId);

        if (! $product->is_active || $product->stock < 1) {
            throw new \Exception('Sản phẩm này hiện không còn hàng!');
        }

        if ($quantity > $product->stock) {
            throw new \Exception("Chỉ còn {$product->stock} sản phẩm trong kho!");
        }

        return [
            'product' => $product,
            'item' => [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->sale_price ?? $product->price,
                'thumbnail' => $product->thumbnail,
                'slug' => $product->slug,
                'quantity' => $quantity,
                'stock' => $product->stock,
            ]
        ];
    }

    // Cập nhật số lượng
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = session('cart', []);
        $id = $request->product_id;

        if (isset($cart[$id])) {
            $product = Product::find($id);
            if ($product && $request->quantity > $product->stock) {
                return back()->with('error', "Chỉ còn {$product->stock} sản phẩm trong kho!");
            }
            $cart[$id]['quantity'] = $request->quantity;
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Đã cập nhật giỏ hàng!');
    }

    // Xoá sản phẩm khỏi giỏ
    public function remove(int $id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng!');
    }

    // Áp dụng mã giảm giá
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (! $coupon || ! $coupon->isValid()) {
            return back()->with('error', '❌ Mã giảm giá không hợp lệ hoặc đã hết hạn!');
        }

        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(fn ($i) => $i['price'] * $i['quantity']);

        if ($subtotal < $coupon->min_order) {
            return back()->with('error',
                '❌ Đơn hàng tối thiểu '.number_format($coupon->min_order).'đ để dùng mã này!');
        }

        session(['coupon' => [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'max_discount' => $coupon->max_discount,
        ]]);

        return back()->with('success', "🎉 Áp dụng mã \"{$coupon->code}\" thành công!");
    }

    // Xoá mã giảm giá
    public function removeCoupon()
    {
        session()->forget('coupon');

        return back()->with('success', 'Đã xoá mã giảm giá!');
    }

    // Tính tổng tiền (dùng chung nhiều nơi)
    public static function calcSummary(array $cart, ?array $coupon): array
    {
        $subtotal = collect($cart)->sum(fn ($i) => $i['price'] * $i['quantity']);
        $discount = 0;

        if ($coupon) {
            if ($coupon['type'] === 'percent') {
                $discount = $subtotal * ($coupon['value'] / 100);
                if ($coupon['max_discount']) {
                    $discount = min($discount, $coupon['max_discount']);
                }
            } else {
                $discount = $coupon['value'];
            }
        }

        $total = max(0, $subtotal - $discount);

        return compact('subtotal', 'discount', 'total');
    }
}
