@extends('layouts.customer')
@section('title', 'Đơn Hàng Của Tôi')

@push('styles')
<style>
    /* ── Page header ── */
    .page-hero {
        background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%);
        border-bottom: 1px solid rgba(201,168,76,0.18);
    }
    .page-hero h1 {
        font-family: 'Playfair Display', serif;
    }
    .page-hero .breadcrumb-item a { color: var(--gold); }
    .page-hero .breadcrumb-item+.breadcrumb-item::before { color: rgba(201,168,76,0.35); }
 
    /* ── Status filter tabs ── */
    .filter-tab {
        border: 1.5px solid #ebe9e0;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50px;
    }
    .filter-tab:hover, .filter-tab.active {
        border-color: var(--gold);
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f !important;
        box-shadow: 0 4px 14px rgba(201,168,76,0.3);
    }
 
    /* ── Order card ── */
    .order-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.06) !important;
    }
    .order-card:hover {
        box-shadow: 0 12px 30px rgba(0,0,0,0.08) !important;
        transform: translateY(-3px);
        border-color: rgba(201,168,76,0.2) !important;
    }
 
    /* More items chip */
    .more-items {
        border: 1px dashed rgba(201,168,76,0.4);
        background: rgba(201,168,76,0.03);
    }
 
    /* Action buttons */
    .btn-view-detail {
        background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
        transition: all 0.2s;
    }
    .btn-view-detail:hover {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(201,168,76,0.3);
    }
 
    /* ── Empty state ── */
    .empty-icon {
        width: 88px; height: 88px;
        background: linear-gradient(135deg, rgba(201,168,76,0.1), rgba(201,168,76,0.02));
        border: 1px dashed rgba(201,168,76,0.3);
        color: var(--gold);
    }
</style>
@endpush

@section('content')

{{-- ── Hero Header ── --}}
<div class="page-hero py-4 border-bottom mb-4 text-white">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
                <li class="breadcrumb-item active text-white-50">Đơn hàng của tôi</li>
            </ol>
        </nav>
        <h1 class="text-white fw-bold mb-1"><i class="bi bi-bag-heart me-2" style="color:var(--gold);font-size:1.4rem;vertical-align:middle;"></i>Đơn Hàng Của Tôi</h1>
        <p class="subtitle text-white-50 small mb-0">Quản lý và theo dõi tất cả đơn hàng sở hữu của bạn</p>
    </div>
</div>

@php
$statusMap = [
    'pending'   => ['label'=>'Chờ xác nhận', 'icon'=>'bi-clock',       'cls'=>'bg-warning-subtle text-warning border-warning-subtle'],
    'confirmed' => ['label'=>'Đã xác nhận',  'icon'=>'bi-check2-circle','cls'=>'bg-info-subtle text-info border-info-subtle'],
    'shipping'  => ['label'=>'Đang giao',     'icon'=>'bi-truck',        'cls'=>'bg-primary-subtle text-primary border-primary-subtle'],
    'delivered' => ['label'=>'Đã giao',       'icon'=>'bi-house-check',  'cls'=>'bg-success-subtle text-success border-success-subtle'],
    'cancelled' => ['label'=>'Đã huỷ',        'icon'=>'bi-x-circle',     'cls'=>'bg-danger-subtle text-danger border-danger-subtle'],
];
@endphp

<div class="orders-page bg-light py-4" style="min-height: 60vh;">
    <div class="container">

        {{-- ── Stats ── --}}
        @if($orders->total() > 0)
        <div class="row g-3 stats-row mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-chip d-flex align-items-center gap-3 bg-white border-0 shadow-sm rounded-4 p-3">
                    <div class="stat-chip-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 42px; height: 42px; background:rgba(201,168,76,0.12); color:var(--gold);">
                        <i class="bi bi-bag fs-5"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark lh-1">{{ $orders->total() }}</div>
                        <div class="small text-muted mt-1">Tổng đơn hàng</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-chip d-flex align-items-center gap-3 bg-white border-0 shadow-sm rounded-4 p-3">
                    <div class="stat-chip-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 42px; height: 42px; background:rgba(25,135,84,0.12); color:#198754;">
                        <i class="bi bi-house-check fs-5"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark lh-1">{{ $orders->getCollection()->where('status','delivered')->count() }}</div>
                        <div class="small text-muted mt-1">Đã giao hàng</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-chip d-flex align-items-center gap-3 bg-white border-0 shadow-sm rounded-4 p-3">
                    <div class="stat-chip-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 42px; height: 42px; background:rgba(13,110,253,0.12); color:#0d6efd;">
                        <i class="bi bi-truck fs-5"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark lh-1">{{ $orders->getCollection()->whereIn('status',['shipping','confirmed','pending'])->count() }}</div>
                        <div class="small text-muted mt-1">Đang xử lý</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-chip d-flex align-items-center gap-3 bg-white border-0 shadow-sm rounded-4 p-3">
                    <div class="stat-chip-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 42px; height: 42px; background:rgba(220,53,69,0.12); color:#dc3545;">
                        <i class="bi bi-x-circle fs-5"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark lh-1">{{ $orders->getCollection()->where('status','cancelled')->count() }}</div>
                        <div class="small text-muted mt-1">Đã hủy bỏ</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Filter Tabs ── --}}
        <div class="filter-tabs d-flex gap-2 flex-wrap mb-4">
            <a href="{{ route('orders.history') }}" class="filter-tab px-3 py-2 bg-white text-secondary small fw-medium text-decoration-none border {{ !$status ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap me-1"></i>Tất cả
            </a>
            @foreach($statusMap as $key => $s)
            <a href="{{ route('orders.history', ['status' => $key]) }}"
               class="filter-tab px-3 py-2 bg-white text-secondary small fw-medium text-decoration-none border {{ $status === $key ? 'active' : '' }}">
                <i class="bi {{ $s['icon'] }} me-1"></i>{{ $s['label'] }}
            </a>
            @endforeach
        </div>

        {{-- ── Order List ── --}}
        @forelse($orders as $order)
        @php $sm = $statusMap[$order->status] ?? ['label'=>$order->status,'icon'=>'bi-circle','cls'=>'bg-secondary-subtle text-secondary']; @endphp

        <div class="order-card card bg-white rounded-4 overflow-hidden mb-4 shadow-sm border-0">
            {{-- Header --}}
            <div class="order-card-header d-flex align-items-center justify-content-between p-3 bg-light border-bottom gap-2 flex-wrap">
                <div>
                    <div class="order-code fw-bold small text-dark">
                        <i class="bi bi-receipt me-1" style="color:var(--gold);"></i>
                        {{ $order->order_code }}
                    </div>
                    <div class="order-date text-muted small mt-1">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ $order->created_at->format('d/m/Y') }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-clock me-1"></i>
                        {{ $order->created_at->format('H:i') }}
                    </div>
                </div>
                <span class="status-badge badge border d-inline-flex align-items-center gap-1 px-3 py-2 rounded-pill small fw-semibold {{ $sm['cls'] }}">
                    <i class="bi {{ $sm['icon'] }}"></i>
                    {{ $sm['label'] }}
                </span>
            </div>

            {{-- Products --}}
            <div class="order-card-body p-3 p-md-4 bg-white">
                @foreach($order->items->take(2) as $item)
                <div class="product-row d-flex align-items-center gap-3 py-3 border-bottom {{ $loop->last && $order->items->count() <= 2 ? 'border-0' : '' }}">
                    <img src="{{ $item->product_image ? asset('storage/'.$item->product_image) : asset('images/no-image.png') }}"
                         class="rounded-3 flex-shrink-0 border object-fit-cover" style="width: 60px; height: 60px;" alt="{{ $item->product_name }}">
                    <div class="flex-grow-1 min-w-0">
                        <p class="product-name fw-bold small text-dark mb-1 text-truncate">{{ $item->product_name }}</p>
                        <span class="product-meta text-muted small">
                            <i class="bi bi-tag me-1"></i>{{ number_format($item->price) }}đ
                            &nbsp;×&nbsp;{{ $item->quantity }}
                        </span>
                    </div>
                    <div class="fw-bold text-dark flex-shrink-0 text-end small">
                        {{ number_format($item->price * $item->quantity) }}đ
                    </div>
                </div>
                @endforeach

                @if($order->items->count() > 2)
                <div class="more-items d-inline-flex align-items-center gap-2 text-muted small px-3 py-1.5 rounded-pill mt-3">
                    <i class="bi bi-plus-circle-fill" style="color:var(--gold);"></i>
                    Xem thêm {{ $order->items->count() - 2 }} sản phẩm khác trong đơn
                </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="order-card-footer d-flex align-items-center justify-content-between p-3 bg-white border-top flex-wrap gap-2">
                <div>
                    <div class="total-label text-muted small">Tổng giá trị đơn hàng</div>
                    <div class="total-amount fs-5 fw-bold text-danger">
                        <span class="d-inline-block rounded-circle bg-warning me-1" style="width: 6px; height: 6px;"></span>{{ number_format($order->total) }}đ
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    @if(in_array($order->status, ['pending', 'confirmed']))
                    <form method="POST" action="{{ route('orders.cancel', $order->id) }}"
                          onsubmit="return confirm('Bạn có chắc muốn huỷ đơn hàng {{ $order->order_code }}?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm fw-bold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1">
                            <i class="bi bi-x-circle"></i> Huỷ đơn
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('orders.invoice', $order->order_code) }}" target="_blank" class="btn btn-outline-success btn-sm fw-bold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                    <a href="{{ route('orders.detail', $order->order_code) }}" class="btn btn-dark btn-sm btn-view-detail text-white border-0 fw-bold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-eye"></i> Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-orders text-center py-5 px-3 bg-white rounded-4 shadow-sm border border-light">
            <div class="empty-icon d-flex align-items-center justify-content-center mx-auto mb-4 rounded-circle">
                <i class="bi bi-bag-x fs-2"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2" style="font-family:'Playfair Display',serif;">Chưa Có Đơn Hàng Nào</h5>
            <p class="text-muted small mb-4" style="max-width: 400px; margin: 0 auto;">Bạn chưa đặt mua sản phẩm nào từ hệ thống. Hãy khám phá bộ sưu tập đồng hồ đẳng cấp của chúng tôi ngay hôm nay!</p>
            <a href="{{ route('products.index') }}" class="btn btn-dark btn-view-detail border-0 text-white px-5 py-2.5 rounded-3 mt-3">
                <i class="bi bi-bag-heart me-2"></i> Khám Phá Sản Phẩm Ngay
            </a>
        </div>
        @endforelse

        {{-- ── Pagination ── --}}
        @if($orders->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
        @endif

    </div>
</div>
@endsection