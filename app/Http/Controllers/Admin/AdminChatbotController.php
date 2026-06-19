<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminChatbotController extends Controller
{
    public function __construct(
        private GeminiService $gemini
    ) {}

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'history' => 'nullable|array',
            'history.*.role' => 'required|in:user,assistant',
            'history.*.content' => 'required|string',
        ]);

        // Aggregate business snapshot (chỉ số liệu, không PII).
        $context = $this->buildContext();

        $response = $this->gemini->chatAdmin(
            userMessage: $request->message,
            context: $context,
            history: $request->history ?? []
        );

        return response()->json([
            'success' => true,
            'response' => $response,
        ]);
    }

    /**
     * Tổng hợp số liệu kinh doanh để truyền vào Gemini.
     * Giữ payload < ~1500 token: chỉ aggregate, không list từng row.
     */
    private function buildContext(): array
    {
        $startOfMonth = now('Asia/Ho_Chi_Minh')->startOfMonth();
        $startOfYear = now('Asia/Ho_Chi_Minh')->startOfYear();

        // Doanh thu (chỉ tính đơn đã thanh toán)
        $revenueMonth = (int) Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('total');

        $revenueYear = (int) Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $startOfYear)
            ->sum('total');

        // Số đơn theo trạng thái
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as cnt'))
            ->groupBy('status')->pluck('cnt', 'status')->toArray();

        // Số đơn theo phương thức thanh toán
        $ordersByMethod = Order::select('payment_method', DB::raw('COUNT(*) as cnt'))
            ->groupBy('payment_method')->pluck('cnt', 'payment_method')->toArray();

        // User stats
        $userCount = User::where('role', 'customer')->count();
        $newUsersThisMonth = User::where('role', 'customer')
            ->where('created_at', '>=', $startOfMonth)->count();

        // Tồn kho thấp (<=5)
        $lowStockProducts = Product::where('is_active', true)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(10)
            ->get(['name', 'stock'])
            ->map(fn ($p) => "{$p->name} ({$p->stock} chiếc)")
            ->implode('; ');

        // Top 5 sản phẩm bán chạy (theo số lượng đã giao)
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as sold'))
            ->whereHas('order', fn ($q) => $q->where('payment_status', 'paid'))
            ->groupBy('product_name')
            ->orderByDesc('sold')
            ->take(5)
            ->get()
            ->map(fn ($r) => "{$r->product_name} ({$r->sold} chiếc)")
            ->implode('; ');

        // Tổng số sản phẩm + thương hiệu + danh mục
        $productCount = Product::where('is_active', true)->count();

        // Review pending
        $pendingReviews = Review::where('status', 'pending')->count();

        return [
            'Doanh thu tháng này (VND, đã thanh toán)' => number_format($revenueMonth, 0, ',', '.'),
            'Doanh thu năm nay (VND, đã thanh toán)' => number_format($revenueYear, 0, ',', '.'),
            'Số đơn theo trạng thái' => $ordersByStatus,
            'Số đơn theo phương thức thanh toán' => $ordersByMethod,
            'Số khách hàng' => $userCount,
            'Khách hàng mới tháng này' => $newUsersThisMonth,
            'Tổng số sản phẩm đang bán' => $productCount,
            'Sản phẩm sắp hết hàng (<=5)' => $lowStockProducts ?: '(không có)',
            'Top 5 bán chạy' => $topProducts ?: '(chưa có đơn paid)',
            'Review chờ duyệt' => $pendingReviews,
        ];
    }
}
