@extends('layouts.customer')
@section('title', 'Thanh Toán QR — ' . $order->order_code)

@push('styles')
<style>
    /* ── Hero Gradient ── */
    .qr-hero {
        background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%);
        border-bottom: 1px solid rgba(201,168,76,0.18);
    }
    .qr-hero h1 {
        font-family: 'Playfair Display', serif;
    }
    
    .checkout-card-header {
        background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
    }

    .qr-border-gold {
        border: 4px solid var(--gold) !important;
        box-shadow: 0 4px 20px rgba(201,168,76,0.25);
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
    .text-gold {
        color: var(--gold) !important;
    }
</style>
@endpush

@section('content')

{{-- ── Hero ── --}}
<div class="qr-hero py-4 mb-0">
    <div class="container py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="font-size: 0.79rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--gold);"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.history') }}" class="text-decoration-none" style="color: var(--gold);">Đơn hàng</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">Thanh toán QR</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-white mb-1">
            <i class="bi bi-qr-code text-gold me-2" style="vertical-align: middle;"></i>Thanh Toán QR Code
        </h1>
        <p class="text-white-50 mb-0 small">Mã đơn hàng của bạn: <strong class="text-white text-gold font-monospace">{{ $order->order_code }}</strong></p>
    </div>
</div>

<div class="py-5" style="background: #f5f4f0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">

                {{-- QR CARD --}}
                <div class="bg-white rounded-4 border border-light-subtle shadow-sm overflow-hidden text-center mb-4">
                    <div class="checkout-card-header p-4 text-white fw-bold d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-wallet2" style="color: var(--gold);"></i> Thông Tin Chuyển Khoản
                    </div>
                    <div class="p-4">

                        {{-- Số tiền nổi bật --}}
                        <div class="mb-4 p-3 rounded-3" style="background: rgba(220,53,69,0.06); border: 1px dashed rgba(220,53,69,0.25);">
                            <p class="text-secondary small mb-1">Số tiền cần thanh toán</p>
                            <h2 class="fw-extrabold text-danger mb-0">
                                {{ number_format($order->total) }}đ
                            </h2>
                        </div>

                        {{-- QR Image từ VietQR API --}}
                        <div class="position-relative d-inline-block mb-4 p-2 bg-light rounded-3" style="border: 1px solid #e9e4d8;">
                            <div id="qrLoading" class="position-absolute top-50 start-50 translate-middle d-flex flex-column align-items-center">
                                <div class="spinner-border text-warning mb-2" style="width:2rem; height:2rem;"></div>
                                <small class="text-secondary small">Đang tạo mã QR...</small>
                            </div>
                            <img id="qrImage"
                                 src="{{ $qrUrl }}"
                                 alt="QR Thanh Toán"
                                 class="qr-border-gold"
                                 style="width:230px; height:230px; border-radius:12px; display:none;"
                                 onload="qrLoaded()"
                                 onerror="qrError()">
                        </div>

                        {{-- Thông tin ngân hàng --}}
                        <div class="p-3.5 rounded-3 text-start mb-4 border border-light-subtle" style="background: #faf9f5; font-size: 0.88rem;">
                            <div class="d-flex justify-content-between align-items-center mb-2.5">
                                <span class="text-secondary">Ngân hàng thụ hưởng</span>
                                <strong class="text-dark">{{ $bankInfo['bank_name'] }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2.5">
                                <span class="text-secondary">Số tài khoản</span>
                                <div class="d-flex align-items-center gap-1">
                                    <strong class="text-dark font-monospace" id="accountNo">{{ $bankInfo['account_no'] }}</strong>
                                    <button class="btn btn-link p-0 text-decoration-none" onclick="copyText('{{ $bankInfo['account_no'] }}')" title="Sao chép số tài khoản">
                                        <i class="bi bi-copy text-gold fs-6 ms-1"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2.5">
                                <span class="text-secondary">Tên chủ tài khoản</span>
                                <strong class="text-dark text-uppercase">{{ $bankInfo['account_name'] }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2.5">
                                <span class="text-secondary">Số tiền chuyển khoản</span>
                                <div class="d-flex align-items-center gap-1">
                                    <strong class="text-danger-emphasis font-monospace">{{ number_format($order->total) }}đ</strong>
                                    <button class="btn btn-link p-0 text-decoration-none" onclick="copyText('{{ $order->total }}')" title="Sao chép số tiền">
                                        <i class="bi bi-copy text-gold fs-6 ms-1"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary">Nội dung chuyển khoản</span>
                                <div class="d-flex align-items-center gap-1">
                                    <strong class="text-primary font-monospace" id="transferContent">{{ $order->order_code }}</strong>
                                    <button class="btn btn-link p-0 text-decoration-none" onclick="copyText('{{ $order->order_code }}')" title="Sao chép nội dung">
                                        <i class="bi bi-copy text-gold fs-6 ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Hướng dẫn --}}
                        <div class="d-flex align-items-start gap-2.5 text-start p-3.5 rounded-3 mb-4 border border-info-subtle" style="background: rgba(13,110,253,0.04);">
                            <i class="bi bi-info-circle-fill text-primary mt-0.5 flex-shrink-0 fs-5"></i>
                            <div class="small text-secondary-emphasis">
                                <strong class="text-primary-emphasis">Hướng dẫn thực hiện thanh toán:</strong>
                                <ol class="mb-0 ps-3 mt-1.5" style="line-height: 1.55;">
                                    <li>Mở ứng dụng ngân hàng di động trên điện thoại của bạn.</li>
                                    <li>Chọn tính năng <strong>Quét mã QR (QR Pay)</strong> và quét hình ảnh trên.</li>
                                    <li>Kiểm tra thông tin tài khoản đích và số tiền <strong class="text-danger">{{ number_format($order->total) }}đ</strong>.</li>
                                    <li>Hoàn tất giao dịch. Hệ thống sẽ tự xác nhận sau vài giây!</li>
                                </ol>
                            </div>
                        </div>

                        {{-- TIMER đếm ngược 15 phút --}}
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                            <i class="bi bi-clock-history text-warning fs-5"></i>
                            <span class="text-secondary small">
                                Giao dịch QR sẽ hết hạn sau:
                                <strong class="text-warning font-monospace fs-6" id="timer">15:00</strong>
                            </span>
                        </div>

                        {{-- Trạng thái thanh toán --}}
                        <div id="paymentStatus" class="alert alert-warning d-flex align-items-center justify-content-center gap-2 mb-0 rounded-3 border-warning-subtle py-2.5">
                            <div class="spinner-border spinner-border-sm text-warning flex-shrink-0"></div>
                            <span class="small text-warning-emphasis fw-medium">Hệ thống đang chờ bạn quét mã và chuyển tiền...</span>
                        </div>

                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="d-flex gap-2.5 justify-content-center mb-4">
                    <a href="{{ route('orders.detail', $order->order_code) }}"
                       class="btn btn-gold-premium rounded-3 py-2.5 px-4 d-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-eye"></i> Xem Đơn Hàng
                    </a>
                    <a href="{{ route('orders.history') }}"
                       class="btn btn-outline-dark bg-white rounded-3 py-2.5 px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-list-ul"></i> Lịch Sử Mua Hàng
                    </a>
                </div>

                {{-- Lưu ý --}}
                <div class="text-center bg-light p-3 rounded-3 border">
                    <small class="text-muted d-block" style="font-size: 0.76rem; line-height: 1.5;">
                        ⚠️ <strong>Lưu ý quan trọng:</strong> Nếu bạn chuyển khoản thủ công (không quét QR), bắt buộc phải điền chính xác nội dung chuyển khoản là <strong class="text-dark font-monospace">{{ $order->order_code }}</strong> để đơn hàng được duyệt tự động.
                    </small>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const orderCode   = "{{ $order->order_code }}";
const checkUrl    = "{{ route('orders.qr.status', $order->order_code) }}";
const successUrl  = "{{ route('orders.success', $order->order_code) }}";

// ── QR LOADED ──
function qrLoaded() {
    document.getElementById('qrLoading').style.display = 'none';
    document.getElementById('qrImage').style.display   = 'inline-block';
}

function qrError() {
    document.getElementById('qrLoading').innerHTML = `
        <div class="text-center p-4">
            <i class="bi bi-wifi-off fs-1 text-muted"></i>
            <p class="small text-muted mt-2">Không thể tạo mã QR lúc này.<br>Vui lòng chuyển khoản thủ công theo thông tin bên dưới.</p>
        </div>
    `;
}

// ── COPY TEXT ──
function copyText(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Đã sao chép vào bộ nhớ tạm!');
    }).catch(() => {
        // Fallback cho trình duyệt cũ
        const el = document.createElement('input');
        el.value = text;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        showToast('Đã sao chép vào bộ nhớ tạm!');
    });
}

function showToast(msg) {
    const toast = document.createElement('div');
    toast.className = 'position-fixed bottom-0 end-0 m-3 alert alert-dark py-2 px-3.5 rounded-3 text-white border-0 shadow';
    toast.style.zIndex = '9999';
    toast.style.background = '#1a1a2e';
    toast.innerHTML = `<i class="bi bi-check-circle-fill text-warning me-2"></i><span class="small font-sans">${msg}</span>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2500);
}

// ── ĐẾM NGƯỢC 15 PHÚT ──
let timeLeft = 15 * 60; // 900 giây

const timerEl = document.getElementById('timer');

const countdown = setInterval(() => {
    timeLeft--;
    const m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
    const s = (timeLeft % 60).toString().padStart(2, '0');
    timerEl.textContent = `${m}:${s}`;

    if (timeLeft <= 60) {
        timerEl.className = 'text-danger fw-bold fs-5';
    }

    if (timeLeft <= 0) {
        clearInterval(countdown);
        timerEl.textContent = 'Đã hết hạn';

        document.getElementById('paymentStatus').className
            = 'alert alert-danger d-flex align-items-center gap-2 mb-0 rounded-3 border-danger-subtle py-2.5';
        document.getElementById('paymentStatus').innerHTML = `
            <i class="bi bi-x-circle-fill text-danger flex-shrink-0 fs-5"></i>
            <span class="small text-danger-emphasis">Mã QR đã hết hạn giao dịch. <a href="{{ route('orders.checkout') }}" class="fw-bold text-danger">Đặt lại đơn hàng</a></span>
        `;

        clearInterval(polling);
    }
}, 1000);

// ── POLLING KIỂM TRA THANH TOÁN (mỗi 5 giây) ──
const polling = setInterval(async () => {
    try {
        const res  = await fetch(checkUrl);
        const data = await res.json();

        if (data.status === 'paid') {
            clearInterval(polling);
            clearInterval(countdown);

            // Cập nhật UI
            document.getElementById('paymentStatus').className
                = 'alert alert-success d-flex align-items-center justify-content-center gap-2 mb-0 rounded-3 border-success-subtle py-2.5';
            document.getElementById('paymentStatus').innerHTML = `
                <i class="bi bi-check-circle-fill text-success flex-shrink-0 fs-5"></i>
                <span class="small text-success-emphasis fw-bold">Thanh toán thành công! Đang chuyển hướng...</span>
            `;

            // Redirect sau 2 giây
            setTimeout(() => {
                window.location.href = successUrl;
            }, 2000);
        }
    } catch (e) {
        console.error('Polling error:', e);
    }
}, 5000); // Poll mỗi 5 giây

// Dừng poll khi thoát trang
window.addEventListener('beforeunload', () => {
    clearInterval(polling);
    clearInterval(countdown);
});
</script>
@endpush
@endsection