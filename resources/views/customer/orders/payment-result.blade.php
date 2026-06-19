@extends('layouts.customer')
@section('title', $success ? 'Thanh Toán Thành Công' : 'Thanh Toán Thất Bại')

@push('styles')
<style>
    .result-card {
        border: 1px solid rgba(201, 168, 76, 0.15) !important;
        border-radius: 20px !important;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .result-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(201, 168, 76, 0.1) !important;
    }
    .icon-wrapper {
        width: 88px;
        height: 88px;
        margin: 0 auto;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }
    .btn-gold-action {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f !important;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.25s;
        box-shadow: 0 4px 14px rgba(201, 168, 76, 0.25);
    }
    .btn-gold-action:hover {
        background: linear-gradient(135deg, var(--gold-light), var(--gold));
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(201, 168, 76, 0.4);
    }
    .btn-dark-outline {
        border: 1.5px solid #1a1a2e !important;
        color: #1a1a2e !important;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.25s;
    }
    .btn-dark-outline:hover {
        background: #1a1a2e !important;
        color: #fff !important;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-4">
        <div class="col-md-6 col-lg-5">
            <div class="result-card card border-0 shadow-sm text-center p-4 p-md-5 bg-white">

                @if($success)
                    {{-- THÀNH CÔNG --}}
                    <div class="mb-4">
                        <div class="icon-wrapper bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    
                    <h3 class="fw-bold text-success mb-2">Thanh Toán Thành Công!</h3>
                    <p class="text-muted small mb-4">Cảm ơn bạn đã tin tưởng và lựa chọn tuyệt phẩm của chúng tôi.</p>
                    
                    <div class="p-3 bg-light rounded-4 mb-4 text-start border border-light">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Mã đơn hàng:</span>
                            <span class="fw-bold text-dark">{{ $orderCode }}</span>
                        </div>
                        @if($transactionId)
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Mã giao dịch VNPay:</span>
                            <span class="fw-semibold text-secondary small">{{ $transactionId }}</span>
                        </div>
                        @endif
                    </div>

                    <p class="text-muted small mb-4">
                        Đơn hàng của bạn đã được xác nhận và đang tiến hành đóng gói vận chuyển. Thông tin chi tiết đã được gửi tới email của bạn.
                    </p>

                    <div class="d-flex flex-column gap-2 mt-4">
                        <a href="{{ route('orders.detail', $orderCode) }}" class="btn btn-gold-action py-3">
                            <i class="bi bi-eye me-2"></i> Xem Đơn Hàng Chi Tiết
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-dark-outline py-2.5">
                            <i class="bi bi-house me-2"></i> Quay Lại Trang Chủ
                        </a>
                    </div>

                @else
                    {{-- THẤT BẠI --}}
                    <div class="mb-4">
                        <div class="icon-wrapper bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    
                    <h3 class="fw-bold text-danger mb-2">Thanh Toán Thất Bại</h3>
                    <p class="text-muted mb-4">{{ $message ?? 'Giao dịch không thành công hoặc đã bị huỷ.' }}</p>
                    
                    <div class="alert alert-warning small text-start border-0 rounded-4 p-3 bg-warning bg-opacity-10 text-warning-dark mb-4">
                        <div class="d-flex gap-2">
                            <i class="bi bi-info-circle-fill mt-0.5"></i>
                            <div>
                                <strong>Lưu ý:</strong> Đơn hàng này đã bị hủy tự động do thanh toán không hoàn tất. Các sản phẩm đã được hoàn lại tồn kho của hệ thống. Vui lòng tạo đơn hàng mới!
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('cart.index') }}" class="btn btn-gold-action py-3">
                            <i class="bi bi-cart-plus me-2"></i> Quay Lại Giỏ Hàng & Thử Lại
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-dark-outline py-2.5">
                            <i class="bi bi-house me-2"></i> Quay Lại Trang Chủ
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection