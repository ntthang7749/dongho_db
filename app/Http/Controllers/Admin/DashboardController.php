<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── THỐNG KÊ TỔNG QUAN ──
        $stats = [
            'total_revenue' => Order::where('status', 'delivered')->sum('total'),
            'total_orders'  => Order::count(),
            'total_users'   => User::where('role', 'customer')->count(),
            'total_products' => Product::where('is_active', true)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock'     => Product::where('stock', '<=', 5)
                ->where('is_active', true)->count(),
        ];

        // ── DOANH THU 12 THÁNG GẦN NHẤT (cho Chart.js) ──
        $revenueData = Order::where('status', 'delivered')
            ->where('created_at', '>=', now()->subMonths(12))
            ->selectRaw('MONTH(created_at) as month,
                          YEAR(created_at)  as year,
                          SUM(total)        as total')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        // ── ĐƠN HÀNG THEO TRẠNG THÁI (cho Chart tròn) ──
        $orderStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')->get()
            ->pluck('count', 'status');

        // ── ĐƠN HÀNG GẦN ĐÂY ──
        $recentOrders = Order::with('user')
            ->latest()->take(8)->get();

        // ── SẢN PHẨM SẮP HẾT HÀNG ──
        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 5)
            ->where('is_active', true)
            ->orderBy('stock')
            ->take(8)->get();

        // ── TOP SẢN PHẨM BÁN CHẠY ──
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('products.id, products.name, products.thumbnail,
                          SUM(order_items.quantity) as total_sold,
                          SUM(order_items.subtotal) as total_revenue')
            ->groupBy('products.id', 'products.name', 'products.thumbnail')
            ->orderByDesc('total_sold')
            ->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'revenueData', 'orderStatus',
            'recentOrders', 'lowStockProducts', 'topProducts'
        ));
    }

    /**
     * API: Lọc doanh thu theo khoảng ngày/tháng/năm
     * Dùng DB::select() với raw SQL để tránh MySQL ONLY_FULL_GROUP_BY strict mode
     */
    public function revenueFilter(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date|after_or_equal:date_from',
            'group_by'  => 'nullable|in:day,month,year',
        ]);

        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo   = $request->date_to   ?? now()->toDateString();
        $groupBy  = $request->group_by  ?? 'day';

        // ── Raw SQL tương thích ONLY_FULL_GROUP_BY ──
        // Dùng MIN(created_at) trong mọi trường hợp để MySQL strict mode chấp nhận
        if ($groupBy === 'day') {
            $sql = "
                SELECT
                    DATE_FORMAT(MIN(created_at), '%d/%m/%Y') AS period_label,
                    SUM(total)                               AS total_revenue,
                    COUNT(*)                                 AS total_orders
                FROM orders
                WHERE status = 'delivered'
                  AND DATE(created_at) BETWEEN ? AND ?
                GROUP BY DATE(created_at)
                ORDER BY DATE(created_at) ASC
            ";
        } elseif ($groupBy === 'month') {
            $sql = "
                SELECT
                    DATE_FORMAT(MIN(created_at), '%m/%Y') AS period_label,
                    SUM(total)                            AS total_revenue,
                    COUNT(*)                              AS total_orders
                FROM orders
                WHERE status = 'delivered'
                  AND DATE(created_at) BETWEEN ? AND ?
                GROUP BY YEAR(created_at), MONTH(created_at)
                ORDER BY YEAR(created_at) ASC, MONTH(created_at) ASC
            ";
        } else {
            // year
            $sql = "
                SELECT
                    CAST(YEAR(MIN(created_at)) AS CHAR) AS period_label,
                    SUM(total)                          AS total_revenue,
                    COUNT(*)                            AS total_orders
                FROM orders
                WHERE status = 'delivered'
                  AND DATE(created_at) BETWEEN ? AND ?
                GROUP BY YEAR(created_at)
                ORDER BY YEAR(created_at) ASC
            ";
        }

        $rows = DB::select($sql, [$dateFrom, $dateTo]);

        // Chuyển stdClass sang array thuần để JSON serialize đúng
        $chartData = array_map(fn($r) => [
            'period_label'  => (string) $r->period_label,
            'total_revenue' => (float)  $r->total_revenue,
            'total_orders'  => (int)    $r->total_orders,
        ], $rows);

        $totalRevenue = array_sum(array_column($chartData, 'total_revenue'));
        $totalOrders  = array_sum(array_column($chartData, 'total_orders'));

        // Chi tiết đơn hàng hoàn thành trong khoảng lọc
        $orders = Order::with('user')
            ->where('status', 'delivered')
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($o) => [
                'order_code' => $o->order_code,
                'customer'   => $o->user->name  ?? 'N/A',
                'email'      => $o->user->email ?? '',
                'total'      => (float) $o->total,
                'created_at' => $o->created_at->format('d/m/Y H:i'),
            ]);

        return response()->json([
            'success'       => true,
            'date_from'     => $dateFrom,
            'date_to'       => $dateTo,
            'group_by'      => $groupBy,
            'chart_data'    => $chartData,
            'total_revenue' => $totalRevenue,
            'total_orders'  => $totalOrders,
            'orders'        => $orders,
        ]);
    }
}
