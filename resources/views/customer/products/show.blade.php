@extends('layouts.customer')
@section('title', $product->name)

@push('styles')
<style>
/* ── PRODUCT HERO / NAV ── */
.product-topbar {
    background: linear-gradient(135deg, #0a0a0f, #111128);
    padding: 20px 0;
    border-bottom: 1px solid rgba(201,168,76,0.1);
}
.product-topbar-bc a { color:rgba(201,168,76,0.65); font-size:0.8rem; text-decoration:none; transition:color .2s; }
.product-topbar-bc a:hover { color:var(--gold); }
.product-topbar-bc span { color:rgba(240,236,224,0.3); font-size:0.75rem; }
.product-topbar-bc .current { color:rgba(240,236,224,0.55); font-size:0.8rem; }

/* ── MAIN PRODUCT SECTION ── */
.product-main { background:#f5f4f0; padding:36px 0 0; }

/* ── IMAGE GALLERY ── */
.gallery-wrap { position:sticky; top:80px; }
.gallery-main {
    border-radius:20px;
    overflow:hidden;
    background:#fff;
    box-shadow:0 4px 28px rgba(0,0,0,0.09);
    margin-bottom:12px;
    aspect-ratio:1/1;
    cursor:zoom-in;
    position:relative;
}
.gallery-main img {
    width:100%; height:100%;
    object-fit:cover;
    transition:transform 0.4s ease;
    display:block;
}
.gallery-main:hover img { transform:scale(1.04); }

/* Thumbnail strip */
.gallery-thumb {
    width:68px; height:68px;
    border-radius:12px;
    object-fit:cover;
    cursor:pointer;
    border:2px solid transparent;
    transition:all 0.2s;
    box-shadow:0 2px 8px rgba(0,0,0,0.08);
}
.gallery-thumb:hover { border-color:rgba(201,168,76,0.5); }
.gallery-thumb.active { border-color:var(--gold); box-shadow:0 0 0 3px rgba(201,168,76,0.15); }

/* ── PRODUCT INFO CARD ── */
.product-info-card {
    background:#fff;
    border-radius:20px;
    padding:32px;
    box-shadow:0 4px 28px rgba(0,0,0,0.07);
    height:fit-content;
}

/* Brand pill */
.product-brand-pill {
    background:rgba(201,168,76,0.08);
    border:1px solid rgba(201,168,76,0.2);
    color:#a07c30;
    font-size:0.72rem; font-weight:700; letter-spacing:1.2px; text-transform:uppercase;
}

/* Product title */
.product-title {
    font-family:'Playfair Display',serif;
    font-size:1.7rem; font-weight:700;
    color:#1a1a2e; line-height:1.3;
    margin-bottom:14px;
}

/* Stars */
.stars-wrap i { font-size:14px; color:#f59e0b; }
.rating-score { font-size:0.875rem; font-weight:700; color:#1a1a2e; }
.rating-sep { color:#ddd; }
.view-count { font-size:0.8rem; color:#bbb; }

/* Price box */
.price-box {
    background:linear-gradient(135deg, rgba(233,69,96,0.05), rgba(233,69,96,0.02));
    border:1px solid rgba(233,69,96,0.12);
    border-radius:16px;
}
.price-current {
    font-size:2rem; font-weight:900; color:#e94560;
    line-height:1;
}
.price-original {
    font-size:1rem; color:#bbb; text-decoration:line-through; font-weight:500;
}
.price-badge {
    background:#e94560; color:#fff;
    font-size:11px; font-weight:800;
    padding:4px 10px; border-radius:8px;
    letter-spacing:0.3px;
}

/* Stock */
.stock-ok {
    font-size:0.845rem; font-weight:600;
    color:#16a34a;
    background:rgba(34,197,94,0.08);
    border:1px solid rgba(34,197,94,0.2);
}
.stock-out {
    font-size:0.845rem; font-weight:600;
    color:#dc2626;
    background:rgba(239,68,68,0.08);
    border:1px solid rgba(239,68,68,0.2);
}

/* Qty + Cart */
.qty-control {
    background:#f8f7f3; border:1.5px solid #ebe9e0;
    border-radius:12px; overflow:hidden;
}
.qty-btn {
    width:38px; height:44px;
    background:none; border:none;
    font-size:1.1rem; font-weight:700;
    color:#555; cursor:pointer;
    transition:all .2s;
}
.qty-btn:hover { background:rgba(201,168,76,0.1); color:#a07c30; }
.qty-input {
    width:52px; height:44px;
    border:none; background:none;
    text-align:center; font-size:0.95rem;
    font-weight:700; color:#1a1a2e;
}
.qty-input:focus { outline:none; }

.btn-add-cart {
    flex:1; padding:12px 0;
    background:linear-gradient(135deg, #1a1a2e, #0d0d18);
    color:#fff; border:none;
    border-radius:12px;
    font-size:0.9rem; font-weight:700;
    cursor:pointer; transition:all .28s;
    min-width:160px;
    box-shadow:0 4px 16px rgba(0,0,0,0.15);
}
.btn-add-cart:hover {
    background:linear-gradient(135deg, var(--gold), var(--gold-dark));
    color:#0a0a0f;
    box-shadow:0 8px 28px rgba(201,168,76,0.4);
    transform:translateY(-2px);
}

.btn-buy-now {
    flex:1; padding:12px 0;
    background:linear-gradient(135deg, #e94560, #ba2d4a);
    color:#fff; border:none;
    border-radius:12px;
    font-size:0.9rem; font-weight:700;
    cursor:pointer; transition:all .28s;
    min-width:160px;
    box-shadow:0 4px 16px rgba(233,69,96,0.15);
}
.btn-buy-now:hover {
    background:linear-gradient(135deg, #ff5e7e, #e94560);
    color:#fff;
    box-shadow:0 8px 28px rgba(233,69,96,0.35);
    transform:translateY(-2px);
}

.btn-wishlist-detail {
    width:48px; height:48px;
    border-radius:12px;
    background:#f8f7f3; border:1.5px solid #ebe9e0;
    cursor:pointer; font-size:1.1rem;
    transition:all .25s;
    color:#888;
}
.btn-wishlist-detail:hover {
    border-color:#e94560; color:#e94560;
    background:rgba(233,69,96,0.06);
}
.btn-wishlist-detail.compare-btn:hover {
    border-color:var(--gold); color:var(--gold);
    background:rgba(201,168,76,0.06);
}
.btn-wishlist-detail.compare-btn.active {
    border-color:var(--gold) !important; color:var(--gold) !important;
    background:rgba(201,168,76,0.1) !important;
}

/* Guarantees row */
.guarantee-row {
    border:1px solid #f0ece0;
    border-radius:14px;
    overflow:hidden;
}
.guarantee-item {
    border-right:1px solid #f0ece0;
}
.guarantee-item:last-child { border-right:none; }
.guarantee-icon { font-size:1.1rem; color:var(--gold); }
.guarantee-text { font-size:0.7rem; color:#888; font-weight:600; }

/* ── SPECS TABLE ── */
.specs-card {
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 3px 18px rgba(0,0,0,0.06);
    margin-top:24px;
}
.specs-header {
    background:linear-gradient(135deg, #0d0d18, #1a1a35);
}
.specs-header span {
    font-size:0.82rem; font-weight:700; letter-spacing:1px;
    text-transform:uppercase; color:rgba(240,236,224,0.88);
}
.specs-header i { color:var(--gold); }
.spec-row {
    border-bottom:1px solid #f8f7f3;
}
.spec-row:last-child { border-bottom:none; }
.spec-label {
    width:140px; flex-shrink:0;
    font-size:0.8rem; color:#aaa; font-weight:500;
}
.spec-value {
    font-size:0.855rem; color:#1a1a2e; font-weight:600; flex:1;
}

/* ── DESCRIPTION ── */
.desc-card {
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 3px 18px rgba(0,0,0,0.06);
    margin-top:24px;
}
.desc-header {
    background:linear-gradient(135deg, #0d0d18, #1a1a35);
}
.desc-header span {
    font-size:0.82rem; font-weight:700; letter-spacing:1px;
    text-transform:uppercase; color:rgba(240,236,224,0.88);
}
.desc-header i { color:var(--gold); }
.desc-body {
    font-size:0.92rem; line-height:1.85; color:#444;
}

/* ── REVIEWS ── */
.reviews-card {
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 3px 18px rgba(0,0,0,0.06);
    margin-top:24px;
}
.reviews-header {
    background:linear-gradient(135deg, #0d0d18, #1a1a35);
}
.reviews-header span {
    font-size:0.82rem; font-weight:700; letter-spacing:1px;
    text-transform:uppercase; color:rgba(240,236,224,0.88);
}
.reviews-header i { color:var(--gold); }
.review-count-badge {
    min-width:22px; height:22px;
    background:linear-gradient(135deg, var(--gold), var(--gold-dark));
    color:#0a0a0f; font-size:0.72rem; font-weight:800;
    border-radius:6px;
}

/* Review item */
.review-item {
    border-bottom:1px solid #f8f7f3;
}
.review-item:last-child { border-bottom:none; }
.review-avatar {
    width:40px; height:40px; border-radius:12px;
    background:linear-gradient(135deg, var(--gold), var(--gold-dark));
    color:#0a0a0f; font-weight:800; font-size:0.95rem;
}
.review-name { font-weight:700; font-size:0.875rem; color:#1a1a2e; }
.review-time { font-size:0.75rem; color:#bbb; }
.review-stars i { font-size:11px; color:#f59e0b; }
.review-comment { font-size:0.855rem; color:#555; line-height:1.65; margin-top:6px; }

/* Review form */
.review-form-wrap {
    background:linear-gradient(135deg, rgba(201,168,76,0.04), rgba(201,168,76,0.01));
    border:1px solid rgba(201,168,76,0.15);
    border-radius:16px;
    padding:24px;
    margin-bottom:24px;
}
.star-rating-interactive i {
    font-size:1.6rem; color:#ddd;
    cursor:pointer; transition:all .15s;
}
.star-rating-interactive i:hover,
.star-rating-interactive i.filled { color:#f59e0b; transform:scale(1.1); }

.review-textarea {
    background:#fff;
    border:1.5px solid #ebe9e0;
    border-radius:12px;
    padding:12px 14px;
    font-size:0.875rem; color:#333;
    resize:none; width:100%;
    transition:border-color .2s;
}
.review-textarea:focus { outline:none; border-color:var(--gold); }
.review-textarea::placeholder { color:#bbb; }
.btn-submit-review {
    display:inline-flex; align-items:center; gap:7px;
    background:linear-gradient(135deg, var(--gold), var(--gold-dark));
    color:#0a0a0f; border:none;
    border-radius:10px; font-size:0.875rem; font-weight:800;
    padding:11px 24px; cursor:pointer;
    transition:all .25s;
    box-shadow:0 4px 14px rgba(201,168,76,0.3);
}
.btn-submit-review:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(201,168,76,0.45); }

.review-notice {
    background:rgba(201,168,76,0.06);
    border:1px solid rgba(201,168,76,0.18);
    border-radius:12px;
    font-size:0.875rem; color:#666;
}
.review-notice a { color:#a07c30; font-weight:700; text-decoration:none; }
.review-notice a:hover { color:var(--gold); }
.review-notice i { color:var(--gold); font-size:1.2rem; }

/* ── RELATED ── */
.related-section { padding:36px 0 56px; background:#f5f4f0; }
.related-title {
    font-family:'Playfair Display',serif;
    font-size:1.5rem; font-weight:700; color:#1a1a2e;
    margin-bottom:28px; padding-bottom:12px;
    border-bottom:3px solid; border-image:linear-gradient(90deg,var(--gold),transparent) 1;
    position:relative;
}
</style>
@endpush

@section('content')

{{-- ── TOP NAV BAR ── --}}
<div class="product-topbar">
    <div class="container">
        <div class="product-topbar-bc d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('home') }}">Trang chủ</a>
            <span>›</span>
            <a href="{{ route('products.index') }}">Sản phẩm</a>
            @if($product->category)
            <span>›</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}">
                {{ $product->category->name }}
            </a>
            @endif
            <span>›</span>
            <span class="current">{{ Str::limit($product->name, 40) }}</span>
        </div>
    </div>
</div>

{{-- ── MAIN PRODUCT ── --}}
<div class="product-main">
    <div class="container">
        <div class="row g-4">

            {{-- ── GALLERY ── --}}
            <div class="col-lg-5">
                <div class="gallery-wrap">
                    <div class="gallery-main">
                        <img id="mainImage"
                             src="{{ $product->thumbnail ? asset('storage/'.$product->thumbnail) : asset('images/no-image.png') }}"
                             alt="{{ $product->name }}"
                             decoding="async">
                    </div>

                    @if($product->images->count() > 0)
                    <div class="gallery-thumbs d-flex gap-2 flex-wrap">
                        @if($product->thumbnail)
                        <img src="{{ asset('storage/'.$product->thumbnail) }}"
                             class="gallery-thumb active" id="thumb-0"
                             onclick="switchImage(this.src, 'thumb-0')"
                             alt="Main"
                             loading="lazy" decoding="async">
                        @endif
                        @foreach($product->images as $k => $img)
                        <img src="{{ asset('storage/'.$img->image) }}"
                             class="gallery-thumb" id="thumb-{{ $k+1 }}"
                             onclick="switchImage(this.src, 'thumb-{{ $k+1 }}')"
                             alt="Gallery {{ $k+1 }}"
                             loading="lazy" decoding="async">
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── PRODUCT INFO ── --}}
            <div class="col-lg-7">
                <div class="product-info-card">

                    {{-- Brand --}}
                    @if($product->brand)
                    <div class="product-brand-pill d-inline-flex align-items-center gap-2 py-1 px-3 rounded-pill mb-3">
                        <i class="bi bi-award"></i>{{ $product->brand->name }}
                    </div>
                    @endif

                      {{-- Rating --}}
                    <div class="rating-row d-flex align-items-center gap-2 mb-3 flex-wrap">
                        <div class="stars-wrap d-flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $product->rating_avg ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="rating-score">{{ number_format($product->rating_avg, 1) }}/5</span>
                        <span style="color:#ddd;font-size:0.8rem;">({{ $product->rating_count }} đánh giá)</span>
                        <span class="rating-sep">|</span>
                        <span class="view-count"><i class="bi bi-eye me-1"></i>{{ number_format($product->view_count) }} lượt xem</span>
                    </div>

                    {{-- Price --}}
                    <div class="price-box d-flex align-items-center gap-3 flex-wrap p-3 mb-3">
                        @if($product->sale_price)
                            @php $discount = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                            <span class="price-current">{{ number_format($product->sale_price) }}đ</span>
                            <span class="price-original">{{ number_format($product->price) }}đ</span>
                            <span class="price-badge">-{{ $discount }}% OFF</span>
                        @else
                            <span class="price-current">{{ number_format($product->price) }}đ</span>
                        @endif
                    </div>

                    {{-- Stock --}}
                    <div class="stock-row mb-3">
                        @if($product->stock > 0)
                            <span class="stock-ok d-inline-flex align-items-center gap-2 py-1 px-3 rounded-pill">
                                <i class="bi bi-check-circle-fill"></i>
                                Còn hàng · {{ $product->stock }} sản phẩm
                            </span>
                        @else
                            <span class="stock-out d-inline-flex align-items-center gap-2 py-1 px-3 rounded-pill">
                                <i class="bi bi-x-circle-fill"></i>Hết hàng
                            </span>
                        @endif
                    </div>

                    {{-- Add to cart --}}
                    @if($product->stock > 0)
                    <form method="POST" action="{{ route('cart.add') }}" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="add-cart-row d-flex align-items-center gap-3 flex-wrap mb-3">
                            <div class="qty-control d-flex align-items-center">
                                <button type="button" class="qty-btn d-flex align-items-center justify-content-center" onclick="changeQty(-1)">−</button>
                                <input type="number" name="quantity" id="qty" value="1"
                                       min="1" max="{{ $product->stock }}" class="qty-input">
                                <button type="button" class="qty-btn d-flex align-items-center justify-content-center" onclick="changeQty(1)">+</button>
                            </div>
                            <button type="submit" class="btn-add-cart d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-bag-plus"></i>Thêm Vào Giỏ
                            </button>
                            <button type="submit" formaction="{{ route('cart.buy-now') }}" class="btn-buy-now d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-lightning-fill"></i>Mua Ngay
                            </button>
                            @auth
                            <button type="button" class="btn-wishlist-detail wishlist-btn d-flex align-items-center justify-content-center" data-id="{{ $product->id }}" title="Yêu thích">
                                <i class="bi bi-heart{{ auth()->user()->wishlists->contains('product_id', $product->id) ? '-fill text-danger' : '' }}"></i>
                            </button>
                            @endauth
                            @php
                                $inCompare = in_array($product->id, session('compare_products', []));
                            @endphp
                            <button type="button" class="btn-wishlist-detail compare-btn {{ $inCompare ? 'active' : '' }} d-flex align-items-center justify-content-center" data-id="{{ $product->id }}" title="So sánh sản phẩm">
                                <i class="bi bi-arrow-left-right {{ $inCompare ? 'text-warning' : '' }}"></i>
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="add-cart-row d-flex align-items-center gap-3 flex-wrap mb-3">
                        <button class="btn-add-cart d-flex align-items-center justify-content-center gap-2" disabled style="opacity: 0.6; cursor: not-allowed; background: #bbb; box-shadow: none;">
                            <i class="bi bi-x-circle"></i> Hết Hàng
                        </button>
                        @auth
                        <button type="button" class="btn-wishlist-detail wishlist-btn d-flex align-items-center justify-content-center" data-id="{{ $product->id }}" title="Yêu thích">
                            <i class="bi bi-heart{{ auth()->user()->wishlists->contains('product_id', $product->id) ? '-fill text-danger' : '' }}"></i>
                        </button>
                        @endauth
                        @php
                            $inCompare = in_array($product->id, session('compare_products', []));
                        @endphp
                        <button type="button" class="btn-wishlist-detail compare-btn {{ $inCompare ? 'active' : '' }} d-flex align-items-center justify-content-center" data-id="{{ $product->id }}" title="So sánh sản phẩm">
                            <i class="bi bi-arrow-left-right {{ $inCompare ? 'text-warning' : '' }}"></i>
                        </button>
                    </div>
                    @endif

                    {{-- Guarantees --}}
                    <div class="guarantee-row d-flex gap-0 mt-3">
                        <div class="guarantee-item flex-fill p-2 text-center d-flex flex-column align-items-center gap-1">
                            <span class="guarantee-icon"><i class="bi bi-shield-check"></i></span>
                            <span class="guarantee-text">Chính hãng 100%</span>
                        </div>
                        <div class="guarantee-item flex-fill p-2 text-center d-flex flex-column align-items-center gap-1">
                            <span class="guarantee-icon"><i class="bi bi-truck"></i></span>
                            <span class="guarantee-text">Ship miễn phí</span>
                        </div>
                        <div class="guarantee-item flex-fill p-2 text-center d-flex flex-column align-items-center gap-1">
                            <span class="guarantee-icon"><i class="bi bi-arrow-repeat"></i></span>
                            <span class="guarantee-text">Đổi trả 30 ngày</span>
                        </div>
                        <div class="guarantee-item flex-fill p-2 text-center d-flex flex-column align-items-center gap-1">
                            <span class="guarantee-icon"><i class="bi bi-headset"></i></span>
                            <span class="guarantee-text">Hỗ trợ 24/7</span>
                        </div>
                    </div>
                </div>

                {{-- Specs --}}
                @php
                $specs = [
                    ['Danh mục',       $product->category->name ?? null, 'bi-grid'],
                    ['Thương hiệu',    $product->brand->name ?? null,    'bi-award'],
                    ['Chất liệu vỏ',   $product->material,               'bi-circle'],
                    ['Chất liệu kính', $product->glass_material,         'bi-gem'],
                    ['Chất liệu dây',  $product->band_material,          'bi-link'],
                    ['Chống nước',     $product->water_resistance,       'bi-droplet'],
                    ['Bộ máy',         $product->movement,               'bi-gear'],
                    ['Kích thước',     $product->case_size,              'bi-arrows-angle-expand'],
                    ['Màu sắc',        $product->color,                  'bi-palette'],
                ];
                $specs = array_filter($specs, fn($s) => !empty($s[1]));
                @endphp
                @if(count($specs) > 0)
                <div class="specs-card">
                    <div class="specs-header d-flex align-items-center gap-2 py-3 px-4">
                        <i class="bi bi-list-check"></i>
                        <span>Thông Số Kỹ Thuật</span>
                    </div>
                    <div class="specs-table py-1">
                        @foreach($specs as [$label, $val, $icon])
                        <div class="spec-row d-flex align-items-baseline gap-3 py-2 px-4">
                            <span class="spec-label"><i class="bi {{ $icon }} me-2" style="color:var(--gold);"></i>{{ $label }}</span>
                            <span class="spec-value">{{ $val }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ── DESCRIPTION ── --}}
        @if($product->description)
        <div class="desc-card">
            <div class="desc-header d-flex align-items-center gap-2 py-3 px-4">
                <i class="bi bi-file-text"></i>
                <span>Mô Tả Sản Phẩm</span>
            </div>
            <div class="desc-body p-4">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
        @endif

        {{-- ── REVIEWS ── --}}
        <div class="reviews-card">
            <div class="reviews-header d-flex align-items-center gap-2 py-3 px-4">
                <i class="bi bi-star"></i>
                <span>Đánh Giá Sản Phẩm</span>
                <span class="review-count-badge d-inline-flex align-items-center justify-content-center px-2 ms-1">{{ $product->rating_count }}</span>
            </div>
            <div class="reviews-body p-4">
                {{-- AI Review Summary --}}
                @if($product->ai_description)
                <div class="p-3 mb-4 rounded-3" style="background: linear-gradient(135deg, rgba(201,168,76,0.06), rgba(201,168,76,0.01)); border: 1px solid rgba(201,168,76,0.18);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-stars text-warning fs-5"></i>
                        <strong style="font-family:'Playfair Display',serif; font-size: 0.95rem; color: #1a1a2e;">Tóm tắt đánh giá bởi AI</strong>
                    </div>
                    <p class="mb-0 text-muted" style="font-size: 0.855rem; line-height: 1.65; font-style: italic;">
                        {{ $product->ai_description }}
                    </p>
                </div>
                @endif

                {{-- Write review --}}
                @if($canReview)
                <div class="review-form-wrap">
                    <p style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:#1a1a2e;margin-bottom:14px;">
                        <i class="bi bi-pencil-square me-2" style="color:var(--gold);"></i>Viết Đánh Giá
                    </p>
                    <form method="POST" action="{{ route('reviews.store') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div style="margin-bottom:14px;">
                            <label style="font-size:0.8rem;font-weight:700;color:#888;letter-spacing:.8px;text-transform:uppercase;margin-bottom:4px;display:block;">Chọn sao</label>
                            <div class="star-rating-interactive d-flex gap-1 my-2" id="starRating">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star" data-value="{{ $i }}"
                                   onmouseover="highlightStars({{ $i }})"
                                   onmouseout="resetStars()"
                                   onclick="selectStar({{ $i }})"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="0">
                        </div>
                        <div style="margin-bottom:14px;">
                            <label style="font-size:0.8rem;font-weight:700;color:#888;letter-spacing:.8px;text-transform:uppercase;margin-bottom:6px;display:block;">Nhận xét</label>
                            <textarea name="comment" rows="3" class="review-textarea"
                                      placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."></textarea>
                        </div>
                        <button type="submit" class="btn-submit-review">
                            <i class="bi bi-send-fill"></i>Gửi Đánh Giá
                        </button>
                    </form>
                </div>

                @elseif($hasReview)
                <div class="review-notice d-flex align-items-center gap-2 p-3 mb-3">
                    <i class="bi bi-patch-check-fill" style="color:#22c55e;"></i>
                    <span>Bạn đã đánh giá sản phẩm này rồi! Cảm ơn phản hồi của bạn.</span>
                </div>
                @elseif(auth()->check())
                <div class="review-notice d-flex align-items-center gap-2 p-3 mb-3">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Bạn cần <strong>mua và nhận hàng thành công</strong> để có thể đánh giá sản phẩm!</span>
                </div>
                @else
                <div class="review-notice d-flex align-items-center gap-2 p-3 mb-3">
                    <i class="bi bi-lock-fill"></i>
                    <span><a href="{{ route('login') }}">Đăng nhập</a> để đánh giá sản phẩm này!</span>
                </div>
                @endif

                {{-- Review list --}}
                @forelse($product->reviews as $review)
                <div class="review-item d-flex gap-3 py-3">
                    <div class="review-avatar d-flex align-items-center justify-content-center flex-shrink-0">
                        {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div style="flex:1;">
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:4px;">
                            <span class="review-name">{{ $review->user->name ?? 'Ẩn danh' }}</span>
                            <div class="review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="review-time">{{ $review->created_at->diffForHumans() }}</span>

                            @if($review->sentiment === 'positive')
                                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.7rem; padding: 2px 6px;">😊 Tích cực</span>
                            @elseif($review->sentiment === 'negative')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.7rem; padding: 2px 6px;">😠 Tiêu cực</span>
                            @endif
                        </div>
                        @if($review->comment)
                        <p class="review-comment">{{ $review->comment }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:32px 0;color:#bbb;">
                    <i class="bi bi-star" style="font-size:2rem;color:rgba(201,168,76,0.3);display:block;margin-bottom:10px;"></i>
                    <span style="font-size:0.875rem;">Chưa có đánh giá nào. Hãy là người đầu tiên!</span>
                </div>
                @endforelse
            </div>
        </div>

    </div>{{-- /container --}}
</div>{{-- /product-main --}}

{{-- ── RELATED PRODUCTS ── --}}
@if($related->count() > 0)
<div class="related-section">
    <div class="container">
        <h2 class="related-title">Sản Phẩm Liên Quan</h2>
        <div class="row g-3">
            @foreach($related as $item)
                @include('customer.partials.product-card', ['product' => $item])
            @endforeach
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
function switchImage(src, id) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    document.getElementById(id)?.classList.add('active');
}
function changeMainImage(src) { document.getElementById('mainImage').src = src; }
function changeQty(delta) {
    const input = document.getElementById('qty');
    const val = parseInt(input.value) + delta;
    const max = parseInt(input.max);
    if (val >= 1 && val <= max) input.value = val;
}
let selectedRating = 0;
function highlightStars(n) {
    document.querySelectorAll('#starRating i').forEach((el, i) => {
        el.className = i < n ? 'bi bi-star-fill filled' : 'bi bi-star';
    });
}
function resetStars() { highlightStars(selectedRating); }
function selectStar(n) {
    selectedRating = n;
    document.getElementById('ratingInput').value = n;
    highlightStars(n);
}

</script>
@endpush

@endsection