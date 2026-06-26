@extends('layouts.customer')
@section('title', 'Chi Tiết Đơn Hàng – ' . $order->order_code)

@push('styles')
<style>
    /* ── Page hero ── */
    .detail-hero {
        background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%);
        border-bottom: 1px solid rgba(201,168,76,0.18);
    }
    .detail-hero h1 {
        font-family: 'Playfair Display', serif;
    }
    .detail-hero .order-code-tag {
        background: rgba(201,168,76,0.12);
        border: 1px solid rgba(201,168,76,0.3);
        color: var(--gold);
    }

    /* ── Icon Background Gradients ── */
    .header-icon-gradient {
        background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(201,168,76,0.03));
        color: var(--gold);
    }
    .info-icon-gradient {
        background: linear-gradient(135deg, rgba(201,168,76,0.1), rgba(201,168,76,0.02));
        color: var(--gold);
    }

    /* ── Timeline horizontal line ── */
    .timeline-wrap::before {
        content: '';
        position: absolute;
        top: 24px;
        left: calc(12.5%);
        right: calc(12.5%);
        height: 2px;
        background: #e9ecef;
        z-index: 0;
    }
    .timeline-circle {
        width: 48px;
        height: 48px;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        z-index: 1;
    }
    .timeline-circle.done {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
    }
    .timeline-circle.current {
        background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
        color: #fff;
        box-shadow: 0 0 0 4px rgba(201,168,76,0.2), 0 4px 16px rgba(26,26,46,0.3);
    }
    .timeline-circle.pending {
        background: #f1ede4;
        color: #b0a89a;
    }
    
    .timeline-connector {
        position: absolute;
        top: 24px;
        left: 50%;
        right: -50%;
        height: 2px;
        z-index: 0;
    }
    .timeline-connector.done { 
        background: linear-gradient(90deg, var(--gold), var(--gold-light)); 
    }
    .timeline-connector.pending { 
        background: #e9ecef; 
    }

    .timeline-label.done { color: var(--gold-dark); }
    .timeline-label.current { color: #1a1a2e; }
    .timeline-label.pending { color: #b0a89a; }

    /* Responsive Timeline */
    @media (max-width: 576px) {
        .timeline-circle { width: 38px; height: 38px; }
        .timeline-wrap::before { top: 19px; }
        .timeline-connector { top: 19px; }
    }
</style>
@endpush

@section('content')

@php
$steps = [
    ['status'=>'pending',   'icon'=>'bi-clock',       'label'=>"Chờ xác\nnhận"],
    ['status'=>'confirmed', 'icon'=>'bi-check2-circle','label'=>"Đã xác\nnhận"],
    ['status'=>'shipping',  'icon'=>'bi-truck',        'label'=>"Đang\ngiao hàng"],
    ['status'=>'delivered', 'icon'=>'bi-house-check',  'label'=>"Đã nhận\nhàng"],
];
$currentIdx = array_search($order->status, array_column($steps, 'status'));
if ($order->status === 'cancelled') $currentIdx = -1;

$payMethod = match($order->payment_method) {
    'cod'   => ['label'=>'COD – Thanh toán khi nhận', 'icon'=>'bi-cash-coin'],
    'vnpay' => ['label'=>'VNPay',                      'icon'=>'bi-credit-card-2-front'],
    default => ['label'=>ucfirst($order->payment_method), 'icon'=>'bi-credit-card'],
};
@endphp

{{-- ── Hero Header ── --}}
<div class="detail-hero py-4 text-white">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.history') }}" class="text-white-50 text-decoration-none">Đơn hàng</a></li>
                <li class="breadcrumb-item active text-white">{{ $order->order_code }}</li>
            </ol>
        </nav>
        <h1 class="fs-4 fw-bold mb-1"><i class="bi bi-receipt me-2" style="color:var(--gold);font-size:1.3rem;vertical-align:middle;"></i>Chi Tiết Đơn Hàng</h1>
        <div class="order-code-tag d-inline-flex align-items-center gap-2 rounded-pill px-3 py-1 fw-semibold small mt-2">
            <i class="bi bi-upc-scan"></i> {{ $order->order_code }}
            &nbsp;·&nbsp;
            <i class="bi bi-calendar3"></i> {{ $order->created_at->format('d/m/Y H:i') }}
        </div>
    </div>
</div>

<div class="bg-light py-5">
<div class="container">

    {{-- ── Status Timeline / Cancelled ── --}}
    @if($order->status !== 'cancelled')
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 d-flex align-items-center gap-2 border-bottom border-light fw-bold text-dark small">
            <div class="header-icon-gradient rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px; height:32px;">
                <i class="bi bi-map"></i>
            </div>
            Trạng Thái Đơn Hàng
        </div>
        <div class="card-body p-4 bg-white">
            <div class="timeline-wrap d-flex align-items-start justify-content-between position-relative py-2">
                @foreach($steps as $i => $step)
                @php
                    $state = $i < $currentIdx ? 'done' : ($i === $currentIdx ? 'current' : 'pending');
                @endphp
                <div class="timeline-step d-flex flex-column align-items-center gap-2 position-relative z-1 flex-fill">
                    {{-- Connector (before each step except first) --}}
                    @if(!$loop->first)
                    <div class="timeline-connector {{ $i <= $currentIdx ? 'done' : 'pending' }}"></div>
                    @endif

                    <div class="timeline-circle rounded-circle d-flex align-items-center justify-content-center {{ $state }}">
                        <i class="bi {{ $step['icon'] }}"></i>
                    </div>
                    <span class="timeline-label small fw-semibold text-center {{ $state }}" style="white-space:pre-line;">{{ $step['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-danger d-flex align-items-center gap-3 p-4 mb-4 rounded-4 border-danger-subtle shadow-sm bg-white">
        <div class="bg-danger text-white rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px; height:40px; font-size:1.2rem;">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <div>
            <h6 class="alert-heading fw-bold text-danger mb-1">Đơn hàng đã bị huỷ</h6>
            <p class="text-muted small mb-0">Đơn hàng này đã được huỷ vào lúc {{ $order->updated_at->format('H:i – d/m/Y') }}</p>
        </div>
    </div>
    @endif

    <div class="row g-4">
        {{-- ── LEFT: Products + Totals ── --}}
        <div class="col-lg-8">

            {{-- Product list --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 d-flex align-items-center gap-2 border-bottom border-light fw-bold text-dark small">
                    <div class="header-icon-gradient rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px; height:32px;">
                        <i class="bi bi-bag"></i>
                    </div>
                    Sản Phẩm Đã Đặt
                    <span class="badge bg-light text-secondary rounded-pill px-3 py-1 small ms-auto fw-normal">
                        {{ $order->items->count() }} sản phẩm
                    </span>
                </div>
                <div class="card-body p-4 py-2 bg-white">
                    @foreach($order->items as $item)
                    <div class="d-flex align-items-center gap-3 py-3 border-bottom {{ $loop->last ? 'border-0' : 'border-light' }}">
                        <img src="{{ $item->product_image ? asset('storage/'.$item->product_image) : asset('images/no-image.png') }}"
                             class="rounded-3 border object-fit-cover flex-shrink-0" style="width:68px; height:68px;" alt="{{ $item->product_name }}">
                        <div class="flex-grow-1 min-w-0">
                            <h6 class="fw-semibold text-dark mb-1 small text-truncate" style="line-height:1.4;">{{ $item->product_name }}</h6>
                            <span class="text-muted small">
                                <i class="bi bi-tag me-1"></i>{{ number_format($item->price) }}đ
                                &nbsp;×&nbsp;{{ $item->quantity }}
                            </span>
                        </div>
                        <div class="fw-bold text-dark flex-shrink-0 text-end small">{{ number_format($item->subtotal) }}đ</div>
                    </div>
                    @endforeach
                </div>

                {{-- Price Summary --}}
                <div class="px-4 pb-4 bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center py-2 small">
                        <span class="text-muted">Tạm tính</span>
                        <span class="fw-semibold text-dark">{{ number_format($order->subtotal) }}đ</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="d-flex justify-content-between align-items-center py-2 small text-success">
                        <span class="text-success fw-medium"><i class="bi bi-percent me-1"></i>Giảm giá / Voucher</span>
                        <span class="fw-bold">−{{ number_format($order->discount) }}đ</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center py-2 small">
                        <span class="text-muted"><i class="bi bi-truck me-1"></i>Phí vận chuyển</span>
                        <span class="fw-semibold text-success">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-3 border-top border-2 mt-2">
                        <span class="fw-bold text-dark fs-6">Tổng thanh toán</span>
                        <span class="fs-4 fw-bold text-danger">{{ number_format($order->total) }}đ</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── RIGHT: Address + Payment ── --}}
        <div class="col-lg-4">

            {{-- Delivery address --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 d-flex align-items-center gap-2 border-bottom border-light fw-bold text-dark small">
                    <div class="header-icon-gradient rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px; height:32px;">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    Địa Chỉ Giao Hàng
                </div>
                <div class="card-body p-4 py-2 bg-white">
                    <div class="d-flex align-items-start gap-3 py-3 border-bottom border-light">
                        <div class="info-icon-gradient rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width:32px; height:32px;">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <div class="text-muted text-uppercase fw-semibold" style="font-size:0.7rem; letter-spacing:0.5px;">Người nhận</div>
                            <div class="text-dark fw-bold small mt-1">{{ $order->receiver_name }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 py-3 border-bottom border-light">
                        <div class="info-icon-gradient rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width:32px; height:32px;">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div>
                            <div class="text-muted text-uppercase fw-semibold" style="font-size:0.7rem; letter-spacing:0.5px;">Số điện thoại</div>
                            <div class="text-dark fw-bold small mt-1">{{ $order->receiver_phone }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 py-3 border-0">
                        <div class="info-icon-gradient rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width:32px; height:32px;">
                            <i class="bi bi-map-fill"></i>
                        </div>
                        <div>
                            <div class="text-muted text-uppercase fw-semibold" style="font-size:0.7rem; letter-spacing:0.5px;">Địa chỉ nhận hàng</div>
                            <div class="text-dark fw-semibold small mt-1" style="line-height:1.5;">{{ $order->receiver_address }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment info --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 d-flex align-items-center gap-2 border-bottom border-light fw-bold text-dark small">
                    <div class="header-icon-gradient rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px; height:32px;">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    Thông Tin Thanh Toán
                </div>
                <div class="card-body p-4 py-2 bg-white">
                    <div class="d-flex align-items-start gap-3 py-3 border-bottom border-light">
                        <div class="info-icon-gradient rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width:32px; height:32px;">
                            <i class="{{ $payMethod['icon'] }}"></i>
                        </div>
                        <div>
                            <div class="text-muted text-uppercase fw-semibold" style="font-size:0.7rem; letter-spacing:0.5px;">Phương thức thanh toán</div>
                            <div class="text-dark fw-bold small mt-1">{{ $payMethod['label'] }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 py-3 border-bottom {{ $order->note ? 'border-light' : 'border-0' }}">
                        <div class="info-icon-gradient rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width:32px; height:32px;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <div class="text-muted text-uppercase fw-semibold" style="font-size:0.7rem; letter-spacing:0.5px;">Trạng thái giao dịch</div>
                            <div class="mt-2">
                                @if($order->payment_status === 'paid')
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold border border-success-subtle small d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-check-circle-fill"></i> Đã thanh toán
                                </span>
                                @else
                                <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill fw-semibold border border-warning-subtle small d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-clock"></i> Chưa thanh toán
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($order->note)
                    <div class="d-flex align-items-start gap-3 py-3 border-0">
                        <div class="info-icon-gradient rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width:32px; height:32px;">
                            <i class="bi bi-chat-text"></i>
                        </div>
                        <div>
                            <div class="text-muted text-uppercase fw-semibold" style="font-size:0.7rem; letter-spacing:0.5px;">Ghi chú giao hàng</div>
                            <div class="text-muted small mt-1" style="font-weight:400; line-height:1.4;">{{ $order->note }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- ── Actions ── --}}
    <div class="d-flex gap-3 align-items-center flex-wrap mt-3">
        <a href="{{ route('orders.history') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 fw-semibold px-4 py-2.5 rounded-3 shadow-sm">
            <i class="bi bi-arrow-left"></i> Quay Lại Danh Sách
        </a>
        <a href="{{ route('orders.invoice', $order->order_code) }}" target="_blank" class="btn btn-success d-inline-flex align-items-center gap-2 fw-semibold px-4 py-2.5 rounded-3 shadow-sm border-0">
            <i class="bi bi-file-earmark-pdf"></i> Tải Hóa Đơn PDF
        </a>
        @if($order->status === 'pending' && $order->payment_status === 'pending' && in_array($order->payment_method, ['vnpay', 'qr']))
            @if($order->payment_method === 'vnpay')
            <form method="POST" action="{{ route('orders.repay', $order->order_code) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 fw-semibold px-4 py-2.5 rounded-3 shadow-sm border-0">
                    <i class="bi bi-credit-card-2-front"></i> Thanh Toán Lại (VNPay)
                </button>
            </form>
            @elseif($order->payment_method === 'qr')
            <a href="{{ route('orders.qr', $order->order_code) }}" class="btn btn-primary d-inline-flex align-items-center gap-2 fw-semibold px-4 py-2.5 rounded-3 shadow-sm border-0">
                <i class="bi bi-qr-code-scan"></i> Xem mã QR Thanh Toán
            </a>
            @endif
            <form method="POST" action="{{ route('orders.change-payment', $order->order_code) }}" class="d-inline"
                  onsubmit="return confirm('Bạn có chắc muốn đổi phương thức thanh toán sang COD?')">
                @csrf
                <button type="submit" class="btn btn-warning d-inline-flex align-items-center gap-2 fw-semibold px-4 py-2.5 rounded-3 shadow-sm border-0 text-dark">
                    <i class="bi bi-cash-coin"></i> Đổi Sang COD
                </button>
            </form>
        @endif
        @if(in_array($order->status, ['pending', 'confirmed']))
        <form method="POST" action="{{ route('orders.cancel', $order->id) }}"
              onsubmit="return confirm('Bạn có chắc chắn muốn huỷ đơn hàng {{ $order->order_code }}?')">
            @csrf
            <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center gap-2 fw-semibold px-4 py-2.5 rounded-3 shadow-sm">
                <i class="bi bi-x-circle"></i> Huỷ Đơn Hàng
            </button>
        </form>
        @endif
    </div>

</div>
</div>
@endsection