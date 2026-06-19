@extends('layouts.customer')
@section('title', 'Trang Chủ')

@push('styles')
<style>
    /* ===== HERO SLIDER ===== */
    .hero-slide { height: 540px; }
    .hero-slide img { transition: transform 8s ease; }
    #heroSlider .carousel-item.active .hero-slide img { transform: scale(1.05); }

    .hero-overlay {
        background: linear-gradient(
            90deg,
            rgba(10,10,15,0.78) 0%,
            rgba(10,10,15,0.45) 50%,
            rgba(10,10,15,0.15) 100%
        );
    }

    .hero-eyebrow {
        background: rgba(201,168,76,0.1);
        border: 1px solid rgba(201,168,76,0.25);
        color: var(--gold);
        letter-spacing: 2px;
    }

    .hero-title {
        font-family: 'Playfair Display', serif;
        line-height: 1.15;
        text-shadow: 0 2px 20px rgba(0,0,0,0.4);
    }
    .hero-title span { color: var(--gold); }

    .btn-hero-primary {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f !important;
        transition: all 0.3s;
        box-shadow: 0 6px 24px rgba(201,168,76,0.4);
    }
    .btn-hero-primary:hover {
        background: linear-gradient(135deg, var(--gold-light), var(--gold));
        transform: translateY(-2px);
        box-shadow: 0 10px 32px rgba(201,168,76,0.55);
    }

    .btn-hero-ghost {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.25);
        color: rgba(255,255,255,0.88) !important;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }
    .btn-hero-ghost:hover {
        background: rgba(255,255,255,0.18);
        border-color: rgba(255,255,255,0.45);
        color: #fff !important;
        transform: translateY(-1px);
    }

    /* Carousel controls */
    .carousel-control-prev,
    .carousel-control-next {
        width: 48px; height: 48px;
        top: 50%; transform: translateY(-50%);
        background: rgba(201,168,76,0.15);
        border: 1px solid rgba(201,168,76,0.3);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s;
        margin: 0 16px;
    }
    #heroSlider:hover .carousel-control-prev,
    #heroSlider:hover .carousel-control-next { opacity: 1; }
    .carousel-control-prev:hover,
    .carousel-control-next:hover { background: rgba(201,168,76,0.35); }

    .carousel-indicators button {
        width: 24px; height: 3px;
        border-radius: 2px;
        background: rgba(201,168,76,0.4);
        border: none;
        transition: all 0.3s;
        margin: 0 3px;
    }
    .carousel-indicators button.active {
        width: 48px;
        background: var(--gold);
    }

    /* ===== FEATURES BAR ===== */
    .features-bar {
        background: linear-gradient(135deg, #0d0d18, #111128);
        border-top: 1px solid rgba(201,168,76,0.15);
        border-bottom: 1px solid rgba(201,168,76,0.15);
    }
    .feature-item { transition: background 0.2s; }
    .feature-item:hover { background: rgba(201,168,76,0.05); }

    .feature-icon {
        width: 40px; height: 40px;
        background: rgba(201,168,76,0.1);
        border: 1px solid rgba(201,168,76,0.2);
        color: var(--gold);
    }

    /* ===== CATEGORY CARDS ===== */
    .category-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(201, 168, 76, 0.16);
        box-shadow: 0 4px 20px rgba(201, 168, 76, 0.04), 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .category-card::before {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--gold), var(--gold-light));
        transform: scaleX(0);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .category-card:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: rgba(201, 168, 76, 0.55);
        box-shadow: 0 16px 36px rgba(201, 168, 76, 0.18), 0 6px 20px rgba(99, 60, 150, 0.1);
    }
    .category-card:hover::before { transform: scaleX(1); }

    .category-icon-wrap {
        width: 76px; height: 76px;
        border-radius: 22px;
        background: linear-gradient(135deg, rgba(201,168,76,0.14), rgba(201,168,76,0.04));
        border: 1px solid rgba(201,168,76,0.25);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .category-card:hover .category-icon-wrap {
        background: linear-gradient(135deg, rgba(201,168,76,0.22), rgba(201,168,76,0.08));
        border-color: var(--gold-light);
        transform: scale(1.1) rotate(2deg);
        box-shadow: 0 8px 20px rgba(201, 168, 76, 0.2);
    }
    .category-img { height: 76px; width: 76px; }
    .category-name {
        font-size: 0.95rem;
        letter-spacing: 0.3px;
        transition: color 0.25s;
    }
    .category-card:hover .category-name { color: var(--gold-dark); }
    .category-count {
        background: rgba(201, 168, 76, 0.08);
        transition: all 0.3s;
    }
    .category-card:hover .category-count {
        color: var(--gold-dark);
        background: rgba(201, 168, 76, 0.16);
    }

    /* ===== SECTIONS ===== */
    .section-light { background: #f5f4f0; }
    .section-dark  {
        background: linear-gradient(135deg, #0d0d18, #111128);
    }
    .section-dark .section-title { color: rgba(240,236,224,0.95) !important; }
    .section-dark .section-title::after { background: linear-gradient(90deg, var(--gold), var(--gold-light)); }

    /* View all button */
    .btn-view-all {
        font-size: 0.835rem;
        font-weight: 600;
        color: var(--gold-dark);
        border: 1.5px solid rgba(201,168,76,0.4);
        transition: all 0.25s;
    }
    .btn-view-all:hover {
        background: var(--gold);
        border-color: var(--gold);
        color: #0a0a0f !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(201,168,76,0.3);
    }
    .section-dark .btn-view-all { color: var(--gold); border-color: rgba(201,168,76,0.3); }
    .section-dark .btn-view-all:hover { color: #0a0a0f !important; }

    /* ===== AI RECOMMEND ===== */
    @keyframes aiGlowPulse {
        0%, 100% {
            box-shadow: 0 0 6px rgba(201, 168, 76, 0.4), inset 0 0 4px rgba(201, 168, 76, 0.2);
            border-color: rgba(201, 168, 76, 0.3);
            transform: scale(1);
        }
        50% {
            box-shadow: 0 0 16px rgba(201, 168, 76, 0.75), inset 0 0 8px rgba(201, 168, 76, 0.4);
            border-color: var(--gold-light);
            transform: scale(1.02);
        }
    }
    .ai-badge {
        background: rgba(201,168,76,0.15); border: 1px solid rgba(201,168,76,0.3);
        color: var(--gold-light); font-weight: 700;
        letter-spacing: 1.5px;
        animation: aiGlowPulse 2.5s infinite ease-in-out;
    }

    /* Shimmer */
    .shimmer-box {
        background: linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%);
        background-size: 200% 100%; animation: shimmer 1.5s infinite;
    }
    @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    /* Product Tabs */
    .prod-tabs { background: #ede9de; }
    .prod-tab {
        color: #666;
        font-size: 0.82rem;
        transition: all 0.22s;
    }
    .prod-tab.active, .prod-tab:hover {
        background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
        color: #fff;
        box-shadow: 0 3px 12px rgba(26,26,46,0.25);
    }
    .prod-tab-pane { display: none; }
    .prod-tab-pane.active { display: block; }

    /* ===== FLASH SALE ===== */
    .flash-sale-bar {
        background: linear-gradient(135deg, #0a0a0f 0%, #1a0a14 50%, #0a0a1a 100%);
        border: 1px solid rgba(233,69,96,0.3);
    }
    .flash-sale-bar::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 0% 50%, rgba(233,69,96,0.12), transparent 60%),
                    radial-gradient(ellipse at 100% 50%, rgba(201,168,76,0.08), transparent 60%);
        pointer-events: none;
    }
    .flash-icon {
        width: 44px; height: 44px;
        background: rgba(233,69,96,0.18);
        border: 1px solid rgba(233,69,96,0.35);
        animation: flashPulse 1.5s ease-in-out infinite;
    }
    @keyframes flashPulse {
        0%,100% { box-shadow: 0 0 0 0 rgba(233,69,96,0.4); }
        50%      { box-shadow: 0 0 0 8px rgba(233,69,96,0); }
    }
    .flash-title {
        font-family: 'Playfair Display', serif;
        color: #f0ece0;
    }
    
    .countdown-unit {
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(201,168,76,0.2);
        min-width: 56px;
    }
    .countdown-num {
        color: var(--gold);
        font-family: 'Inter', sans-serif;
    }
    .countdown-label { font-size: 0.62rem; color: rgba(240,236,224,0.4); letter-spacing: 0.8px; }
    .countdown-sep { font-size: 1.4rem; font-weight: 800; color: #e94560; }

    /* ===== TRUST BAR ===== */
    .trust-item {
        background: linear-gradient(135deg, rgba(26,26,46,0.65) 0%, rgba(15,15,30,0.85) 100%);
        border: 1px solid rgba(201,168,76,0.15);
        color: #f0ece0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        backdrop-filter: blur(10px);
    }
    .trust-item:hover {
        background: rgba(201,168,76,0.06);
        border-color: rgba(201,168,76,0.45);
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.25);
    }
    .trust-icon-wrap {
        width: 52px; height: 52px;
        background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(201,168,76,0.05));
        border: 1px solid rgba(201,168,76,0.2);
        color: var(--gold);
        transition: all 0.25s;
    }
    .trust-item:hover .trust-icon-wrap {
        background: rgba(201,168,76,0.2);
        border-color: rgba(201,168,76,0.4);
        transform: scale(1.08);
    }
    .trust-val {
        font-family: 'Playfair Display', serif;
        color: var(--gold);
    }

    /* ===== PROMO BANNERS ===== */
    .promo-banner {
        height: 200px;
        transition: all 0.3s;
    }
    .promo-banner:hover { transform: translateY(-4px); box-shadow: 0 16px 48px rgba(0,0,0,0.2); }
    .promo-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: inherit;
        transition: opacity 0.3s;
    }
    .promo-banner-1 { background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 60%, #2d1a0a 100%); }
    .promo-banner-2 { background: linear-gradient(135deg, #0a100a 0%, #0d1a10 60%, #0a1a2e 100%); }
    .promo-eyebrow {
        background: rgba(201,168,76,0.15); border: 1px solid rgba(201,168,76,0.3);
        color: var(--gold);
    }
    .promo-title {
        font-family: 'Playfair Display', serif;
        color: #f0ece0;
    }
    .promo-cta {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f !important;
        font-size: 0.79rem; font-weight: 700;
        transition: all 0.22s;
    }
    .promo-banner:hover .promo-cta { transform: translateX(4px); }
    .promo-deco {
        position: absolute;
        right: -20px; top: 50%;
        transform: translateY(-50%);
        font-size: 9rem;
        opacity: 0.06;
        line-height: 1;
        z-index: 0;
    }

    /* ===== BRAND MARQUEE ===== */
    .brand-marquee-wrap {
        overflow: hidden;
        position: relative;
    }
    .brand-marquee-wrap::before,
    .brand-marquee-wrap::after {
        content: '';
        position: absolute;
        top: 0; bottom: 0;
        width: 80px;
        z-index: 2;
        pointer-events: none;
    }
    .brand-marquee-wrap::before { left: 0; background: linear-gradient(90deg, #f5f4f0, transparent); }
    .brand-marquee-wrap::after  { right: 0; background: linear-gradient(-90deg, #f5f4f0, transparent); }
    .brand-marquee {
        display: flex;
        gap: 16px;
        width: max-content;
        animation: marquee 28s linear infinite;
    }
    .brand-marquee:hover { animation-play-state: paused; }
    @keyframes marquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    .brand-pill {
        background: #fff;
        border: 1px solid #ede9df;
        transition: all 0.25s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .brand-pill:hover {
        border-color: rgba(201,168,76,0.5);
        box-shadow: 0 6px 22px rgba(0,0,0,0.1);
        transform: translateY(-3px);
    }
    .brand-pill img { height: 30px; object-fit: contain; filter: grayscale(0.5); transition: filter 0.25s; }
    .brand-pill:hover img { filter: none; }
    .brand-pill-name { font-size: 0.82rem; font-weight: 700; color: #1a1a2e; }

    /* ===== REVIEWS ===== */
    .review-strip {
        transition: all 0.25s;
    }
    .review-strip:hover { box-shadow: 0 8px 28px rgba(0,0,0,0.15) !important; transform: translateY(-2px); }
    .review-avatar {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
    }

    /* Note Chips */
    .note-chip {
        background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.25);
        color: var(--gold-dark); font-weight: 600;
        font-size: 0.72rem;
    }
    .note-chip-red {
        background: rgba(233,69,96,0.08); border-color: rgba(233,69,96,0.22);
        color: #c41a38;
    }
    .note-chip-green {
        background: rgba(34,197,94,0.08); border-color: rgba(34,197,94,0.22);
        color: #15803d;
    }

    .reveal {
        opacity: 0;
        transform: translateY(35px);
        transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: transform, opacity;
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    /* ===== SPOTLIGHT ===== */
    .spotlight-section {
        background: linear-gradient(135deg, #09090e 0%, #120c1a 50%, #0a0e14 100%);
        position: relative;
        overflow: hidden;
        border-top: 1px solid rgba(201,168,76,0.15);
        border-bottom: 1px solid rgba(201,168,76,0.15);
    }
    .spotlight-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 75% 50%, rgba(201,168,76,0.08), transparent 50%),
                    radial-gradient(circle at 25% 30%, rgba(99,60,150,0.06), transparent 50%);
        pointer-events: none;
    }
    .spotlight-badge {
        background: rgba(201, 168, 76, 0.12);
        border: 1px solid rgba(201, 168, 76, 0.3);
        color: var(--gold-light);
        letter-spacing: 2px;
        animation: aiGlowPulse 3s infinite ease-in-out;
    }
    .spotlight-title {
        font-family: 'Playfair Display', serif;
        line-height: 1.2;
    }
    .spotlight-title span {
        color: var(--gold);
        text-shadow: 0 0 15px rgba(201, 168, 76, 0.35);
    }
    .spotlight-desc {
        font-size: 1.02rem;
        color: rgba(255,255,255,0.7);
        line-height: 1.7;
    }
    
    .spotlight-spec-item {
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(201,168,76,0.12);
        backdrop-filter: blur(10px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .spotlight-spec-item:hover {
        border-color: rgba(201,168,76,0.45);
        background: rgba(201,168,76,0.05);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(201,168,76,0.08);
    }
    .spotlight-spec-icon {
        width: 38px; height: 38px;
        background: rgba(201, 168, 76, 0.1);
        color: var(--gold);
    }
    
    .spotlight-glow {
        position: absolute;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,168,76,0.16) 0%, rgba(99,60,150,0.08) 50%, transparent 70%);
        filter: blur(15px);
        animation: spinGlow 20s linear infinite;
    }
    .spotlight-ring {
        position: absolute;
        width: 280px;
        height: 280px;
        border: 1px dashed rgba(201,168,76,0.22);
        border-radius: 50%;
        animation: rotateRing 40s linear infinite;
    }
    .spotlight-img {
        width: 280px;
        height: 280px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid rgba(201, 168, 76, 0.35);
        z-index: 1;
        filter: drop-shadow(0 15px 35px rgba(0,0,0,0.65)) drop-shadow(0 5px 15px rgba(201,168,76,0.25));
        animation: spotlightFloat 4s ease-in-out infinite;
    }
    @keyframes spinGlow {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes rotateRing {
        0% { transform: rotate(360deg); }
        100% { transform: rotate(0deg); }
    }
    @keyframes spotlightFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-16px); }
    }

    @media (max-width: 768px) {
        .hero-slide { height: 360px; }
        .hero-title { font-size: 2rem; }
        .flash-sale-bar { padding: 20px; }
        .spotlight-title { font-size: 2rem; }
        .spotlight-img-container { min-height: 320px; }
        .spotlight-img { width: 200px; height: 200px; }
        .spotlight-glow { width: 240px; height: 240px; }
        .spotlight-ring { width: 200px; height: 200px; }
    }
</style>
@endpush

@section('content')

{{-- ════════ HERO SLIDER ════════ --}}
<div id="heroSlider" class="carousel slide position-relative overflow-hidden" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
        @forelse($banners as $i => $banner)
            <button type="button" data-bs-target="#heroSlider"
                    data-bs-slide-to="{{ $i }}"
                    class="{{ $i === 0 ? 'active' : '' }}"></button>
        @empty
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active"></button>
        @endforelse
    </div>

    <div class="carousel-inner">
        @forelse($banners as $i => $banner)
            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                <div class="hero-slide position-relative overflow-hidden">
                    <img src="{{ asset('storage/' . $banner->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $banner->title }}"
                         @if($i > 0) loading="lazy" @endif decoding="async">
                    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
                    <div class="hero-caption position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center px-4 px-md-5">
                        <div class="hero-content" style="max-width:560px;">
                            <div class="hero-eyebrow badge d-inline-flex align-items-center gap-1.5 py-1.5 px-3 rounded-pill mb-3 text-uppercase">
                                <i class="bi bi-stars"></i> Bộ Sưu Tập Mới
                            </div>
                            <h2 class="hero-title display-4 fw-bold mb-3 text-white">{{ $banner->title }}</h2>
                            @if($banner->link)
                                <a href="{{ $banner->link }}" class="btn btn-hero-primary d-inline-flex align-items-center gap-2 rounded-pill px-4 py-2.5 fw-bold">
                                    Khám phá ngay <i class="bi bi-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="carousel-item active">
                <div class="hero-slide position-relative overflow-hidden" style="background: linear-gradient(135deg, #0a0a0f 0%, #141428 50%, #0a0a0f 100%);">
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background:radial-gradient(ellipse at 20% 50%,rgba(201,168,76,0.12),transparent 60%),radial-gradient(ellipse at 80% 30%,rgba(99,60,150,0.1),transparent 50%);"></div>
                    <div class="hero-caption position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center px-4 px-md-5">
                        <div class="hero-content" style="max-width:560px;">
                            <div class="hero-eyebrow badge d-inline-flex align-items-center gap-1.5 py-1.5 px-3 rounded-pill mb-3 text-uppercase">
                                <i class="bi bi-stars"></i> Premium Collection 2025
                            </div>
                            <h1 class="hero-title display-4 fw-bold mb-3 text-white">Đồng Hồ <span>Chính Hãng</span><br>Cao Cấp</h1>
                            <p class="hero-desc text-white-50 mb-4 fs-6">Bộ sưu tập đồng hồ từ các thương hiệu nổi tiếng thế giới.<br>Cam kết chính hãng 100% · Bảo hành toàn quốc.</p>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="{{ route('products.index') }}" class="btn btn-hero-primary d-inline-flex align-items-center gap-2 rounded-pill px-4 py-2.5 fw-bold">
                                    Mua ngay <i class="bi bi-arrow-right"></i>
                                </a>
                                <a href="{{ route('ai.image.form') }}" class="btn btn-hero-ghost d-inline-flex align-items-center gap-2 rounded-pill px-4 py-2.5 fw-bold">
                                    <i class="bi bi-camera"></i> Nhận diện AI
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
        <i class="bi bi-chevron-left" style="color:var(--gold);font-size:1.2rem;"></i>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
        <i class="bi bi-chevron-right" style="color:var(--gold);font-size:1.2rem;"></i>
    </button>
</div>

{{-- ════════ FEATURES BAR ════════ --}}
<div class="features-bar text-white py-1">
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-6 col-md-3">
                <div class="feature-item d-flex align-items-center gap-3 p-3.5 border-md-end border-bottom border-md-bottom-0 border-gold-light h-100" style="border-color: rgba(201,168,76,0.1) !important;">
                    <div class="feature-icon d-flex align-items-center justify-content-center rounded-3 fs-5 flex-shrink-0"><i class="bi bi-truck"></i></div>
                    <div>
                        <span class="feature-text-main fw-bold">Miễn phí vận chuyển</span>
                        <span class="feature-text-sub text-muted small">Đơn hàng từ 500k</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-item d-flex align-items-center gap-3 p-3.5 border-md-end border-bottom border-md-bottom-0 border-gold-light h-100" style="border-color: rgba(201,168,76,0.1) !important;">
                    <div class="feature-icon d-flex align-items-center justify-content-center rounded-3 fs-5 flex-shrink-0"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <span class="feature-text-main fw-bold">Bảo hành chính hãng</span>
                        <span class="feature-text-sub text-muted small">Đến 24 tháng</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-item d-flex align-items-center gap-3 p-3.5 border-md-end border-bottom border-md-bottom-0 border-gold-light h-100" style="border-color: rgba(201,168,76,0.1) !important;">
                    <div class="feature-icon d-flex align-items-center justify-content-center rounded-3 fs-5 flex-shrink-0"><i class="bi bi-arrow-repeat"></i></div>
                    <div>
                        <span class="feature-text-main fw-bold">Đổi trả 30 ngày</span>
                        <span class="feature-text-sub text-muted small">Không câu hỏi</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-item d-flex align-items-center gap-3 p-3.5 border-bottom border-md-bottom-0 h-100">
                    <div class="feature-icon d-flex align-items-center justify-content-center rounded-3 fs-5 flex-shrink-0"><i class="bi bi-headset"></i></div>
                    <div>
                        <span class="feature-text-main fw-bold">Hỗ trợ 24/7</span>
                        <span class="feature-text-sub text-muted small">Tư vấn tận tình</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════ DANH MỤC ════════ --}}
<section class="bg-white py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <h2 class="section-title mb-0 fs-3 fw-bold">Danh Mục Sản Phẩm</h2>
        </div>
        <div class="row g-3">
            @foreach($categories as $cat)
            <div class="col-6 col-md-3">
                <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="category-card d-block text-center p-4 rounded-4 text-decoration-none position-relative">
                    <div class="category-icon-wrap d-flex align-items-center justify-content-center mx-auto mb-3">
                        @if($cat->image)
                            <img src="{{ asset('storage/' . $cat->image) }}" class="category-img w-100 h-100 object-fit-contain" alt="{{ $cat->name }}"
                                 loading="lazy" decoding="async">
                        @else
                            <i class="bi bi-watch" style="font-size:2rem; color:var(--gold);"></i>
                        @endif
                    </div>
                    <div class="category-name text-dark fw-bold mb-2">{{ $cat->name }}</div>
                    <div class="category-count badge rounded-pill px-3 py-1 small text-muted">{{ $cat->products_count }} sản phẩm</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════ FLASH SALE COUNTDOWN BAR ════════ --}}
<section class="section-light py-5">
<div class="container reveal">
    <div class="flash-sale-bar d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4 p-4 p-md-5 rounded-4 position-relative overflow-hidden mb-0">
        <div class="flash-label d-flex align-items-center gap-3">
            <div class="flash-icon d-flex align-items-center justify-content-center rounded-3 fs-4">⚡</div>
            <div>
                <h3 class="flash-title mb-1 fw-bold">Flash Sale Hôm Nay</h3>
                <p class="mb-0 text-white-50 small">Giảm đến 40% — Số lượng có hạn, nhanh tay kẻo hết!</p>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="flash-countdown d-flex align-items-center gap-2">
                <div class="countdown-unit text-center px-3 py-2 rounded-3">
                    <span class="countdown-num d-block fs-3 fw-bold" id="cd-h">00</span>
                    <span class="countdown-label text-uppercase d-block text-muted">Giờ</span>
                </div>
                <span class="countdown-sep">:</span>
                <div class="countdown-unit text-center px-3 py-2 rounded-3">
                    <span class="countdown-num d-block fs-3 fw-bold" id="cd-m">00</span>
                    <span class="countdown-label text-uppercase d-block text-muted">Phút</span>
                </div>
                <span class="countdown-sep">:</span>
                <div class="countdown-unit text-center px-3 py-2 rounded-3">
                    <span class="countdown-num d-block fs-3 fw-bold" id="cd-s">00</span>
                    <span class="countdown-label text-uppercase d-block text-muted">Giây</span>
                </div>
            </div>
            <a href="{{ route('products.index', ['sort' => 'sale']) }}" class="btn-view-all d-inline-flex align-items-center gap-1.5 py-2 px-4 rounded-pill text-decoration-none" style="background:rgba(233,69,96,0.12); border-color:rgba(233,69,96,0.35); color:#e94560;">
                Mua ngay <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
</section>

{{-- ════════ TAB SẢN PHẨM: Nổi Bật · Mới Về · Khuyến Mãi ════════ --}}
<section class="bg-white py-5">
    <div class="container">

        {{-- Section header --}}
        <div class="prod-tabs-wrap d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3 mb-4 reveal">
            <div>
                <h2 class="section-title mb-1 fs-3 fw-bold">Trưng Bày Sản Phẩm</h2>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="note-chip badge rounded-pill px-3 py-1.5"><i class="bi bi-patch-check-fill"></i> 100% chính hãng</span>
                    <span class="note-chip-green note-chip badge rounded-pill px-3 py-1.5"><i class="bi bi-shield-check"></i> Bảo hành 24 tháng</span>
                    <span class="note-chip-red note-chip badge rounded-pill px-3 py-1.5"><i class="bi bi-fire"></i> Cập nhật hàng ngày</span>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="prod-tabs d-inline-flex gap-1 p-1 rounded-pill" role="tablist">
                    <button class="prod-tab px-4 py-2 rounded-pill fw-bold border-0 bg-transparent active" data-tab="featured">⭐ Nổi Bật</button>
                    <button class="prod-tab px-4 py-2 rounded-pill fw-bold border-0 bg-transparent" data-tab="new">🆕 Hàng Mới</button>
                    <button class="prod-tab px-4 py-2 rounded-pill fw-bold border-0 bg-transparent" data-tab="sale">🔥 Khuyến Mãi</button>
                </div>
                <a href="{{ route('products.index') }}" class="btn-view-all d-inline-flex align-items-center gap-1 py-1.5 px-3 rounded-pill text-decoration-none">Tất cả <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>

        {{-- Tab: Nổi bật --}}
        <div class="prod-tab-pane active reveal" id="tab-featured">
            @if($featuredProducts->count() > 0)
            <div class="row g-3">
                @foreach($featuredProducts as $product)
                    @include('customer.partials.product-card', ['product' => $product])
                @endforeach
            </div>
            @else
            <p class="text-muted text-center py-4">Chưa có sản phẩm nổi bật.</p>
            @endif
        </div>

        {{-- Tab: Hàng mới --}}
        <div class="prod-tab-pane" id="tab-new">
            @if($newProducts->count() > 0)
            <div class="row g-3">
                @foreach($newProducts as $product)
                    @include('customer.partials.product-card', ['product' => $product])
                @endforeach
            </div>
            @else
            <p class="text-muted text-center py-4">Chưa có hàng mới.</p>
            @endif
        </div>

        {{-- Tab: Khuyến mãi --}}
        <div class="prod-tab-pane" id="tab-sale">
            @php $saleProducts = ($featuredProducts->merge($newProducts))->filter(fn($p) => $p->sale_price); @endphp
            @if($saleProducts->count() > 0)
            <div class="row g-3">
                @foreach($saleProducts as $product)
                    @include('customer.partials.product-card', ['product' => $product])
                @endforeach
            </div>
            @else
            <p class="text-muted text-center py-4">Chưa có sản phẩm khuyến mãi.</p>
            @endif
        </div>

    </div>
</section>

{{-- ════════ PROMO BANNERS 2 CỘT ════════ --}}
<section class="section-light py-5">
<div class="container reveal">
    <div class="row g-3">
        <div class="col-md-6">
            <a href="{{ route('products.index', ['category'=>'dong-ho-nam']) }}" class="promo-banner promo-banner-1 d-flex align-items-center p-4 p-md-5 rounded-4 text-decoration-none position-relative overflow-hidden mb-1">
                <div class="promo-deco">⌚</div>
                <div class="promo-banner-content position-relative" style="z-index: 1;">
                    <div class="promo-eyebrow badge d-inline-flex align-items-center gap-1.5 py-1 px-3 rounded-pill mb-2"><i class="bi bi-gender-male"></i> Bộ sưu tập Nam</div>
                    <h3 class="promo-title fw-bold text-white mb-2 fs-4">Đồng Hồ Nam<br>Phong Cách Mạnh Mẽ</h3>
                    <p class="mb-3 small text-white-50">Rolex, Seiko, Citizen — từ doanh nhân tới thể thao</p>
                    <span class="promo-cta btn d-inline-flex align-items-center gap-1.5 rounded-pill px-3 py-1.5 fw-bold">Khám phá ngay <i class="bi bi-arrow-right"></i></span>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('products.index', ['category'=>'dong-ho-nu']) }}" class="promo-banner promo-banner-2 d-flex align-items-center p-4 p-md-5 rounded-4 text-decoration-none position-relative overflow-hidden mb-1">
                <div class="promo-deco">💎</div>
                <div class="promo-banner-content position-relative" style="z-index: 1;">
                    <div class="promo-eyebrow badge d-inline-flex align-items-center gap-1.5 py-1 px-3 rounded-pill mb-2"><i class="bi bi-gender-female"></i> Bộ sưu tập Nữ</div>
                    <h3 class="promo-title fw-bold text-white mb-2 fs-4">Đồng Hồ Nữ<br>Tinh Tế & Sang Trọng</h3>
                    <p class="mb-3 small text-white-50">Tissot, Longines, Fossil — vẻ đẹp vĩnh cửu</p>
                    <span class="promo-cta btn d-inline-flex align-items-center gap-1.5 rounded-pill px-3 py-1.5 fw-bold">Khám phá ngay <i class="bi bi-arrow-right"></i></span>
                </div>
            </a>
        </div>
    </div>
</div>
</section>

{{-- ════════ GỢI Ý AI (personalised) ════════ --}}
@if(session('viewed_products'))
<section class="section-dark py-5" id="aiRecommendSection" style="display:none;">
    <div class="container">
        <div class="prod-tabs-wrap d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3 mb-4">
            <div>
                <div class="ai-badge badge d-inline-flex align-items-center gap-1.5 py-1.5 px-3 rounded-pill text-uppercase mb-2"><i class="bi bi-stars"></i> AI Personalised</div>
                <h2 class="section-title mb-1 text-white fs-3 fw-bold">Gợi Ý Riêng Cho Bạn</h2>
                <small id="recommendReason" class="d-block text-gold-light opacity-75"></small>
            </div>
        </div>
        <div class="row g-3" id="aiRecommendProducts">
            @for($i = 0; $i < 4; $i++)
            <div class="col-6 col-md-3">
                <div class="rounded-4 overflow-hidden" style="background:#131320; border:1px solid rgba(201,168,76,0.08);">
                    <div class="shimmer-box rounded-2" style="height:220px;"></div>
                    <div class="p-3">
                        <div class="shimmer-box rounded-2 mb-2" style="height:12px;"></div>
                        <div class="shimmer-box rounded-2" style="height:18px; width:60%;"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>
@endif

{{-- ════════ SPOTLIGHT — SEIKO PRESAGE COCKTAIL TIME ════════ --}}
<section class="spotlight-section">
    <div class="container reveal">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="spotlight-badge badge d-inline-flex align-items-center gap-1.5 py-2 px-3 rounded-pill mb-3">
                    <i class="bi bi-award-fill"></i> Flagship Masterpiece
                </span>
                <h2 class="spotlight-title display-5 fw-bold text-white mb-3">Seiko Presage <br><span>Cocktail Time</span></h2>
                <p class="spotlight-desc text-white-50 mb-4 fs-6">
                    Tuyệt phẩm chế tác đỉnh cao từ bộ sưu tập Presage danh tiếng, lấy cảm hứng từ bầu trời hoàng hôn ấm áp của các quán bar Tokyo sang trọng. Mặt số chải tia Sunray tinh xảo hội tụ ánh sáng lộng lẫy kết hợp vỏ mạ vàng Champagne quý phái đem đến diện mạo độc bản đầy kiêu sa cho quý ông thượng lưu.
                </p>
                
                <div class="row g-3 spotlight-specs mb-4">
                    <div class="col-6">
                        <div class="spotlight-spec-item d-flex align-items-center gap-3 p-3 rounded-4">
                            <div class="spotlight-spec-icon d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                                <i class="bi bi-gear-wide-connected"></i>
                            </div>
                            <div>
                                <span class="spotlight-spec-label d-block text-uppercase text-muted small" style="letter-spacing: 0.5px;">Bộ Máy</span>
                                <span class="spotlight-spec-value fw-bold text-white small">Cơ Auto (41h cót)</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="spotlight-spec-item d-flex align-items-center gap-3 p-3 rounded-4">
                            <div class="spotlight-spec-icon d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                                <i class="bi bi-gem"></i>
                            </div>
                            <div>
                                <span class="spotlight-spec-label d-block text-uppercase text-muted small" style="letter-spacing: 0.5px;">Mặt Kính</span>
                                <span class="spotlight-spec-value fw-bold text-white small">Kính Sapphire Cong</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="spotlight-spec-item d-flex align-items-center gap-3 p-3 rounded-4">
                            <div class="spotlight-spec-icon d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                                <i class="bi bi-droplet-half"></i>
                            </div>
                            <div>
                                <span class="spotlight-spec-label d-block text-uppercase text-muted small" style="letter-spacing: 0.5px;">Chống Nước</span>
                                <span class="spotlight-spec-value fw-bold text-white small">50m (5 ATM)</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="spotlight-spec-item d-flex align-items-center gap-3 p-3 rounded-4">
                            <div class="spotlight-spec-icon d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                                <i class="bi bi-shield-shaded"></i>
                            </div>
                            <div>
                                <span class="spotlight-spec-label d-block text-uppercase text-muted small" style="letter-spacing: 0.5px;">Vật Liệu Vỏ</span>
                                <span class="spotlight-spec-value fw-bold text-white small">Thép 316L mạ vàng</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    <div>
                        <span class="text-uppercase text-white-50 d-block small mb-1">Giá Tuyệt Phẩm</span>
                        <span class="text-gold-light fw-extrabold fs-3">18,500,000đ</span>
                    </div>
                    <a href="{{ route('products.show', 'seiko-presage-sarx055-cocktail-automatic') }}" class="btn btn-hero-primary d-inline-flex align-items-center gap-2 rounded-pill px-4 py-2.5 fw-bold" style="box-shadow: 0 8px 32px rgba(201,168,76,0.3);">
                        Sở hữu kiệt tác <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-6 text-center">
                <div class="spotlight-img-container position-relative d-flex align-items-center justify-content-center">
                    <div class="spotlight-glow"></div>
                    <div class="spotlight-ring"></div>
                    <img src="{{ asset('images/seiko-presage-cocktail.png') }}" class="spotlight-img img-fluid" alt="Seiko Presage Cocktail Time"
                         loading="lazy" decoding="async">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ════════ TRUST / SỐ LIỆU ẤN TƯỢNG ════════ --}}
<section class="section-dark py-5">
<div class="container reveal">
    <div class="text-center mb-5">
        <h2 class="section-title mb-0 fs-3 fw-bold text-white text-center">Vì Sao Chọn Chúng Tôi?</h2>
    </div>
    <div class="row g-3">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="trust-item d-flex flex-column align-items-center text-center p-4 h-100 rounded-4">
                <div class="trust-icon-wrap d-flex align-items-center justify-content-center rounded-3 fs-4 mb-2"><i class="bi bi-patch-check-fill"></i></div>
                <div class="trust-val fw-bold fs-4">100%</div>
                <div class="trust-label small mb-1">Hàng Chính Hãng</div>
                <div class="trust-sub small opacity-50">Có tem, hộp, CO/CQ</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="trust-item d-flex flex-column align-items-center text-center p-4 h-100 rounded-4">
                <div class="trust-icon-wrap d-flex align-items-center justify-content-center rounded-3 fs-4 mb-2"><i class="bi bi-people-fill"></i></div>
                <div class="trust-val fw-bold fs-4">50K+</div>
                <div class="trust-label small mb-1">Khách Hàng</div>
                <div class="trust-sub small opacity-50">Tin dùng từ 2018</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="trust-item d-flex flex-column align-items-center text-center p-4 h-100 rounded-4">
                <div class="trust-icon-wrap d-flex align-items-center justify-content-center rounded-3 fs-4 mb-2"><i class="bi bi-star-fill"></i></div>
                <div class="trust-val fw-bold fs-4">4.9★</div>
                <div class="trust-label small mb-1">Đánh Giá</div>
                <div class="trust-sub small opacity-50">Trên 10.000+ vote</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="trust-item d-flex flex-column align-items-center text-center p-4 h-100 rounded-4">
                <div class="trust-icon-wrap d-flex align-items-center justify-content-center rounded-3 fs-4 mb-2"><i class="bi bi-shield-check"></i></div>
                <div class="trust-val fw-bold fs-4">24T</div>
                <div class="trust-label small mb-1">Bảo Hành</div>
                <div class="trust-sub small opacity-50">Bảo hành tại nhà</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="trust-item d-flex flex-column align-items-center text-center p-4 h-100 rounded-4">
                <div class="trust-icon-wrap d-flex align-items-center justify-content-center rounded-3 fs-4 mb-2"><i class="bi bi-arrow-repeat"></i></div>
                <div class="trust-val fw-bold fs-4">30N</div>
                <div class="trust-label small mb-1">Đổi Trả</div>
                <div class="trust-sub small opacity-50">Không phí đổi trả</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="trust-item d-flex flex-column align-items-center text-center p-4 h-100 rounded-4">
                <div class="trust-icon-wrap d-flex align-items-center justify-content-center rounded-3 fs-4 mb-2"><i class="bi bi-headset"></i></div>
                <div class="trust-val fw-bold fs-4">24/7</div>
                <div class="trust-label small mb-1">Tư Vấn</div>
                <div class="trust-sub small opacity-50">Chat · Gọi · Email</div>
            </div>
        </div>
    </div>
</div>
</section>

{{-- ════════ ĐÁNH GIÁ KHÁCH HÀNG ════════ --}}
<section class="section-light py-5">
<div class="container reveal">
    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
        <div>
            <h2 class="section-title mb-1 fs-3 fw-bold">Khách Hàng Nói Gì?</h2>
            <div class="d-flex align-items-center gap-2">
                <span class="fs-4 fw-extrabold text-dark">4.9</span>
                <div>
                    @for($i=0;$i<5;$i++)
                    <i class="bi bi-star-fill text-warning small"></i>
                    @endfor
                    <div class="text-muted small" style="font-size:0.72rem;">Dựa trên 10.000+ đánh giá</div>
                </div>
            </div>
        </div>
        <a href="{{ route('products.index') }}" class="btn-view-all d-inline-flex align-items-center gap-1 py-1.5 px-3 rounded-pill text-decoration-none">Xem thêm <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-3">
        @php
        $reviews = [
            ['name'=>'Nguyễn Minh Tuấn', 'init'=>'T', 'text'=>'Mua chiếc Seiko SRPD55 tại đây, đồng hồ đẹp hơn ảnh nhiều, đóng gói cẩn thận, giao nhanh trong ngày. Sẽ giới thiệu cho bạn bè!', 'product'=>'Seiko SRPD55', 'date'=>'18/05/2025'],
            ['name'=>'Trần Thị Lan Anh', 'init'=>'L', 'text'=>'Shop tư vấn rất nhiệt tình, mình không biết chọn loại nào thì nhân viên hỗ trợ chọn ngay mẫu phù hợp. Đồng hồ chính hãng 100%.', 'product'=>'Citizen EW2421', 'date'=>'12/05/2025'],
            ['name'=>'Lê Quốc Hùng', 'init'=>'H', 'text'=>'Giá cả cạnh tranh, đã so sánh nhiều nơi thì đây là rẻ nhất mà vẫn chính hãng. Bảo hành 2 năm thực sự yên tâm.', 'product'=>'Casio G-Shock GA-110', 'date'=>'05/05/2025'],
        ];
        @endphp
        @foreach($reviews as $rev)
        <div class="col-md-4">
            <div class="review-strip card p-4 rounded-4 shadow-sm border border-light h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="review-avatar d-flex align-items-center justify-content-center rounded-circle fw-bold text-dark flex-shrink-0">{{ $rev['init'] }}</div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold text-dark small mb-0.5">{{ $rev['name'] }}</div>
                        <div class="d-flex gap-0.5 text-warning mb-0 small">
                            @for($i=0;$i<5;$i++)<i class="bi bi-star-fill"></i>@endfor
                        </div>
                    </div>
                    <span class="text-muted small flex-shrink-0" style="font-size:0.7rem;">{{ $rev['date'] }}</span>
                </div>
                <p class="mb-3 flex-grow-1 text-muted fst-italic small" style="line-height: 1.6;">"{{ $rev['text'] }}"</p>
                <div class="d-flex align-items-center justify-content-between gap-2 pt-2 border-top border-light">
                    <span class="text-muted small"><i class="bi bi-bag-check me-1 text-gold"></i>Đã mua: {{ $rev['product'] }}</span>
                    <span class="badge bg-success-light text-success rounded-pill px-2.5 py-1 small" style="font-size:0.68rem; font-weight:700; background:#dcfce7; color:#166534;">✓ Đã xác minh</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</section>

{{-- ════════ THƯƠNG HIỆU — MARQUEE ════════ --}}
@if($brands->count() > 0)
<section class="bg-white py-5">
<div class="container reveal">
    <h2 class="section-title text-center mb-4 fs-3 fw-bold" style="text-align:center!important;">Thương Hiệu Chính Hãng</h2>
    <div class="brand-marquee-wrap">
        <div class="brand-marquee">
            @php $loopBrands = $brands->concat($brands); @endphp
            @foreach($loopBrands as $brand)
            <a href="{{ route('products.index', ['brand' => $brand->slug]) }}" class="brand-pill d-inline-flex align-items-center gap-2 px-4 py-2.5 rounded-3 text-decoration-none shadow-sm">
                @if($brand->logo)
                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
                         loading="lazy" decoding="async">
                @endif
                <span class="brand-pill-name">{{ $brand->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>
</section>
@endif

@push('scripts')
<script>
// ── 1. PRODUCT TABS ──────────────────────────────────────────
document.querySelectorAll('.prod-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        const tab = btn.dataset.tab;
        // Active button
        document.querySelectorAll('.prod-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        // Show pane
        document.querySelectorAll('.prod-tab-pane').forEach(p => p.classList.remove('active'));
        document.getElementById('tab-' + tab)?.classList.add('active');
    });
});

// ── 2. FLASH SALE COUNTDOWN ──────────────────────────────────
(function startCountdown() {
    const now = new Date();
    const end = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);

    function pad(n) { return String(n).padStart(2, '0'); }

    function tick() {
        const diff = Math.max(0, end - new Date());
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        const elH = document.getElementById('cd-h');
        const elM = document.getElementById('cd-m');
        const elS = document.getElementById('cd-s');
        if (elH) elH.textContent = pad(h);
        if (elM) elM.textContent = pad(m);
        if (elS) elS.textContent = pad(s);
    }
    tick();
    setInterval(tick, 1000);
})();

// ── 3. SCROLL REVEAL ─────────────────────────────────────────
(function initReveal() {
    const els = document.querySelectorAll('.reveal');
    if (!els.length) return;
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); } });
    }, { threshold: 0.12 });
    els.forEach(el => observer.observe(el));
})();

// ── 4. AI PERSONALISED RECOMMENDATIONS ───────────────────────
@if(session('viewed_products'))
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const res  = await fetch('{{ route("ai.personalized") }}');
        const data = await res.json();

        if (data.success && data.products.length > 0) {
            const section = document.getElementById('aiRecommendSection');
            section.style.display = 'block';
            section.style.animation = 'fadeIn 0.5s ease';

            document.getElementById('recommendReason').textContent
                = '✨ ' + (data.reason || 'Dựa trên lịch sử xem của bạn');

            document.getElementById('aiRecommendProducts').innerHTML
                = data.products.map(p => `
                <div class="col-6 col-md-3">
                    <div class="pcard h-100" style="background: rgba(19, 19, 32, 0.95); border: 1px solid rgba(201, 168, 76, 0.16); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4); border-radius: 18px; overflow: hidden; display: flex; flex-direction: column;">
                        <div class="pcard-img-wrap" style="aspect-ratio: 1 / 1; overflow: hidden; position: relative; background: #0c0c12;">
                            <a href="${p.url}" style="display: block; width: 100%; height: 100%;">
                                <img src="${p.thumbnail || '/images/no-image.png'}" class="pcard-img" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.45s ease;" alt="${p.name}">
                            </a>
                            ${p.old_price ? `
                                <span class="pcard-badge-sale" style="position: absolute; top: 10px; left: 10px; font-size: 10px; font-weight: 800; background: #e94560; color: #fff; padding: 3px 9px; border-radius: 6px;">
                                    -${Math.round(((p.old_price - p.price) / p.old_price) * 100)}%
                                </span>` : ''}
                        </div>
                        <div class="pcard-body" style="padding: 14px 14px 8px; flex-grow: 1; display: flex; flex-direction: column; gap: 4px;">
                            <span class="pcard-brand" style="font-size: 0.7rem; font-weight: 700; color: var(--gold); letter-spacing: 0.8px; text-transform: uppercase;">${p.brand || 'CHÍNH HÃNG'}</span>
                            <a href="${p.url}" class="pcard-name" style="font-size: 0.875rem; font-weight: 600; color: rgba(240, 236, 224, 0.9); text-decoration: none; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; flex-grow: 1;">
                                ${p.name}
                            </a>
                            <div class="pcard-stars" style="display: flex; align-items: center; gap: 2px;">
                                <i class="bi bi-star-fill" style="color:#f59e0b;font-size:11px;"></i>
                                <i class="bi bi-star-fill" style="color:#f59e0b;font-size:11px;"></i>
                                <i class="bi bi-star-fill" style="color:#f59e0b;font-size:11px;"></i>
                                <i class="bi bi-star-fill" style="color:#f59e0b;font-size:11px;"></i>
                                <i class="bi bi-star-fill" style="color:#f59e0b;font-size:11px;"></i>
                                <span style="font-size: 0.7rem; color: #666; margin-left: 4px;">(5.0)</span>
                            </div>
                            <div class="pcard-price-wrap" style="display: flex; align-items: baseline; gap: 6px; flex-wrap: wrap;">
                                <span class="pcard-price-new" style="font-size: 1rem; font-weight: 800; color: #e94560;">${Number(p.price).toLocaleString('vi')}đ</span>
                                ${p.old_price ? `<span class="pcard-price-old" style="font-size: 0.78rem; color: #666; text-decoration: line-through;">${Number(p.old_price).toLocaleString('vi')}đ</span>` : ''}
                            </div>
                        </div>
                        <div class="pcard-footer" style="padding: 10px 14px 14px; background: transparent; border: none; display: flex;">
                            <a href="${p.url}" class="pcard-btn-cart text-center text-decoration-none" style="width: 100%; display: block; background: var(--gold-dark); color: #0a0a0f; border-radius: 10px; font-size: 0.8rem; font-weight: 700; padding: 9px 0; transition: all 0.25s; letter-spacing: 0.2px;">
                                Xem Chi Tiết
                            </a>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    } catch (e) { /* ignore */ }
});
@endif
</script>
@endpush

@endsection