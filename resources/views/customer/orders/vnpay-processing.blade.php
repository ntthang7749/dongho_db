@extends('layouts.customer')
@section('title', 'Đang Xử Lý Thanh Toán')

@push('styles')
<style>
    .processing-card {
        border: 1px solid rgba(201, 168, 76, 0.15) !important;
        border-radius: 24px !important;
        background: linear-gradient(145deg, #0a0a0f 0%, #111128 100%);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3) !important;
    }
    .spinner-gold {
        color: var(--gold);
        border-right-color: transparent;
    }
    .text-gold {
        color: var(--gold) !important;
    }
    .btn-gold-link {
        color: var(--gold) !important;
        text-decoration: none;
        transition: color 0.2s;
    }
    .btn-gold-link:hover {
        color: var(--gold-light) !important;
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-5">
        <div class="col-md-6 col-lg-5 text-center">
            <div class="processing-card card border-0 p-5 text-white">
                <div class="mb-4">
                    <div class="spinner-border spinner-gold mb-3" style="width: 4.5rem; height: 4.5rem; border-width: 0.35em;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                
                <h4 class="fw-bold text-gold mb-3" style="font-family: 'Playfair Display', serif; letter-spacing: 0.5px;">Đang Kết Nối Cổng Thanh Toán</h4>
                <p class="text-white-50 small mb-4">Hệ thống đang chuyển hướng bạn đến cổng thanh toán an toàn của VNPay. Vui lòng không tắt trình duyệt hoặc tải lại trang.</p>
                
                <hr class="border-secondary opacity-25 my-4">
                
                <p class="text-muted small mb-0">
                    Nếu trình duyệt không tự động chuyển hướng sau 3 giây, vui lòng 
                    <a href="{{ $paymentUrl }}" class="btn-gold-link fw-bold">
                        click vào đây
                    </a> để tiếp tục.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tự động chuyển hướng sau 1 giây
    setTimeout(() => {
        window.location.href = "{{ $paymentUrl }}";
    }, 1000);
</script>
@endpush
@endsection