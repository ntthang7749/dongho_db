{{-- ===============================================
     PRODUCT CARD PARTIAL — dùng trong listing/home
     Nhận: $product
=============================================== --}}
<div class="col-6 col-lg-3">
    <div class="pcard card h-100 border-0 shadow-sm overflow-hidden d-flex flex-column">

        {{-- ── IMAGE WRAP ── --}}
        <div class="pcard-img-wrap position-relative overflow-hidden flex-shrink-0 bg-light ratio ratio-1x1">
            <a href="{{ route('products.show', $product->slug) }}" class="d-block w-100 h-100">
                <img src="{{ img_url($product->thumbnail) }}"
                      class="pcard-img w-100 h-100 object-fit-cover d-block" alt="{{ $product->name }}"
                      loading="lazy" decoding="async">
                {{-- Hover overlay --}}
                <div class="pcard-overlay position-absolute start-0 top-0 w-100 h-100 d-flex align-items-center justify-content-center">
                    <span class="pcard-quick-view text-dark fw-bold rounded-pill shadow-sm">
                        <i class="bi bi-eye me-1"></i>Xem nhanh
                    </span>
                </div>
            </a>

            {{-- Badge giảm giá --}}
            @if($product->sale_price)
                @php $discount = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                <span class="pcard-badge-sale position-absolute top-0 start-0 m-2 badge bg-danger text-white rounded-2 fw-bold w-auto h-auto">-{{ $discount }}%</span>
            @endif

            @if(!$product->sale_price && $product->created_at->gt(now()->subDays(14)))
                <span class="pcard-badge-new position-absolute top-0 start-0 m-2 badge rounded-2 fw-bold w-auto h-auto">Mới</span>
            @endif

            {{-- Wishlist --}}
            @auth
            <button class="pcard-wish wishlist-btn position-absolute rounded-circle border-0 d-flex align-items-center justify-content-center shadow-sm" data-id="{{ $product->id }}" title="Yêu thích">
                <i class="bi bi-heart{{ auth()->user()->wishlists->contains('product_id', $product->id) ? '-fill text-danger' : '' }} small"></i>
            </button>
            @endauth

            {{-- Compare --}}
            @php
                $inCompare = in_array($product->id, session('compare_products', []));
            @endphp
            <button class="pcard-compare compare-btn position-absolute rounded-circle border-0 d-flex align-items-center justify-content-center shadow-sm {{ $inCompare ? 'active' : '' }}" data-id="{{ $product->id }}" title="So sánh sản phẩm">
                <i class="bi bi-arrow-left-right small {{ $inCompare ? 'text-warning' : '' }}"></i>
            </button>
        </div>

        {{-- ── BODY ── --}}
        <div class="pcard-body p-3 pb-2 flex-grow-1 d-flex flex-column gap-1">
            {{-- Brand --}}
            @if($product->brand)
            <span class="pcard-brand small fw-bold text-uppercase">{{ $product->brand->name }}</span>
            @endif

            {{-- Name --}}
            <a href="{{ route('products.show', $product->slug) }}" class="pcard-name text-dark fw-semibold text-decoration-none flex-grow-1">
                {{ $product->name }}
            </a>

            {{-- Stars --}}
            <div class="d-flex align-items-center gap-1 my-1">
                <div>
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $product->rating_avg ? '-fill' : ($i - 0.5 <= $product->rating_avg ? '-half' : '') }}"
                           style="color:#f59e0b;font-size:11px;"></i>
                    @endfor
                </div>
                <span class="text-muted ms-1" style="font-size: 0.7rem;">( {{ $product->rating_count }} )</span>
            </div>

            {{-- Price --}}
            <div class="d-flex align-items-baseline gap-2 flex-wrap">
                @if($product->sale_price)
                    <span class="fs-6 fw-extrabold text-danger">{{ number_format($product->sale_price) }}đ</span>
                    <span class="text-muted text-decoration-line-through small">{{ number_format($product->price) }}đ</span>
                @else
                    <span class="fs-6 fw-extrabold text-danger">{{ number_format($product->price) }}đ</span>
                @endif
            </div>
        </div>

        {{-- ── FOOTER ── --}}
        <div class="pcard-footer p-3 pt-0 bg-transparent border-0 d-flex">
            @if($product->stock > 0)
                <form method="POST" action="{{ route('cart.add') }}" class="add-to-cart-form w-100">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="pcard-btn-cart btn btn-dark w-100 fw-bold py-2 rounded-3 border-0">
                        <i class="bi bi-bag-plus me-1"></i>Thêm vào giỏ
                    </button>
                </form>
            @else
                <button class="btn btn-light w-100 fw-semibold py-2 rounded-3 text-muted disabled" disabled>
                    <i class="bi bi-x-circle me-1"></i>Hết hàng
                </button>
            @endif
        </div>

    </div>
</div>

@once
@push('styles')
<style>
/* ─────────────────────────────────────────
   PRODUCT CARD  (pcard)
   ───────────────────────────────────────── */
.pcard {
    transition: transform 0.32s cubic-bezier(.4,0,.2,1),
                box-shadow 0.32s cubic-bezier(.4,0,.2,1),
                border-color 0.32s cubic-bezier(.4,0,.2,1);
    border: 1px solid transparent !important;
}
.pcard:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 48px rgba(0,0,0,0.13) !important;
    border-color: rgba(201,168,76,0.2) !important;
}

.pcard-img {
    transition: transform 0.45s ease;
}
.pcard:hover .pcard-img { 
    transform: scale(1.07); 
}

/* Overlay */
.pcard-overlay {
    background: rgba(10,10,15,0.38);
    opacity: 0;
    transition: opacity 0.3s;
}
.pcard:hover .pcard-overlay { 
    opacity: 1; 
}
.pcard-quick-view {
    background: rgba(255,255,255,0.92);
    font-size: 0.78rem;
    padding: 8px 18px;
    backdrop-filter: blur(6px);
    letter-spacing: 0.3px;
    transform: translateY(8px);
    transition: transform 0.3s;
}
.pcard:hover .pcard-quick-view { 
    transform: translateY(0); 
}

/* Badges */
.pcard-badge-sale,
.pcard-badge-new {
    font-size: 10px;
    letter-spacing: 0.5px;
    padding: 4px 9px;
    z-index: 2;
    width: auto !important;
    height: auto !important;
}
.pcard-badge-new {
    background: linear-gradient(135deg, #c9a84c, #a07c30) !important;
    color: #0a0a0f !important;
}

/* Buttons (Wishlist & Compare) */
.pcard-wish, .pcard-compare {
    right: 8px;
    width: 34px !important; 
    height: 34px !important;
    background: rgba(255,255,255,0.9);
    cursor: pointer;
    transition: all 0.25s;
    z-index: 3;
    opacity: 0;
}
.pcard-wish { top: 8px; }
.pcard-compare { top: 48px; }

.pcard:hover .pcard-wish, 
.pcard:hover .pcard-compare { 
    opacity: 1; 
}

.pcard-wish:hover, 
.pcard-compare:hover {
    background: #fff;
    transform: scale(1.12);
    box-shadow: 0 4px 14px rgba(0,0,0,0.18) !important;
}

/* Brand */
.pcard-brand {
    font-size: 0.7rem;
    color: #c9a84c;
    letter-spacing: 0.8px;
}

/* Name */
.pcard-name {
    font-size: 0.875rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s;
}
.pcard-name:hover { 
    color: #a07c30 !important; 
}

/* Price */
.fw-extrabold {
    font-weight: 800;
}

/* Cart Button */
.pcard-btn-cart {
    background: #1a1a2e;
    font-size: 0.8rem;
    transition: all 0.25s;
    letter-spacing: 0.2px;
}
.pcard-btn-cart:hover {
    background: linear-gradient(135deg, #c9a84c, #a07c30) !important;
    color: #0a0a0f !important;
    box-shadow: 0 4px 14px rgba(201,168,76,0.35);
}
</style>
@endpush
@endonce