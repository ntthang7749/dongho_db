<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\VietQRService;
use App\Services\VNPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;


class OrderController extends Controller
{
    protected VNPayService $vnpay;

    protected VietQRService $vietqr;

    public function __construct(VNPayService $vnpay, VietQRService $vietqr)
    {
        $this->vnpay = $vnpay;
        $this->vietqr = $vietqr;
    }

    // Helper: Lấy thông tin giỏ hàng và coupon cho checkout/đặt hàng
    private function getCheckoutCartAndCoupon(Request $request): array
    {
        $buyNow = session('buy_now');
        if ($buyNow) {
            $cart = [
                $buyNow['product_id'] => $buyNow
            ];
            $coupon = null;
        } else {
            $cart = session('cart', []);
            $coupon = session('coupon');

            $selectedItems = $request->query('selected_items') ?? $request->input('selected_items');
            if (!empty($selectedItems)) {
                $selectedIds = explode(',', $selectedItems);
                $cart = collect($cart)->only($selectedIds)->toArray();
            }
        }

        return [$cart, $coupon, (bool)$buyNow];
    }

    // ── TRANG CHECKOUT ──
    public function checkout(Request $request)
    {
        list($cart, $coupon, $isBuyNow) = $this->getCheckoutCartAndCoupon($request);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng trống hoặc chưa chọn sản phẩm để thanh toán!');
        }

        $summary = CartController::calcSummary($cart, $coupon);
        $user = auth()->user();

        return view('customer.orders.checkout',
            compact('cart', 'coupon', 'summary', 'user'));
    }

    // ── ĐẶT HÀNG ──
    public function place(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:100',
            'receiver_phone' => 'required|string|max:20',
            'receiver_address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,vnpay,qr',
            'note' => 'nullable|string|max:500',
            'selected_items' => 'nullable|string',
        ]);

        list($cart, $coupon, $isBuyNow) = $this->getCheckoutCartAndCoupon($request);
        $summary = CartController::calcSummary($cart, $coupon);

        // Guard: không cho đặt đơn với giỏ rỗng hoặc tổng < 5000đ (VNPay min).
        // Trường hợp dính bug này: user submit form 2 lần (refresh sau khi cổng thanh toán fail) —
        // lần 2 cart đã bị forget() ở lần 1 → tạo order total=0 → VNPay reject "Số tiền không hợp lệ".
        if (empty($cart) || $summary['total'] < 5000) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng trống hoặc tổng tiền thấp hơn 5.000đ. Vui lòng thêm sản phẩm.');
        }

        $order = null;

        try {
            DB::transaction(function () use (
                $request, $cart, $coupon, $summary, &$order
            ) {
                $order = Order::create([
                    'order_code' => 'DH'.date('Ymd').strtoupper(Str::random(4)),
                    'user_id' => auth()->id(),
                    'coupon_id' => $coupon['id'] ?? null,
                    'receiver_name' => $request->receiver_name,
                    'receiver_phone' => $request->receiver_phone,
                    'receiver_address' => $request->receiver_address,
                    'subtotal' => $summary['subtotal'],
                    'discount' => $summary['discount'],
                    'total' => $summary['total'],
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                    'status' => 'pending',
                    'note' => $request->note,
                ]);

                foreach ($cart as $productId => $item) {
                    $product = Product::lockForUpdate()->find($productId);

                    if (! $product || $product->stock < $item['quantity']) {
                        throw new \Exception(
                            "Sản phẩm \"{$item['name']}\" không đủ số lượng!"
                        );
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'product_name' => $item['name'],
                        'product_image' => $item['thumbnail'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    $product->decrement('stock', $item['quantity']);
                }

                if ($coupon) {
                    Coupon::where('id', $coupon['id'])->increment('usage_count');
                }
            });

            // Ghi log hoạt động đặt hàng
            ActivityLogger::log('Đặt hàng', 'Đơn hàng', $order->id, 'Khách hàng "' . auth()->user()->name . '" đã đặt đơn hàng mới #' . $order->order_code . ' (Phương thức: ' . strtoupper($order->payment_method) . ', Tổng tiền: ' . number_format($order->total) . 'đ)');


        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        // Xoá giỏ hàng hoặc session buy_now
        if ($isBuyNow) {
            session()->forget('buy_now');
        } else {
            if ($request->filled('selected_items')) {
                $selectedIds = explode(',', $request->selected_items);
                $fullCart = session('cart', []);
                foreach ($selectedIds as $id) {
                    unset($fullCart[$id]);
                }
                session(['cart' => $fullCart]);
                session()->forget('coupon');
            } else {
                session()->forget(['cart', 'coupon']);
            }
        }

        // ── ROUTING THEO PHƯƠNG THỨC THANH TOÁN ──
        return match ($request->payment_method) {

            // VNPay → redirect sang cổng VNPay
            'vnpay' => redirect($this->vnpay->createPaymentUrl(
                orderCode: $order->order_code,
                amount: (int) $order->total,
                orderInfo: "Thanh toan don hang {$order->order_code}",
                ipAddr: $request->ip()
            )),

            // QR Code → redirect sang trang hiển thị mã QR
            'qr' => redirect()->route('orders.qr', $order->order_code),

            // COD → trang thành công
            default => redirect()->route('orders.success', $order->order_code),
        };
    }

    // ── TRANG QR THANH TOÁN ──
    public function showQR(string $code)
    {
        $order = Order::where('order_code', $code)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Chỉ hiện QR nếu chưa thanh toán
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.success', $code);
        }

        // Tạo QR URL từ VietQR API
        $qrUrl = $this->vietqr->getQRImageUrl(
            amount: (int) $order->total,
            orderCode: $order->order_code,
        );

        $bankInfo = [
            'bank_name' => $this->vietqr->getBankName(),
            'account_no' => $this->vietqr->getAccountNo(),
            'account_name' => $this->vietqr->getAccountName(),
        ];

        return view('customer.orders.qr-payment',
            compact('order', 'qrUrl', 'bankInfo'));
    }

    // ── AJAX KIỂM TRA TRẠNG THÁI THANH TOÁN ──
    public function checkPaymentStatus(string $code)
    {
        $order = Order::where('order_code', $code)
            ->where('user_id', auth()->id())
            ->first();

        if (! $order) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json([
            'status' => $order->payment_status,
            'order_status' => $order->status,
            'redirect_url' => $order->payment_status === 'paid'
                ? route('orders.success', $code)
                : null,
        ]);
    }

    // ── ADMIN XÁC NHẬN ĐÃ NHẬN TIỀN QR ──
    public function confirmQRPayment(int $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        return back()->with('success',
            '✅ Đã xác nhận thanh toán QR cho đơn '.$order->order_code);
    }

    // ── VNPAY CALLBACK ──
    public function vnpayReturn(Request $request)
    {
        $data = $request->all();

        if (! $this->vnpay->verifyReturn($data)) {
            return redirect()->route('home')
                ->with('error', '❌ Xác thực thanh toán thất bại!');
        }

        $orderCode = $this->vnpay->getOrderCode($data);
        $order = Order::where('order_code', $orderCode)->first();

        if (! $order) {
            return redirect()->route('home')
                ->with('error', '❌ Không tìm thấy đơn hàng!');
        }

        if ($this->vnpay->isSuccess($data)) {
            $order->update([
                'payment_status' => 'paid',
                'vnpay_transaction_id' => $this->vnpay->getTransactionId($data),
                'status' => 'confirmed',
            ]);

            ActivityLogger::log('Thanh toán đơn hàng', 'Đơn hàng', $order->id, 'Thanh toán thành công đơn hàng #' . $order->order_code . ' qua cổng VNPay (Số tiền: ' . number_format($order->total) . 'đ)');


            return redirect()->route('orders.success', $order->order_code)
                ->with('success', '✅ Thanh toán VNPay thành công!');
        }

        // Thất bại hoặc Hủy thanh toán
        $msg = $this->vnpay->getResponseMessage($data['vnp_ResponseCode'] ?? '99');

        return redirect()->route('orders.detail', $order->order_code)
            ->with('warning', "⚠️ Thanh toán chưa hoàn tất: {$msg}. Bạn có thể thử thanh toán lại hoặc đổi phương thức thanh toán bên dưới!");
    }

    // ── CÁC METHOD CŨ GIỮ NGUYÊN ──
    public function success(string $code)
    {
        $order = Order::with('items')
            ->where('order_code', $code)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('customer.orders.success', compact('order'));
    }

    public function history(Request $request)
    {
        $validStatuses = ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled'];
        $status = in_array($request->status, $validStatuses) ? $request->status : null;

        $query = auth()->user()->orders()->with('items')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('customer.orders.history', compact('orders', 'status'));
    }

    public function detail(string $code)
    {
        $order = Order::with(['items.product', 'coupon'])
            ->where('order_code', $code)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('customer.orders.detail', compact('order'));
    }

    public function cancel(int $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (! in_array($order->status, ['pending', 'confirmed'])) {
            return back()->with('error',
                'Không thể huỷ đơn hàng ở trạng thái này!');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)
                    ->increment('stock', $item->quantity);
            }
            $order->update(['status' => 'cancelled']);
        });

        ActivityLogger::log('Hủy đơn hàng', 'Đơn hàng', $order->id, 'Khách hàng "' . auth()->user()->name . '" đã hủy đơn hàng #' . $order->order_code);


        return back()->with('success', '✅ Đã huỷ đơn hàng!');
    }

    // ── XUẤT HÓA ĐƠN PDF DÀNH CHO KHÁCH HÀNG ──
    public function invoice(string $code)
    {
        $order = Order::with(['items.product', 'coupon'])
            ->where('order_code', $code)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $items_images = [];
        foreach ($order->items as $item) {
            $base64 = null;
            if ($item->product_image) {
                $path = Storage::disk('public')->path($item->product_image);
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = @file_get_contents($path);
                    if ($data !== false) {
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    }
                }
            }
            $items_images[$item->id] = $base64;
        }

        $pdf = Pdf::loadView('admin.orders.invoice', compact('order', 'items_images'));

        return $pdf->stream('HoaDon_' . $order->order_code . '.pdf');
    }

    // Thanh toán lại qua VNPay
    public function repay(string $code, Request $request)
    {
        $order = Order::where('order_code', $code)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->where('payment_status', 'pending')
            ->firstOrFail();

        return redirect($this->vnpay->createPaymentUrl(
            orderCode: $order->order_code,
            amount: (int) $order->total,
            orderInfo: "Thanh toan don hang {$order->order_code}",
            ipAddr: $request->ip()
        ));
    }

    // Đổi sang thanh toán COD
    public function changePaymentMethod(string $code)
    {
        $order = Order::where('order_code', $code)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->where('payment_status', 'pending')
            ->firstOrFail();

        $order->update([
            'payment_method' => 'cod'
        ]);

        ActivityLogger::log('Đổi phương thức thanh toán', 'Đơn hàng', $order->id, 'Khách hàng "' . auth()->user()->name . '" đã đổi phương thức thanh toán đơn hàng #' . $order->order_code . ' sang COD');

        return redirect()->route('orders.detail', $order->order_code)
            ->with('success', '✅ Đã đổi phương thức thanh toán sang COD thành công!');
    }
}
