@extends('layouts.customer')
@section('title', 'Đặt Hàng Thành Công')

@push('styles')
<style>
    /* ── Success Check Icon ── */
    .success-icon-box {
        width: 100px;
        height: 100px;
        background: rgba(201,168,76,0.12);
        border: 2px solid rgba(201,168,76,0.3);
        color: var(--gold);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 4px 15px rgba(201,168,76,0.25);
    }
    
    .checkout-card-header {
        background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
    }
    
    .btn-gold-premium {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
        font-weight: 700;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-gold-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(201,168,76,0.4);
        color: #0a0a0f;
    }
</style>
@endpush

@section('content')
<div class="py-5" style="background: #f5f4f0;">
    <div class="container">
        <div class="text-center mb-5">
            <div class="success-icon-box rounded-circle">
                <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: var(--gold);"></i>
            </div>
            <h3 class="fw-bold mt-4 text-dark" style="font-family: 'Playfair Display', serif;">Đặt Hàng Thành Công!</h3>
            <p class="text-secondary small">Cảm ơn bạn đã tin tưởng mua sắm. Chúng tôi đang nhanh chóng xử lý đơn hàng của bạn.</p>
            <div class="badge fs-6 py-2.5 px-4 text-white-50 shadow-sm" style="background: #1a1a2e; border: 1px solid rgba(201,168,76,0.3);">
                Mã đơn hàng: <strong class="text-white" style="color: var(--gold) !important;">{{ $order->order_code }}</strong>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="bg-white rounded-4 border border-light-subtle shadow-sm overflow-hidden mb-4">
                    <div class="checkout-card-header p-4 text-white fw-bold d-flex align-items-center gap-2">
                        <i class="bi bi-receipt-cutoff" style="color: var(--gold);"></i> Chi Tiết Đơn Hàng
                    </div>
                    <div class="p-4 fs-6">
                        <div class="row mb-3">
                            <div class="col-sm-4 text-secondary small">Người nhận hàng</div>
                            <div class="col-sm-8 fw-semibold text-dark">{{ $order->receiver_name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-secondary small">Số điện thoại liên hệ</div>
                            <div class="col-sm-8 text-dark">{{ $order->receiver_phone }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-secondary small">Địa chỉ giao hàng</div>
                            <div class="col-sm-8 text-dark">{{ $order->receiver_address }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-secondary small">Phương thức thanh toán</div>
                            <div class="col-sm-8 text-dark d-flex align-items-center gap-2">
                                @if($order->payment_method === 'vnpay')
                                    <img src="https://sandbox.vnpayment.vn/apis/assets/images/icon/vnpay_logo.png"
                                        height="20" alt="VNPay">
                                    <span class="small">VNPay</span>
                                    @if($order->payment_status === 'paid')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">✓ Đã thanh toán</span>
                                    @endif
                                @elseif($order->payment_method === 'qr')
                                    <i class="bi bi-qr-code text-primary fs-5"></i>
                                    <span class="small">Chuyển khoản QR Code</span>
                                    @if($order->payment_status === 'paid')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">✓ Đã nhận tiền</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-pill small">⏳ Chờ chuyển khoản</span>
                                    @endif
                                @else
                                    <i class="bi bi-cash-coin text-success fs-5"></i>
                                    <span class="small">COD — Trả tiền mặt khi nhận hàng</span>
                                @endif
                            </div>
                        </div>
                        @if($order->vnpay_transaction_id)
                        <div class="row mb-3">
                            <div class="col-sm-4 text-secondary small">Mã giao dịch VNPay</div>
                            <div class="col-sm-8">
                                <code class="bg-light p-1.5 px-2 rounded text-dark font-monospace fs-6" style="border: 1px solid #e9e4d8;">{{ $order->vnpay_transaction_id }}</code>
                            </div>
                        </div>
                        @endif
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm-4 text-secondary small">Tổng thanh toán</div>
                            <div class="col-sm-8 fw-extrabold text-danger fs-4">
                                {{ number_format($order->total) }}đ
                            </div>
                        </div>
                        <hr class="my-4 border-light-subtle">

                        {{-- Trạng thái timeline --}}
                        <div class="d-flex justify-content-between text-center small mt-4">
                            @php
                                $steps = [
                                    ['status'=>'pending',   'icon'=>'bi-clock',          'label'=>'Chờ xác nhận'],
                                    ['status'=>'confirmed', 'icon'=>'bi-check-lg',       'label'=>'Đã xác nhận'],
                                    ['status'=>'shipping',  'icon'=>'bi-truck',           'label'=>'Đang giao'],
                                    ['status'=>'delivered', 'icon'=>'bi-house-check-fill','label'=>'Đã nhận'],
                                ];
                                $currentIdx = array_search($order->status, array_column($steps, 'status'));
                            @endphp
                            @foreach($steps as $i => $step)
                            <div class="d-flex flex-column align-items-center" style="flex: 1;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 shadow-sm"
                                     style="width:38px; height:38px;
                                            background: {{ $i <= $currentIdx ? 'linear-gradient(135deg, var(--gold), var(--gold-dark))' : '#e9ecef' }};
                                            color: {{ $i <= $currentIdx ? '#0a0a0f' : '#aaa' }};
                                            border: 2px solid {{ $i <= $currentIdx ? 'var(--gold)' : '#fff' }};">
                                    <i class="bi {{ $step['icon'] }} fs-6"></i>
                                </div>
                                <span class="small {{ $i <= $currentIdx ? 'fw-bold text-dark' : 'text-muted' }}" style="font-size: 0.78rem;">
                                    {{ $step['label'] }}
                                </span>
                            </div>
                            @if(!$loop->last)
                            <div class="align-self-start" style="flex: 0.5; height: 3px; margin-top: 19px;
                                        background: {{ $i < $currentIdx ? 'var(--gold)' : '#e9ecef' }};"></div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2.5 mt-4 justify-content-center">
                    <a href="{{ route('orders.detail', $order->order_code) }}"
                       class="btn btn-gold-premium rounded-3 py-2.5 px-4 d-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-eye"></i> Xem Chi Tiết
                    </a>
                    <a href="{{ route('orders.invoice', $order->order_code) }}" target="_blank"
                       class="btn btn-success rounded-3 py-2.5 px-4 d-flex align-items-center gap-2 shadow-sm border-0" style="background-color: #198754;">
                        <i class="bi bi-file-earmark-pdf"></i> Tải Hóa Đơn (PDF)
                    </a>
                    <a href="{{ route('orders.history') }}" class="btn btn-outline-dark rounded-3 py-2.5 px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-list-ul"></i> Đơn Hàng Của Tôi
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-3 py-2.5 px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-house"></i> Về Trang Chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection