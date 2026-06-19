@extends('layouts.customer')
@section('title', 'Gợi Ý Sản Phẩm — Tuyệt Phẩm Bán Chạy')

@push('styles')
<style>
    /* ── SUGGESTIONS HERO ── */
    .suggestions-hero {
        background: linear-gradient(135deg, #0a0a0f, #121228);
        border-bottom: 1px solid rgba(201,168,76,0.15);
        position: relative;
    }
    .suggestions-hero::before {
        content:'';
        position:absolute;
        inset:0;
        background: radial-gradient(circle at 50% 50%, rgba(201,168,76,0.09), transparent 60%),
                    radial-gradient(circle at 15% 85%, rgba(138,43,226,0.07), transparent 45%);
        pointer-events:none;
    }
    .suggestions-hero-badge {
        font-size: 0.72rem;
        letter-spacing: 2px;
        background: rgba(201,168,76,0.1);
        border: 1px solid rgba(201,168,76,0.25);
        border-radius: 100px;
        padding: 6px 16px;
        color: var(--gold);
        animation: goldPulse 3s infinite;
    }
    @keyframes goldPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(201,168,76,0.2); }
        50% { box-shadow: 0 0 12px 4px rgba(201,168,76,0.15); }
    }
    
    .btn-gold-action {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f !important;
        transition: all 0.22s;
    }
    .btn-gold-action:hover {
        background: linear-gradient(135deg, var(--gold-light), var(--gold));
        box-shadow: 0 8px 24px rgba(201,168,76,0.35);
    }
</style>
@endpush

@section('content')
<div class="suggestions-hero py-5 text-center text-white">
    <div class="container position-relative py-3">
        <div class="suggestions-hero-badge d-inline-flex align-items-center gap-2 fw-bold text-uppercase mb-3">
            <i class="bi bi-star-fill text-gold"></i> TUYỆT PHẨM BÁN CHẠY
        </div>
        <h1 class="fs-1 fw-bold mb-3" style="font-family:'Playfair Display',serif;">Gợi Ý <span class="text-gold">Sản Phẩm</span></h1>
        <p class="mx-auto mb-4 text-white-50 small" style="max-width: 600px; line-height: 1.6;">
            Tinh hoa hội tụ trong những mẫu đồng hồ bán chạy nhất tại hệ thống của chúng tôi. Những tuyệt tác thời gian xuất sắc từ các thương hiệu hàng đầu thế giới được chuyên gia tuyển chọn.
        </p>
        <nav class="d-flex align-items-center justify-content-center gap-2 small">
            <a href="{{ route('home') }}" class="text-decoration-none text-gold text-opacity-75 hover-gold"><i class="bi bi-house me-1"></i>Trang chủ</a>
            <span class="text-white-50 opacity-25">/</span>
            <span class="text-white-50 opacity-75">Gợi ý sản phẩm</span>
        </nav>
    </div>
</div>

<div class="py-5 bg-light">
    <div class="container">
        @if($products->isEmpty())
            <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-light">
                <div class="fs-1 mb-3">🔍</div>
                <h4 class="text-dark" style="font-family:'Playfair Display',serif;">Chưa có sản phẩm gợi ý</h4>
                <p class="text-muted small mb-4">Hệ thống đang cập nhật danh sách sản phẩm bán chạy.</p>
                <a href="{{ route('products.index') }}" class="btn btn-gold-action px-4 py-2 rounded-3 fw-bold small">Khám Phá Sản Phẩm Ngay</a>
            </div>
        @else
            <div class="row g-4">
                @foreach($products as $product)
                    @include('customer.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
