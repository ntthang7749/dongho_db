@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    {{-- Doanh thu --}}
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-currency-dollar fs-4"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Doanh Thu</p>
                    <h5 class="fw-bold mb-0 text-dark" id="kpi-total-revenue">
                        {{ number_format($stats['total_revenue'] / 1000000, 1) }}M đ
                    </h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Đơn hàng --}}
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-bag fs-4"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Đơn Hàng</p>
                    <h5 class="fw-bold mb-0 text-dark" id="kpi-total-orders">{{ number_format($stats['total_orders']) }}</h5>
                </div>
            </div>
            @if($stats['pending_orders'] > 0)
                <small class="text-danger-emphasis mt-2 d-flex align-items-center gap-1 bg-danger-subtle px-2 py-1 rounded-2">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ $stats['pending_orders'] }} đơn chờ xử lý
                </small>
            @endif
        </div>
    </div>

    {{-- Người dùng --}}
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Khách Hàng</p>
                    <h5 class="fw-bold mb-0 text-dark">{{ number_format($stats['total_users']) }}</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Sản phẩm --}}
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger-subtle text-danger">
                    <i class="bi bi-watch fs-4"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Sản Phẩm</p>
                    <h5 class="fw-bold mb-0 text-dark">{{ number_format($stats['total_products']) }}</h5>
                </div>
            </div>
            @if($stats['low_stock'] > 0)
                <small class="text-warning-emphasis mt-2 d-flex align-items-center gap-1 bg-warning-subtle px-2 py-1 rounded-2">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ $stats['low_stock'] }} SP sắp hết hàng
                </small>
            @endif
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════
     BỘ LỌC DOANH THU THEO NGÀY / THÁNG / NĂM
     ════════════════════════════════════════════════════════ --}}
<div class="card table-card mb-4" id="revenueFilterCard">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span class="fw-bold">
            <i class="bi bi-funnel me-2 text-warning"></i>Báo Cáo Doanh Thu Theo Thời Gian
        </span>
        <div class="d-flex gap-2 flex-wrap">
            <button id="btnExportPDF" class="btn btn-sm btn-danger" disabled>
                <i class="bi bi-file-earmark-pdf me-1"></i>Xuất PDF
            </button>
            <button id="btnExportExcel" class="btn btn-sm btn-success" disabled>
                <i class="bi bi-file-earmark-excel me-1"></i>Xuất Excel
            </button>
        </div>
    </div>
    <div class="card-body">
        {{-- Filter Form --}}
        <form id="revenueFilterForm" class="row g-2 align-items-end mb-3">
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label small fw-semibold mb-1">
                    <i class="bi bi-calendar-event me-1"></i>Từ ngày
                </label>
                <input type="date" id="filterDateFrom" name="date_from"
                       class="form-control form-control-sm"
                       value="{{ now()->startOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label small fw-semibold mb-1">
                    <i class="bi bi-calendar-event-fill me-1"></i>Đến ngày
                </label>
                <input type="date" id="filterDateTo" name="date_to"
                       class="form-control form-control-sm"
                       value="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label small fw-semibold mb-1">
                    <i class="bi bi-bar-chart-steps me-1"></i>Nhóm theo
                </label>
                <select id="filterGroupBy" name="group_by" class="form-select form-select-sm">
                    <option value="day">Theo Ngày</option>
                    <option value="month" selected>Theo Tháng</option>
                    <option value="year">Theo Năm</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-dark w-100" id="btnFilter">
                        <i class="bi bi-search me-1"></i>Lọc
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnResetFilter" title="Đặt lại">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
            </div>
        </form>

        {{-- Quick range shortcuts --}}
        <div class="d-flex gap-2 flex-wrap mb-3">
            <span class="small text-muted me-1 align-self-center">Nhanh:</span>
            <button class="btn btn-xs btn-outline-dark quick-range" data-range="today">Hôm nay</button>
            <button class="btn btn-xs btn-outline-dark quick-range" data-range="week">7 ngày</button>
            <button class="btn btn-xs btn-outline-dark quick-range" data-range="month">Tháng này</button>
            <button class="btn btn-xs btn-outline-dark quick-range" data-range="last_month">Tháng trước</button>
            <button class="btn btn-xs btn-outline-dark quick-range" data-range="quarter">Quý này</button>
            <button class="btn btn-xs btn-outline-dark quick-range" data-range="year">Năm này</button>
        </div>

        {{-- Loading spinner --}}
        <div id="filterLoading" class="text-center py-4 d-none">
            <div class="spinner-border text-warning" role="status">
                <span class="visually-hidden">Đang tải...</span>
            </div>
            <p class="text-muted small mt-2">Đang tải dữ liệu...</p>
        </div>

        {{-- Kết quả tổng hợp --}}
        <div id="filterResult" class="d-none">
            {{-- Summary cards --}}
            <div class="row g-2 mb-3" id="filterSummary">
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #0b0b14 0%, #1e1e38 100%); border-left: 4px solid var(--gold);">
                        <p class="text-warning small mb-1"><i class="bi bi-cash-stack me-1"></i>Tổng Doanh Thu</p>
                        <h5 class="fw-bold mb-0" id="summaryRevenue" style="color: var(--gold-light) !important;">0đ</h5>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #0b0b14 0%, #1e1e38 100%); border-left: 4px solid #0dcaf0;">
                        <p class="text-info small mb-1"><i class="bi bi-bag-check me-1"></i>Tổng đơn hoàn thành</p>
                        <h5 class="text-info fw-bold mb-0" id="summaryOrders">0</h5>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #0b0b14 0%, #1e1e38 100%); border-left: 4px solid #198754;">
                        <p class="text-success small mb-1"><i class="bi bi-graph-up me-1"></i>Doanh thu TB/đơn</p>
                        <h5 class="text-success fw-bold mb-0" id="summaryAvg">0đ</h5>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #0b0b14 0%, #1e1e38 100%); border-left: 4px solid #6f42c1;">
                        <p class="text-light small mb-1" style="opacity: 0.8;"><i class="bi bi-calendar-range me-1"></i>Khoảng thời gian</p>
                        <h5 class="text-white fw-bold mb-0 small" id="summaryRange">—</h5>
                    </div>
                </div>
            </div>

            {{-- Biểu đồ doanh thu lọc --}}
            <div class="mb-3" style="position:relative; height:200px;">
                <canvas id="filteredRevenueChart"></canvas>
            </div>

            {{-- Bảng chi tiết theo kỳ --}}
            <div class="table-responsive mb-3">
                <table class="table table-hover align-middle table-sm" id="filterPeriodTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kỳ</th>
                            <th class="text-end">Doanh Thu</th>
                            <th class="text-end">Số Đơn</th>
                            <th class="text-end">TB/Đơn</th>
                            <th class="text-end">% Tổng</th>
                        </tr>
                    </thead>
                    <tbody id="filterPeriodBody"></tbody>
                    <tfoot class="fw-bold table-light" id="filterPeriodFoot" style="border-top: 2px solid var(--gold);"></tfoot>
                </table>
            </div>

            {{-- Toggle chi tiết đơn hàng --}}
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-list-ul me-2"></i>Chi Tiết Đơn Hàng Hoàn Thành
                </h6>
                <button class="btn btn-sm btn-outline-secondary" id="btnToggleOrders">
                    <i class="bi bi-chevron-down"></i> Hiện đơn hàng
                </button>
            </div>
            <div id="filterOrdersTable" class="d-none">
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã Đơn</th>
                                <th>Khách Hàng</th>
                                <th>Email</th>
                                <th class="text-end">Tổng Tiền</th>
                                <th>Thời Gian</th>
                            </tr>
                        </thead>
                        <tbody id="filterOrdersBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Empty state --}}
        <div id="filterEmpty" class="text-center py-5 d-none">
            <i class="bi bi-search fs-1 text-muted opacity-50"></i>
            <p class="text-muted mt-2">Không có doanh thu trong khoảng thời gian này.</p>
        </div>
    </div>
</div>

{{-- CHARTS --}}
<div class="row g-3 mb-4">
    {{-- Doanh thu theo tháng --}}
    <div class="col-md-8">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart me-2"></i>Doanh Thu 12 Tháng</span>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Đơn hàng theo trạng thái --}}
    <div class="col-md-4">
        <div class="card table-card">
            <div class="card-header">
                <i class="bi bi-pie-chart me-2"></i>Trạng Thái Đơn Hàng
            </div>
            <div class="card-body">
                <canvas id="orderChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- TABLES --}}
<div class="row g-3">
    {{-- Đơn hàng gần đây --}}
    <div class="col-md-8">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Đơn Hàng Gần Đây</span>
                <a href="{{ route('admin.orders.index') }}"
                   class="btn btn-sm btn-outline-dark">Xem tất cả</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        @php
                            $colors = [
                                'pending'   => 'warning',
                                'confirmed' => 'info',
                                'shipping'  => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                            ];
                            $labels = [
                                'pending'   => 'Chờ xác nhận',
                                'confirmed' => 'Đã xác nhận',
                                'shipping'  => 'Đang giao',
                                'delivered' => 'Đã giao',
                                'cancelled' => 'Đã huỷ',
                            ];
                        @endphp
                        <tr>
                            <td><code>{{ $order->order_code }}</code></td>
                            <td>
                                <p class="mb-0 fw-semibold small">{{ $order->user->name ?? 'N/A' }}</p>
                                <small class="text-muted">{{ $order->created_at->format('d/m H:i') }}</small>
                            </td>
                            <td class="text-danger fw-bold">
                                {{ number_format($order->total) }}đ
                            </td>
                            <td>
                                <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}-subtle text-{{ $colors[$order->status] ?? 'secondary' }}">
                                    {{ $labels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="btn btn-xs btn-outline-dark btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sản phẩm sắp hết hàng --}}
    <div class="col-md-4">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Sắp Hết Hàng</span>
                <a href="{{ route('admin.products.index', ['filter' => 'low_stock']) }}"
                   class="btn btn-sm btn-outline-warning">Xem tất cả</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($lowStockProducts as $p)
                <div class="list-group-item d-flex align-items-center gap-2 py-2">
                    <img src="{{ img_url($p->thumbnail) }}"
                         width="40" height="40" style="object-fit:cover; border-radius:6px;">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-0 small fw-semibold text-truncate">{{ $p->name }}</p>
                    </div>
                    <span class="badge {{ $p->stock == 0 ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning-emphasis' }}">
                        {{ $p->stock == 0 ? 'Hết hàng' : $p->stock . ' còn' }}
                    </span>
                </div>
                @empty
                <div class="list-group-item text-center text-muted py-3">
                    ✅ Không có sản phẩm nào sắp hết!
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.btn-xs { font-size: 0.75rem; padding: 0.2rem 0.5rem; }
#revenueFilterCard .card-header { 
    background: linear-gradient(135deg, #0b0b14 0%, #1a1a30 100%); 
    color: #fff; 
    border-bottom: 1px solid var(--border-color);
}
#revenueFilterCard .card-header .btn-danger { 
    background: linear-gradient(135deg, #dc3545, #bd2130); 
    border: none;
    box-shadow: 0 2px 5px rgba(220,53,69,0.2);
}
#revenueFilterCard .card-header .btn-success { 
    background: linear-gradient(135deg, #198754, #146c43); 
    border: none;
    box-shadow: 0 2px 5px rgba(25,135,84,0.2);
}
#revenueFilterCard .card-header .btn-danger:hover,
#revenueFilterCard .card-header .btn-success:hover {
    transform: translateY(-1px);
    opacity: 0.9;
}
#filterPeriodTable thead th { font-size: 0.8rem; }
#filterPeriodTable tbody td { font-size: 0.85rem; }
.quick-range { 
    font-size: 0.75rem; 
    padding: 0.25rem 0.75rem; 
    border-radius: 6px;
    border: 1px solid rgba(0,0,0,0.08);
    background: #fff;
    color: #475569;
    transition: var(--transition);
}
.quick-range:hover, .quick-range.active { 
    background: linear-gradient(135deg, #0b0b14, #1a1a2e) !important; 
    color: var(--gold-light) !important; 
    border-color: var(--gold) !important; 
    box-shadow: 0 3px 8px rgba(201,168,76,0.15);
}
</style>
@endpush

@push('scripts')
{{-- SheetJS (xlsx) --}}
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
{{-- jsPDF + AutoTable --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<script>
const allTimeRevenue = {{ $stats['total_revenue'] }};
const allTimeOrders = {{ $stats['total_orders'] }};

// ══════════════════════════════════════════
// BIỂU ĐỒ DOANH THU 12 THÁNG (mặc định)
// ══════════════════════════════════════════
const revenueData = @json($revenueData);
const months      = revenueData.map(r => `Tháng ${r.month}/${r.year}`);
const revenues    = revenueData.map(r => Math.round(r.total / 1000));

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Doanh thu (nghìn đồng)',
            data: revenues,
            backgroundColor: 'rgba(201, 168, 76, 0.85)',
            borderColor: '#c9a84c',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => v.toLocaleString('vi') + 'k' }
            }
        }
    }
});

// ── BIỂU ĐỒ TRẠNG THÁI ──
const orderStatus = @json($orderStatus);
const statusLabels = { pending:'Chờ xác nhận', confirmed:'Đã xác nhận', shipping:'Đang giao', delivered:'Đã giao', cancelled:'Đã huỷ' };
const statusColors = ['#ffc107','#0dcaf0','#0d6efd','#198754','#dc3545'];
const statusKeys   = Object.keys(orderStatus);

new Chart(document.getElementById('orderChart'), {
    type: 'doughnut',
    data: {
        labels: statusKeys.map(k => statusLabels[k] || k),
        datasets: [{ data: statusKeys.map(k => orderStatus[k]), backgroundColor: statusColors }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }
    }
});

// ══════════════════════════════════════════
// BỘ LỌC DOANH THU
// ══════════════════════════════════════════
const API_URL = "{{ route('admin.dashboard.revenue-filter') }}";
let filteredChart = null;
let currentData   = null; // lưu kết quả để dùng khi xuất

// ─ Format tiền VNĐ ─
function formatVND(n) {
    return Number(n).toLocaleString('vi-VN') + 'đ';
}

// ─ Quick range shortcuts ─
const today = new Date();

// Dùng local date (tránh lệch ngày do toISOString() trả về UTC)
function toYMD(d) {
    const y  = d.getFullYear();
    const m  = String(d.getMonth() + 1).padStart(2, '0');
    const dd = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${dd}`;
}

document.querySelectorAll('.quick-range').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.quick-range').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const range = this.dataset.range;
        let from, to = toYMD(today), grp = 'day';

        if (range === 'today') {
            from = to;
            grp  = 'day';
        } else if (range === 'week') {
            const d = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 6);
            from = toYMD(d);
            grp  = 'day';
        } else if (range === 'month') {
            from = toYMD(new Date(today.getFullYear(), today.getMonth(), 1));
            grp  = 'day';
        } else if (range === 'last_month') {
            const f = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const t = new Date(today.getFullYear(), today.getMonth(),     0); // ngày cuối tháng trước
            from = toYMD(f);
            to   = toYMD(t);
            grp  = 'day';
        } else if (range === 'quarter') {
            const q = Math.floor(today.getMonth() / 3);
            from = toYMD(new Date(today.getFullYear(), q * 3, 1));
            grp  = 'month';
        } else if (range === 'year') {
            from = toYMD(new Date(today.getFullYear(), 0, 1));
            grp  = 'month';
        }

        document.getElementById('filterDateFrom').value = from;
        document.getElementById('filterDateTo').value   = to;
        document.getElementById('filterGroupBy').value  = grp;
        doFilter();
    });
});

// ─ Reset ─
document.getElementById('btnResetFilter').addEventListener('click', () => {
    // Dùng toYMD local (không UTC) để tránh lệch ngày
    document.getElementById('filterDateFrom').value = toYMD(new Date(today.getFullYear(), today.getMonth(), 1));
    document.getElementById('filterDateTo').value   = toYMD(new Date(today.getFullYear(), today.getMonth(), today.getDate()));
    document.getElementById('filterGroupBy').value  = 'month';
    document.querySelectorAll('.quick-range').forEach(b => b.classList.remove('active'));
    document.getElementById('filterResult').classList.add('d-none');
    document.getElementById('filterEmpty').classList.add('d-none');
    document.getElementById('btnExportPDF').disabled = true;
    document.getElementById('btnExportExcel').disabled = true;
    currentData = null;

    // Reset KPI cards to all-time values
    document.getElementById('kpi-total-revenue').innerText = (allTimeRevenue / 1000000).toFixed(1) + 'M đ';
    document.getElementById('kpi-total-orders').innerText = allTimeOrders.toLocaleString('vi-VN');
});

// ─ Submit form ─
document.getElementById('revenueFilterForm').addEventListener('submit', function (e) {
    e.preventDefault();
    doFilter();
});

async function doFilter() {
    const dateFrom = document.getElementById('filterDateFrom').value;
    const dateTo   = document.getElementById('filterDateTo').value;
    const groupBy  = document.getElementById('filterGroupBy').value;

    document.getElementById('filterLoading').classList.remove('d-none');
    document.getElementById('filterResult').classList.add('d-none');
    document.getElementById('filterEmpty').classList.add('d-none');
    document.getElementById('btnExportPDF').disabled = true;
    document.getElementById('btnExportExcel').disabled = true;

    try {
        const url = `${API_URL}?date_from=${dateFrom}&date_to=${dateTo}&group_by=${groupBy}`;
        const res  = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await res.json();
        document.getElementById('filterLoading').classList.add('d-none');

        if (!data.success) { alert('Có lỗi xảy ra.'); return; }
        currentData = data;

        if (data.chart_data.length === 0) {
            document.getElementById('filterEmpty').classList.remove('d-none');
            // Update KPI cards to zero
            document.getElementById('kpi-total-revenue').innerText = '0.0M đ';
            document.getElementById('kpi-total-orders').innerText = '0';
            return;
        }

        // ── Phải hiện container TRƯỚC khi vẽ chart ──
        // Chart.js cần canvas có kích thước thực (display != none)
        document.getElementById('filterResult').classList.remove('d-none');
        document.getElementById('btnExportPDF').disabled   = false;
        document.getElementById('btnExportExcel').disabled = false;

        // Dùng requestAnimationFrame để đợi DOM paint xong rồi mới render chart
        requestAnimationFrame(() => renderFilterResult(data));

    } catch (err) {
        document.getElementById('filterLoading').classList.add('d-none');
        alert('Không kết nối được tới server.');
    }
}

function renderFilterResult(data) {
    const totalRev  = parseFloat(data.total_revenue) || 0;
    const totalOrd  = parseInt(data.total_orders) || 0;
    const avgRev    = totalOrd > 0 ? totalRev / totalOrd : 0;

    // Summary
    document.getElementById('summaryRevenue').textContent = formatVND(totalRev);
    document.getElementById('summaryOrders').textContent  = totalOrd.toLocaleString('vi-VN');
    document.getElementById('summaryAvg').textContent     = formatVND(avgRev);
    document.getElementById('summaryRange').textContent   =
        `${data.date_from.split('-').reverse().join('/')} → ${data.date_to.split('-').reverse().join('/')}`;

    // Update top KPI cards to match filter results
    document.getElementById('kpi-total-revenue').innerText = (totalRev / 1000000).toFixed(1) + 'M đ';
    document.getElementById('kpi-total-orders').innerText = totalOrd.toLocaleString('vi-VN');

    // Chart
    const rows   = data.chart_data;
    const labels = rows.map(r => r.period_label);
    const vals   = rows.map(r => parseFloat(r.total_revenue) / 1000);

    // Huỷ chart cũ nếu có
    if (filteredChart) {
        filteredChart.destroy();
        filteredChart = null;
    }

    // Lấy canvas và reset để tránh lỗi kích thước cũ
    const canvasEl = document.getElementById('filteredRevenueChart');
    canvasEl.style.display = 'block';

    filteredChart = new Chart(canvasEl, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Doanh thu (nghìn đồng)',
                data: vals,
                backgroundColor: 'rgba(201,168,76,0.85)',
                borderColor: '#c9a84c',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 600 },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const raw = ctx.raw * 1000;
                            return ' ' + raw.toLocaleString('vi-VN') + 'đ';
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => v.toLocaleString('vi') + 'k' },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });

    // Bảng kỳ
    const tbody = document.getElementById('filterPeriodBody');
    const tfoot = document.getElementById('filterPeriodFoot');
    tbody.innerHTML = '';

    rows.forEach((r, i) => {
        const rev = parseFloat(r.total_revenue) || 0;
        const ord = parseInt(r.total_orders) || 0;
        const avg = ord > 0 ? rev / ord : 0;
        const pct = totalRev > 0 ? ((rev / totalRev) * 100).toFixed(1) : '0.0';
        tbody.innerHTML += `
            <tr>
                <td class="text-muted">${i + 1}</td>
                <td><strong>${r.period_label}</strong></td>
                <td class="text-end text-success fw-semibold">${formatVND(rev)}</td>
                <td class="text-end">${ord.toLocaleString('vi-VN')}</td>
                <td class="text-end text-muted">${formatVND(avg)}</td>
                <td class="text-end">
                    <div class="d-flex align-items-center justify-content-end gap-1">
                        <div class="progress flex-grow-1" style="height:6px; min-width:40px; max-width:60px;">
                            <div class="progress-bar bg-warning" style="width:${pct}%"></div>
                        </div>
                        <small>${pct}%</small>
                    </div>
                </td>
            </tr>`;
    });

    tfoot.innerHTML = `
        <tr>
            <td colspan="2">Tổng cộng</td>
            <td class="text-end">${formatVND(totalRev)}</td>
            <td class="text-end">${totalOrd.toLocaleString('vi-VN')}</td>
            <td class="text-end">${formatVND(avgRev)}</td>
            <td class="text-end">100%</td>
        </tr>`;

    // Bảng đơn hàng
    const ordBody = document.getElementById('filterOrdersBody');
    ordBody.innerHTML = '';
    (data.orders || []).forEach(o => {
        ordBody.innerHTML += `
            <tr>
                <td><code>${o.order_code}</code></td>
                <td>${o.customer}</td>
                <td class="text-muted small">${o.email}</td>
                <td class="text-end text-success fw-semibold">${formatVND(o.total)}</td>
                <td class="text-muted small">${o.created_at}</td>
            </tr>`;
    });
}

// Toggle bảng đơn hàng
document.getElementById('btnToggleOrders').addEventListener('click', function () {
    const el = document.getElementById('filterOrdersTable');
    el.classList.toggle('d-none');
    const ico = this.querySelector('i');
    if (el.classList.contains('d-none')) {
        ico.className = 'bi bi-chevron-down';
        this.innerHTML = '<i class="bi bi-chevron-down"></i> Hiện đơn hàng';
    } else {
        this.innerHTML = '<i class="bi bi-chevron-up"></i> Ẩn đơn hàng';
    }
});

// ══════════════════════════════════════════
// XUẤT PDF
// ══════════════════════════════════════════
document.getElementById('btnExportPDF').addEventListener('click', function () {
    if (!currentData) return;
    const { jsPDF } = window.jspdf;
    
    // A4 Landscape (297 x 210 mm)
    const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
    
    const PW = 297;
    const PH = 210;
    
    // Theme Colors
    const NAVY = [26, 26, 46];
    const GOLD = [201, 168, 76];
    const WHITE = [255, 255, 255];
    const LIGHT = [248, 249, 250];
    const GREEN = [39, 174, 96];
    const BLUE = [41, 128, 185];
    
    const totalRev   = parseFloat(currentData.total_revenue) || 0;
    const totalOrd   = parseInt(currentData.total_orders)    || 0;
    const avgRev     = totalOrd > 0 ? Math.round(totalRev / totalOrd) : 0;
    const groupLabel = currentData.group_by === 'day' ? 'Ngay' : currentData.group_by === 'month' ? 'Thang' : 'Nam';
    const dateFrom   = currentData.date_from.split('-').reverse().join('/');
    const dateTo     = currentData.date_to.split('-').reverse().join('/');
    const exportTime = new Date().toLocaleString('vi-VN');

    // ── INFO BOXES (KPI Cards on Page 1) ──
    const BOX_DEFS = [
        { label: 'TU NGAY', value: dateFrom,                          bg: [15, 52, 96],   accent: [100, 160, 230] },
        { label: 'DEN NGAY', value: dateTo,                           bg: [14, 80, 50],   accent: [80, 200, 120]  },
        { label: 'NHOM THEO', value: groupLabel,                      bg: [80, 30, 120],  accent: [180, 120, 240] },
        { label: 'TONG DOANH THU', value: totalRev.toLocaleString('vi-VN') + ' VND', bg: [130, 70, 10], accent: [240, 180, 60] },
        { label: 'TONG DON HOAN THANH', value: `${totalOrd} don`,     bg: [26, 26, 46],   accent: [200, 168, 76]  },
        { label: 'TRUNG BINH / DON', value: avgRev.toLocaleString('vi-VN') + ' VND', bg: [20, 80, 80],  accent: [80, 210, 210] },
    ];
    
    const bw = 43; // Box width
    const bh = 15; // Box height
    const bsy = 34; // Box start Y
    const gap = 3;  // Gap
    const bsx = 14; // Start X
    
    BOX_DEFS.forEach((b, i) => {
        const x = bsx + i * (bw + gap);
        doc.setFillColor(...b.bg);
        doc.roundedRect(x, bsy, bw, bh, 2, 2, 'F');
        doc.setFillColor(...b.accent);
        doc.roundedRect(x, bsy, bw, 2, 1, 1, 'F');
        doc.setTextColor(...b.accent);
        doc.setFontSize(6.5);
        doc.setFont('helvetica', 'bold');
        doc.text(b.label, x + 3, bsy + 5.5);
        doc.setTextColor(...WHITE);
        doc.setFontSize(8);
        doc.text(b.value, x + 3, bsy + 11.5);
    });

    // ── BẢNG THỐNG KÊ THEO KỲ ──
    const t1Y = 54;
    doc.setFillColor(...NAVY);
    doc.roundedRect(14, t1Y, 100, 6, 1, 1, 'F');
    doc.setFillColor(...GOLD);
    doc.roundedRect(14, t1Y, 3, 6, 1, 0, 'F');
    doc.setTextColor(...GOLD);
    doc.setFontSize(8);
    doc.setFont('helvetica', 'bold');
    doc.text('THONG KE DOANH THU THEO KY', 20, t1Y + 4.2);
    
    const periodRows = currentData.chart_data.map((r, i) => {
        const rev = parseFloat(r.total_revenue) || 0;
        const ord = parseInt(r.total_orders)    || 0;
        const avg = ord > 0 ? Math.round(rev / ord) : 0;
        const pct = totalRev > 0 ? ((rev / totalRev) * 100).toFixed(1) : '0.0';
        return [
            { content: i + 1,                              styles: { halign: 'center', textColor: [140,140,140] } },
            { content: r.period_label,                      styles: { fontStyle: 'bold', textColor: NAVY } },
            { content: rev.toLocaleString('vi-VN') + ' VND',  styles: { halign: 'right', textColor: GREEN, fontStyle: 'bold' } },
            { content: String(ord),                         styles: { halign: 'center' } },
            { content: avg.toLocaleString('vi-VN') + ' VND',  styles: { halign: 'right', textColor: BLUE } },
            { content: pct + '%',                           styles: { halign: 'center', textColor: [160, 80, 20] } },
        ];
    });
    
    doc.autoTable({
        startY: t1Y + 8,
        head: [[
            { content: '#',         styles: { halign: 'center', cellWidth: 10 } },
            { content: 'Ky / Period',styles: { halign: 'left',   cellWidth: 40 } },
            { content: 'Doanh Thu', styles: { halign: 'right',  cellWidth: 60 } },
            { content: 'So Don',    styles: { halign: 'center', cellWidth: 30 } },
            { content: 'TB/Don',    styles: { halign: 'right',  cellWidth: 60 } },
            { content: '% Tong',    styles: { halign: 'center', cellWidth: 30 } },
        ]],
        body: periodRows,
        foot: [[
            { content: '' },
            { content: 'TONG CONG', styles: { fontStyle: 'bold' } },
            { content: totalRev.toLocaleString('vi-VN') + ' VND', styles: { halign: 'right', fontStyle: 'bold' } },
            { content: String(totalOrd), styles: { halign: 'center', fontStyle: 'bold' } },
            { content: avgRev.toLocaleString('vi-VN') + ' VND', styles: { halign: 'right', fontStyle: 'bold' } },
            { content: '100%', styles: { halign: 'center', fontStyle: 'bold' } },
        ]],
        styles: {
            fontSize: 7.5, cellPadding: { top: 2.5, bottom: 2.5, left: 3, right: 3 },
            lineWidth: 0.15, lineColor: [210, 220, 235], textColor: [50, 50, 70],
        },
        headStyles: { fillColor: NAVY, textColor: GOLD, fontStyle: 'bold', fontSize: 7.5, cellPadding: 3 },
        footStyles: { fillColor: [255, 248, 220], textColor: NAVY },
        alternateRowStyles: { fillColor: LIGHT },
        margin: { left: 14, right: 14 },
    });

    // ── BẢNG CHI TIẾT ĐƠN HÀNG ──
    const afterT1 = doc.lastAutoTable.finalY;
    const spaceNeeded = 45;
    const shouldAddPage = afterT1 > (PH - spaceNeeded - 15);
    const t2Y = shouldAddPage ? 18 : afterT1 + 10;
    if (shouldAddPage) {
        doc.addPage();
    }
    
    doc.setFillColor(...NAVY);
    doc.roundedRect(14, t2Y, 140, 6, 1, 1, 'F');
    doc.setFillColor(...GOLD);
    doc.roundedRect(14, t2Y, 3, 6, 1, 0, 'F');
    doc.setTextColor(...GOLD);
    doc.setFontSize(8);
    doc.setFont('helvetica', 'bold');
    doc.text(`CHI TIET DON HANG HOAN THANH  (${currentData.orders.length} don)`, 20, t2Y + 4.2);
    
    const orderBodyRows = (currentData.orders || []).map((o, i) => [
        { content: i + 1, styles: { halign: 'center', textColor: [140,140,140] } },
        { content: o.order_code, styles: { fontStyle: 'bold', textColor: BLUE } },
        { content: o.customer },
        { content: o.email, styles: { textColor: [120,120,120], fontSize: 6.5 } },
        { content: parseFloat(o.total).toLocaleString('vi-VN') + ' VND', styles: { halign: 'right', textColor: GREEN, fontStyle: 'bold' } },
        { content: o.created_at, styles: { halign: 'center', textColor: [120,120,120] } },
    ]);
    
    doc.autoTable({
        startY: t2Y + 8,
        head: [[
            { content: '#',         styles: { halign: 'center', cellWidth: 10 } },
            { content: 'Ma Don Hang',styles: { cellWidth: 45 } },
            { content: 'Khach Hang', styles: { cellWidth: 55 } },
            { content: 'Email',     styles: { cellWidth: 80 } },
            { content: 'Tong Tien',  styles: { halign: 'right', cellWidth: 45 } },
            { content: 'Thoi Gian',  styles: { halign: 'center', cellWidth: 34 } },
        ]],
        body: orderBodyRows,
        styles: {
            fontSize: 7.2, cellPadding: { top: 2.2, bottom: 2.2, left: 3, right: 3 },
            lineWidth: 0.15, lineColor: [210, 220, 235], textColor: [50, 50, 70],
        },
        headStyles: { fillColor: NAVY, textColor: GOLD, fontStyle: 'bold', fontSize: 7.2, cellPadding: 3 },
        alternateRowStyles: { fillColor: LIGHT },
        margin: { left: 14, right: 14 },
    });

    // ── WATERMARK & PAGE FOOTERS ON EVERY PAGE ──
    const totalPages = doc.internal.getNumberOfPages();
    for (let p = 1; p <= totalPages; p++) {
        doc.setPage(p);
        if (p === 1) {
            // Header banner
            doc.setFillColor(26, 26, 46);
            doc.rect(0, 0, PW, 28, 'F');
            doc.setTextColor(201, 168, 76);
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text('BAO CAO DOANH THU & KINH DOANH', PW / 2, 11, { align: 'center' });
            
            doc.setTextColor(170, 200, 240);
            doc.setFontSize(9);
            doc.setFont('helvetica', 'normal');
            doc.text('Dong Ho Online  —  He Thong Quan Ly Cua Hang', PW / 2, 18, { align: 'center' });
            doc.setFontSize(7.5);
            doc.text(`Xuat ngay: ${exportTime}`, PW / 2, 24, { align: 'center' });
            
            doc.setDrawColor(...GOLD);
            doc.setLineWidth(1.2);
            doc.line(0, 28, PW, 28);
        } else {
            // Header for page >= 2
            doc.setFillColor(26, 26, 46);
            doc.rect(0, 0, PW, 12, 'F');
            doc.setDrawColor(...GOLD);
            doc.setLineWidth(0.8);
            doc.line(0, 12, PW, 12);
            
            doc.setTextColor(201, 168, 76);
            doc.setFontSize(10);
            doc.setFont('helvetica', 'bold');
            doc.text('BAO CAO DOANH THU  —  CHI TIET DON HANG (Tiep theo)', 14, 8);
        }
        
        // Page footer
        doc.setFillColor(245, 245, 250);
        doc.rect(0, PH - 10, PW, 10, 'F');
        doc.setDrawColor(...GOLD);
        doc.setLineWidth(0.4);
        doc.line(14, PH - 10, PW - 14, PH - 10);
        
        doc.setFontSize(7.5);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(130, 130, 150);
        doc.text('Dong Ho Online  —  Bao cao thong ke tu dong tu he thong Admin', 18, PH - 4.5);
        doc.text(`Trang ${p} / ${totalPages}`, PW / 2, PH - 4.5, { align: 'center' });
        doc.text(`Ngay xuat: ${exportTime}`, PW - 18, PH - 4.5, { align: 'right' });
    }

    doc.save(`BaoCaoDoanhThu_${currentData.date_from}_to_${currentData.date_to}.pdf`);
});

// ══════════════════════════════════════════
// XUẤT EXCEL (.xlsx) — 3 Sheets chuyên nghiệp
// ══════════════════════════════════════════
document.getElementById('btnExportExcel').addEventListener('click', function () {
    if (!currentData) return;
    const XLSX = window.XLSX;

    const totalRev   = parseFloat(currentData.total_revenue) || 0;
    const totalOrd   = parseInt(currentData.total_orders)    || 0;
    const avgRev     = totalOrd > 0 ? Math.round(totalRev / totalOrd) : 0;
    const groupLabel = currentData.group_by === 'day' ? 'Ngày' : currentData.group_by === 'month' ? 'Tháng' : 'Năm';
    const dateFrom   = currentData.date_from.split('-').reverse().join('/');
    const dateTo     = currentData.date_to.split('-').reverse().join('/');
    const exportDate = new Date().toLocaleDateString('vi-VN');
    const orders     = currentData.orders || [];

    const wb = XLSX.utils.book_new();

    // ────────────────────────────────────────
    // SHEET 1: THỐNG KÊ TỔNG HỢP
    // ────────────────────────────────────────
    const s1 = [
        ['BÁO CÁO DOANH THU & KINH DOANH - ĐỒNG HỒ ONLINE', '', '', '', '', ''],
        [`Bộ lọc thời gian: ${dateFrom} đến ${dateTo} | Nhóm theo: ${groupLabel}`, '', '', '', '', ''],
        ['', '', '', '', '', ''],
        ['CHỈ SỐ TỔNG QUAN', '', '', 'THÔNG TIN BÁO CÁO', '', ''],
        ['Tổng doanh thu (đ):', totalRev, '', 'Ngày xuất báo cáo:', exportDate, ''],
        ['Tổng số đơn hoàn thành:', totalOrd, '', 'Nguồn dữ liệu:', 'Hệ thống Admin', ''],
        ['Doanh thu trung bình/đơn (đ):', avgRev, '', 'Số kỳ báo cáo:', currentData.chart_data.length, ''],
        ['', '', '', '', '', ''],
        ['STT', 'Kỳ Báo Cáo', 'Doanh Thu (đ)', 'Số Đơn Hàng', 'TB / Đơn (đ)', 'Tỷ Lệ Doanh Thu (%)'],
    ];

    currentData.chart_data.forEach((r, i) => {
        const rev = parseFloat(r.total_revenue) || 0;
        const ord = parseInt(r.total_orders)    || 0;
        const avg = ord > 0 ? Math.round(rev / ord) : 0;
        const pct = totalRev > 0 ? parseFloat(((rev / totalRev) * 100).toFixed(2)) : 0;
        s1.push([i + 1, r.period_label, rev, ord, avg, pct]);
    });
    s1.push(['', '', '', '', '', '']);
    s1.push(['', 'TỔNG CỘNG', totalRev, totalOrd, avgRev, 100]);

    const ws1 = XLSX.utils.aoa_to_sheet(s1);
    ws1['!cols'] = [{ wch: 8 }, { wch: 18 }, { wch: 22 }, { wch: 14 }, { wch: 22 }, { wch: 22 }];
    ws1['!merges'] = [
        { s: { r: 0, c: 0 }, e: { r: 0, c: 5 } },
        { s: { r: 1, c: 0 }, e: { r: 1, c: 5 } },
        { s: { r: 3, c: 0 }, e: { r: 3, c: 1 } },
        { s: { r: 3, c: 3 }, e: { r: 3, c: 4 } },
    ];
    XLSX.utils.book_append_sheet(wb, ws1, 'Thống Kê Tổng Hợp');

    // ────────────────────────────────────────
    // SHEET 2: CHI TIẾT ĐƠN HÀNG
    // ────────────────────────────────────────
    const s2 = [
        ['DANH SÁCH ĐƠN HÀNG HOÀN THÀNH - ĐỒNG HỒ ONLINE', '', '', '', '', ''],
        [`Thời gian lọc: ${dateFrom} đến ${dateTo} | Ngày xuất: ${exportDate}`, '', '', '', '', ''],
        ['', '', '', '', '', ''],
        ['STT', 'Mã Đơn Hàng', 'Khách Hàng', 'Email', 'Tổng Tiền (đ)', 'Thời Gian Đặt'],
    ];

    orders.forEach((o, i) => {
        s2.push([i + 1, o.order_code, o.customer, o.email, parseFloat(o.total), o.created_at]);
    });

    s2.push(['', '', '', '', '', '']);
    s2.push(['', 'TỔNG CỘNG', `${orders.length} đơn hàng`, '', totalRev, '']);

    const ws2 = XLSX.utils.aoa_to_sheet(s2);
    ws2['!cols'] = [{ wch: 8 }, { wch: 22 }, { wch: 26 }, { wch: 32 }, { wch: 22 }, { wch: 22 }];
    ws2['!merges'] = [
        { s: { r: 0, c: 0 }, e: { r: 0, c: 5 } },
        { s: { r: 1, c: 0 }, e: { r: 1, c: 5 } },
        { s: { r: s2.length - 1, c: 1 }, e: { r: s2.length - 1, c: 2 } },
    ];
    XLSX.utils.book_append_sheet(wb, ws2, 'Chi Tiết Đơn Hàng');

    // ────────────────────────────────────────
    // SHEET 3: CHỈ SỐ PHÂN TÍCH
    // ────────────────────────────────────────
    const allTotals = orders.map(o => parseFloat(o.total));
    const maxVal    = allTotals.length ? Math.max(...allTotals) : 0;
    const minVal    = allTotals.length ? Math.min(...allTotals) : 0;
    const topPeriod = currentData.chart_data.length
        ? currentData.chart_data.reduce((a, b) => parseFloat(a.total_revenue) > parseFloat(b.total_revenue) ? a : b)
        : { period_label: 'N/A', total_revenue: 0 };

    const s3 = [
        ['CHỈ SỐ PHÂN TÍCH DOANH THU & HIỆU SUẤT', ''],
        [`Thời gian phân tích: ${dateFrom} - ${dateTo}`, ''],
        ['', ''],
        ['CHỈ SỐ PHÂN TÍCH', 'GIÁ TRỊ'],
        ['Khoảng thời gian phân tích', `${dateFrom} - ${dateTo}`],
        ['Kiểu nhóm báo cáo', groupLabel],
        ['Số kỳ có doanh thu', currentData.chart_data.length],
        ['Kỳ có doanh thu cao nhất', topPeriod.period_label],
        ['Doanh thu kỳ cao nhất (đ)', parseFloat(topPeriod.total_revenue)],
        ['', ''],
        ['Tổng doanh thu thực tế (đ)', totalRev],
        ['Tổng số đơn hoàn thành', totalOrd],
        ['Doanh thu trung bình mỗi đơn (đ)', avgRev],
        ['', ''],
        ['Đơn hàng có giá trị cao nhất (đ)', maxVal],
        ['Đơn hàng có giá trị thấp nhất (đ)', minVal],
        ['', ''],
        ['Ngày xuất báo cáo phân tích', exportDate],
        ['Trạng thái đơn hàng tổng hợp', 'Chỉ bao gồm đơn hàng trạng thái "Đã giao"'],
    ];

    const ws3 = XLSX.utils.aoa_to_sheet(s3);
    ws3['!cols'] = [{ wch: 38 }, { wch: 45 }];
    ws3['!merges'] = [
        { s: { r: 0, c: 0 }, e: { r: 0, c: 1 } },
        { s: { r: 1, c: 0 }, e: { r: 1, c: 1 } },
    ];
    XLSX.utils.book_append_sheet(wb, ws3, 'Phân Tích Chỉ Số');

    XLSX.writeFile(wb, `BaoCaoDoanhThu_${currentData.date_from}_to_${currentData.date_to}.xlsx`);
});

// Auto-run khi trang load (tháng này mặc định)
document.addEventListener('DOMContentLoaded', () => {
    doFilter();
});
</script>
@endpush