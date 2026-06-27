@extends('layouts.customer')
@section('title', 'Đặt Hàng')

@push('styles')
<style>
    /* ── Hero Gradient ── */
    .checkout-hero {
        background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%);
        border-bottom: 1px solid rgba(201,168,76,0.18);
    }
    .checkout-hero h1 {
        font-family: 'Playfair Display', serif;
    }
    
    /* ── Premium Card ── */
    .checkout-card-header {
        background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
    }
    
    .payment-box-active {
        border-color: var(--gold) !important;
        background-color: rgba(201,168,76,0.04) !important;
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

{{-- ── Hero ── --}}
<div class="checkout-hero py-4 mb-0">
    <div class="container py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="font-size: 0.79rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--gold);"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none" style="color: var(--gold);">Giỏ hàng</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">Đặt hàng</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-white mb-1">
            <i class="bi bi-bag-check me-2" style="color: var(--gold); font-size: 1.3rem; vertical-align: middle;"></i>Thủ Tục Đặt Hàng
        </h1>
        <p class="text-white-50 mb-0 small">Vui lòng điền thông tin và lựa chọn phương thức thanh toán để hoàn tất đơn hàng</p>
    </div>
</div>

<div class="py-5" style="background: #f5f4f0;">
    <div class="container">
        <form method="POST" action="{{ route('orders.place') }}">
            @csrf
            @if(request()->has('selected_items'))
                <input type="hidden" name="selected_items" value="{{ request('selected_items') }}">
            @endif
            <div class="row g-4">
                {{-- THÔNG TIN GIAO HÀNG & PHƯƠNG THỨC THANH TOÁN --}}
                <div class="col-lg-7">
                    {{-- THÔNG TIN GIAO HÀNG --}}
                    <div class="bg-white rounded-4 border border-light-subtle shadow-sm overflow-hidden mb-4">
                        <div class="checkout-card-header p-4 text-white fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt-fill" style="color: var(--gold);"></i> Thông Tin Giao Hàng
                        </div>
                        <div class="p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small">Họ tên người nhận *</label>
                                <input type="text" name="receiver_name"
                                       class="form-control rounded-3 p-2.5 @error('receiver_name') is-invalid @enderror"
                                       value="{{ old('receiver_name', $user->name) }}" placeholder="Nhập tên người nhận">
                                @error('receiver_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small">Số điện thoại *</label>
                                <input type="text" name="receiver_phone"
                                       class="form-control rounded-3 p-2.5 @error('receiver_phone') is-invalid @enderror"
                                       value="{{ old('receiver_phone', $user->phone) }}" placeholder="Nhập số điện thoại giao hàng">
                                @error('receiver_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small">Địa chỉ giao hàng *</label>
                                <textarea name="receiver_address" rows="3"
                                          class="form-control rounded-3 p-2.5 @error('receiver_address') is-invalid @enderror"
                                          placeholder="Nhập địa chỉ giao hàng chi tiết (Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố)">{{ old('receiver_address', $user->address) }}</textarea>
                                @error('receiver_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold text-dark small">Ghi chú đơn hàng</label>
                                <textarea name="note" rows="2" class="form-control rounded-3 p-2.5"
                                          placeholder="Ghi chú thêm cho shipper hoặc cửa hàng (tuỳ chọn)">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- PHƯƠNG THỨC THANH TOÁN --}}
                    <div class="bg-white rounded-4 border border-light-subtle shadow-sm overflow-hidden">
                        <div class="checkout-card-header p-4 text-white fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-credit-card-2-front-fill" style="color: var(--gold);"></i> Phương Thức Thanh Toán
                        </div>
                        <div class="p-4">

                            {{-- COD --}}
                            <div class="form-check p-3 border rounded-3 mb-3 cursor-pointer" id="codBox" style="transition: all 0.2s ease;">
                                <input class="form-check-input ms-0 me-3" type="radio"
                                    name="payment_method" id="cod" value="cod"
                                    {{ old('payment_method','cod') === 'cod' ? 'checked' : '' }}
                                    onchange="switchPayment('cod')">
                                <label class="form-check-label w-100 cursor-pointer" for="cod">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-cash-coin text-success fs-3"></i>
                                        <div>
                                            <strong class="text-dark small">Thanh toán khi nhận hàng (COD)</strong>
                                            <p class="text-secondary small mb-0" style="font-size: 0.78rem;">
                                                Trả tiền mặt khi nhận hàng — An toàn, tiện lợi
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            {{-- QR Code --}}
                            <div class="form-check p-3 border rounded-3 mb-3 cursor-pointer" id="qrBox" style="transition: all 0.2s ease;">
                                <input class="form-check-input ms-0 me-3" type="radio"
                                    name="payment_method" id="qr" value="qr"
                                    {{ old('payment_method') === 'qr' ? 'checked' : '' }}
                                    onchange="switchPayment('qr')">
                                <label class="form-check-label w-100 cursor-pointer" for="qr">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-qr-code text-primary fs-3"></i>
                                        <div>
                                            <strong class="text-dark small">Chuyển khoản Ngân hàng (VietQR)</strong>
                                            <p class="text-secondary small mb-0" style="font-size: 0.78rem;">
                                                Quét mã QR để chuyển khoản nhanh 24/7 — Nhận hàng nhanh chóng
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            {{-- VNPAY --}}
                            <div class="form-check p-3 border rounded-3 cursor-pointer" id="vnpayBox" style="transition: all 0.2s ease;">
                                <input class="form-check-input ms-0 me-3" type="radio"
                                    name="payment_method" id="vnpay" value="vnpay"
                                    {{ old('payment_method') === 'vnpay' ? 'checked' : '' }}
                                    onchange="switchPayment('vnpay')">
                                <label class="form-check-label w-100 cursor-pointer" for="vnpay">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://sandbox.vnpayment.vn/apis/assets/images/icon/vnpay_logo.png"
                                            height="28" alt="VNPay">
                                        <div>
                                            <strong class="text-dark small">Thanh toán qua cổng VNPay</strong>
                                            <p class="text-secondary small mb-0" style="font-size: 0.78rem;">
                                                ATM nội địa · Internet Banking · QR Code · Ví điện tử VNPay
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            {{-- Info box VNPay --}}
                            <div id="vnpayInfo" class="mt-3 d-none">
                                <div class="alert alert-info d-flex align-items-start gap-2 mb-0 rounded-3 border-info-subtle">
                                    <i class="bi bi-info-circle-fill flex-shrink-0 mt-1 fs-5 text-info"></i>
                                    <div class="small text-secondary-emphasis">
                                        <strong class="text-info-emphasis">Thông tin tài khoản VNPay:</strong><br>
                                        Ngân hàng: <strong>BIDV</strong><br>
                                        Số tài khoản/thẻ: <strong>2601657079</strong><br>
                                        Tên chủ tài khoản/thẻ: <strong>NGUYEN TOAN THANG</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TÓM TẮT ĐƠN HÀNG --}}
                <div class="col-lg-5">
                    <div class="bg-white rounded-4 border border-light-subtle shadow-sm overflow-hidden sticky-top" style="top: 24px; z-index: 10;">
                        <div class="checkout-card-header p-4 text-white fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-receipt" style="color: var(--gold);"></i> Đơn Hàng Của Bạn
                        </div>
                        <div class="p-0">
                            <div class="overflow-y-auto" style="max-height: 280px;">
                                @foreach($cart as $item)
                                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                                    <img src="{{ img_url($item['thumbnail']) }}"
                                         width="55" height="55" class="rounded-3 border object-fit-cover">
                                    <div class="flex-grow-1 min-w-0">
                                        <p class="mb-0 small fw-semibold text-dark text-truncate">{{ $item['name'] }}</p>
                                        <small class="text-secondary">Số lượng: {{ $item['quantity'] }}</small>
                                    </div>
                                    <span class="fw-bold text-danger-emphasis small text-nowrap">
                                        {{ number_format($item['price'] * $item['quantity']) }}đ
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            <div class="p-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary small">Tạm tính</span>
                                    <span class="text-dark fw-semibold small">{{ number_format($summary['subtotal']) }}đ</span>
                                </div>
                                @if($summary['discount'] > 0)
                                <div class="d-flex justify-content-between mb-2 text-success small">
                                    <span>Mã giảm giá ({{ $coupon['code'] }})</span>
                                    <span>-{{ number_format($summary['discount']) }}đ</span>
                                </div>
                                @endif
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary small">Phí vận chuyển</span>
                                    <span class="text-success small fw-semibold">Miễn phí</span>
                                </div>
                                <hr class="my-3 border-light-subtle">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="text-dark fw-bold">Tổng thanh toán</span>
                                    <span class="text-danger fw-extrabold fs-4">{{ number_format($summary['total']) }}đ</span>
                                </div>
                                <button type="submit" class="btn btn-gold-premium w-100 py-3 rounded-3 fs-6 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                    <i class="bi bi-shield-check fs-5"></i> Xác Nhận Đặt Hàng & Thanh Toán
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function switchPayment(method) {
    // Reset tất cả
    ['cod','vnpay','qr'].forEach(m => {
        const box = document.getElementById(m + 'Box');
        if (box) {
            box.classList.remove('payment-box-active');
        }
        const info = document.getElementById(m + 'Info');
        if (info) {
            info.classList.add('d-none');
        }
    });

    const boxes = {
        cod:   document.getElementById('codBox'),
        vnpay: document.getElementById('vnpayBox'),
        qr:    document.getElementById('qrBox'),
    };
    const infos = {
        vnpay: document.getElementById('vnpayInfo'),
    };

    // Active box được chọn
    if (boxes[method]) {
        boxes[method].classList.add('payment-box-active');
    }

    // Hiện info tương ứng
    if (infos[method]) {
        infos[method].classList.remove('d-none');
    }
}
// Khởi tạo
switchPayment('{{ old("payment_method", "cod") }}');
</script>
@endpush
@endsection