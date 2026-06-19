<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $query->where('order_code', 'LIKE', "%{$request->search}%")
                ->orWhereHas('user', fn ($q) => $q->where('name', 'LIKE', "%{$request->search}%"));
        }

        $orders = $query->paginate(15)->withQueryString();

        // Đếm theo từng trạng thái
        $statusCounts = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')->pluck('count', 'status');

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(int $id)
    {
        $order = Order::with(['user', 'items.product', 'coupon'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipping,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);

        // Nếu huỷ → hoàn kho
        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)
                    ->increment('stock', $item->quantity);
            }
        }

        // Nếu delivered → cập nhật payment
        if ($request->status === 'delivered' &&
            $order->payment_method === 'cod') {
            $order->payment_status = 'paid';
        }

        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        ActivityLogger::log('Cập nhật đơn hàng', 'Đơn hàng', $order->id, 'Đã cập nhật trạng thái đơn hàng #' . $order->order_code . ' từ "' . $oldStatus . '" sang "' . $request->status . '" (Tổng thanh toán: ' . number_format($order->total) . 'đ)');


        return back()->with('success', '✅ Đã cập nhật trạng thái đơn hàng!');
    }

    // ── XÁC NHẬN ĐÃ NHẬN TIỀN QR ──
    public function confirmQRPayment(int $id)
    {
        $order = Order::findOrFail($id);

        if ($order->payment_method !== 'qr') {
            return back()->with('error', 'Đơn hàng này không phải thanh toán QR!');
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        ActivityLogger::log('Xác nhận thanh toán QR', 'Đơn hàng', $order->id, 'Đã xác nhận thanh toán chuyển khoản QR thành công cho đơn hàng #' . $order->order_code . ' (Số tiền: ' . number_format($order->total) . 'đ)');


        return back()->with('success',
            "✅ Đã xác nhận thanh toán QR cho đơn {$order->order_code}!");
    }

    // ── XUẤT HÓA ĐƠN PDF ĐƠN HÀNG DÀNH CHO ADMIN ──
    public function invoice(int $id)
    {
        $order = Order::with(['user', 'items.product', 'coupon'])->findOrFail($id);

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
}
