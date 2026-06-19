@extends('layouts.customer')
@section('title', 'Tất Cả Sản Phẩm')

@push('styles')
<style>
/* ── PAGE HERO ── */
.shop-hero {
    background: linear-gradient(135deg, #0a0a0f, #111128);
    padding: 44px 0 36px;
    border-bottom: 1px solid rgba(201,168,76,0.12);
    position: relative;
    overflow: hidden;
}
.shop-hero::before {
    content:'';
    position:absolute;
    inset:0;
    background: radial-gradient(ellipse at 15% 70%, rgba(201,168,76,0.08), transparent 55%),
                radial-gradient(ellipse at 85% 25%, rgba(99,60,150,0.07), transparent 50%);
    pointer-events:none;
}
.shop-hero-label {
    font-size:0.72rem; font-weight:700; letter-spacing:2px; text-transform:uppercase;
    color:var(--gold); background:rgba(201,168,76,0.1);
    border:1px solid rgba(201,168,76,0.25);
}
.shop-hero h1 {
    font-family:'Playfair Display',serif;
    font-size:2.2rem; font-weight:700; color:#fff; margin-bottom:6px;
}
.shop-hero h1 span { color:var(--gold); }
.shop-hero-bc a  { color:rgba(201,168,76,0.65); font-size:0.8rem; text-decoration:none; }
.shop-hero-bc a:hover { color:var(--gold); }
.shop-hero-bc span { color:rgba(240,236,224,0.35); font-size:0.8rem; margin:0 6px; }

/* ── SIDEBAR ── */
.filter-sidebar { position:sticky; top:80px; }

.filter-card {
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 3px 20px rgba(0,0,0,0.07);
    margin-bottom:16px;
    border:1px solid #f0ece0;
}
.filter-card-header {
    background:linear-gradient(135deg, #0d0d18, #1a1a35);
    border-bottom:1px solid rgba(201,168,76,0.15);
}
.filter-card-header span {
    font-size:0.78rem; font-weight:700; letter-spacing:1px;
    text-transform:uppercase; color:rgba(240,236,224,0.88);
}
.filter-card-header i { color:var(--gold); font-size:0.85rem; }

/* Category links */
.filter-cat-link {
    padding:8px 10px;
    border-radius:10px;
    font-size:0.84rem;
    color:#555;
    text-decoration:none;
    transition:all 0.2s;
    font-weight:500;
}
.filter-cat-link:hover { background:rgba(201,168,76,0.07); color:#a07c30; }
.filter-cat-link.active {
    background:rgba(201,168,76,0.1);
    color:#a07c30;
    font-weight:700;
    border-left:2.5px solid var(--gold);
}
.filter-cat-link .dot {
    width:6px; height:6px; border-radius:50%;
    background:rgba(201,168,76,0.35); flex-shrink:0;
}
.filter-cat-link.active .dot { background:var(--gold); }
.filter-child-link { padding-left:28px; font-size:0.8rem; }

/* Brand checkboxes */
.filter-brand-item {
    padding:7px 0;
    border-bottom:1px solid #f8f7f3;
    cursor:pointer;
}
.filter-brand-item:last-child { border-bottom:none; }
.filter-brand-radio {
    appearance:none;
    width:16px; height:16px;
    border-radius:50%;
    border:2px solid #ddd;
    flex-shrink:0;
    transition:all 0.2s;
    cursor:pointer;
    position:relative;
}
.filter-brand-radio:checked {
    border-color:var(--gold);
    background:var(--gold);
    box-shadow:0 0 0 2px rgba(201,168,76,0.2);
}
.filter-brand-label {
    font-size:0.84rem; color:#444; cursor:pointer; flex:1;
    transition:color 0.2s;
}
.filter-brand-item:hover .filter-brand-label { color:#a07c30; }

/* Price inputs */
.price-input {
    background:#f8f7f3;
    border:1.5px solid #ebe9e0;
    border-radius:10px;
    padding:8px 12px;
    font-size:0.82rem;
    color:#333;
    width:100%;
    transition:border-color 0.2s;
}
.price-input:focus { outline:none; border-color:var(--gold); }
.btn-apply-filter {
    width:100%; padding:9px;
    background:linear-gradient(135deg, #1a1a2e, #0d0d18);
    color:#fff;
    border:none; border-radius:10px;
    font-size:0.82rem; font-weight:700;
    cursor:pointer; transition:all 0.25s;
    margin-top:10px;
}
.btn-apply-filter:hover { background:linear-gradient(135deg, var(--gold), var(--gold-dark)); color:#0a0a0f; }

.btn-clear-filter {
    width:100%; padding:8px;
    background:transparent;
    color:#e94560;
    border:1.5px solid rgba(233,69,96,0.3);
    border-radius:10px;
    font-size:0.8rem; font-weight:600;
    cursor:pointer; transition:all 0.2s;
}
.btn-clear-filter:hover { background:rgba(233,69,96,0.06); border-color:#e94560; }

/* Active filters pills */
.filter-pill {
    background:rgba(201,168,76,0.1);
    border:1px solid rgba(201,168,76,0.25);
    color:#a07c30;
    font-size:0.75rem; font-weight:600;
}
.filter-pill i { font-size:0.7rem; }

/* ── TOOLBAR ── */
.shop-toolbar {
    background:#fff;
    border-radius:14px;
    box-shadow:0 2px 12px rgba(0,0,0,0.06);
    border:1px solid #f0ece0;
}
.toolbar-count {
    font-size:0.84rem; color:#888;
}
.toolbar-count strong { color:#1a1a2e; font-weight:700; }

.sort-select {
    appearance:none;
    background:#f8f7f3 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23999'/%3E%3C/svg%3E") no-repeat right 12px center;
    border:1.5px solid #ebe9e0;
    border-radius:10px;
    padding:7px 36px 7px 12px;
    font-size:0.82rem; color:#444;
    cursor:pointer; transition:border-color 0.2s;
    min-width:160px;
}
.sort-select:focus { outline:none; border-color:var(--gold); }

/* ── EMPTY STATE ── */
.empty-products {
    text-align:center; padding:72px 20px;
}
.empty-products-icon {
    width:88px; height:88px;
    border-radius:24px;
    background:rgba(201,168,76,0.07);
    border:1px solid rgba(201,168,76,0.15);
    font-size:2.5rem; margin:0 auto 20px;
}
.empty-products h5 {
    font-family:'Playfair Display',serif;
    color:#1a1a2e; font-size:1.2rem; margin-bottom:8px;
}
.btn-reset-shop {
    background:linear-gradient(135deg, #1a1a2e, #0d0d18);
    color:#fff; border:none; border-radius:12px;
    font-size:0.875rem; font-weight:700;
    padding:11px 24px; cursor:pointer;
    text-decoration:none; transition:all 0.25s;
    box-shadow:0 4px 16px rgba(0,0,0,0.15);
}
.btn-reset-shop:hover {
    background:linear-gradient(135deg, var(--gold), var(--gold-dark));
    color:#0a0a0f;
}

/* ── PAGINATION ── */
.shop-pagination .pagination { gap:4px; }
.shop-pagination .page-link {
    border:1.5px solid #ebe9e0;
    border-radius:10px !important;
    color:#555; font-size:0.84rem;
    font-weight:500; padding:8px 14px;
    transition:all 0.2s;
}
.shop-pagination .page-link:hover { background:var(--gold); border-color:var(--gold); color:#0a0a0f; }
.shop-pagination .page-item.active .page-link {
    background:linear-gradient(135deg, var(--gold), var(--gold-dark));
    border-color:var(--gold); color:#0a0a0f; font-weight:800;
    box-shadow:0 4px 14px rgba(201,168,76,0.3);
}
.shop-pagination .page-item.disabled .page-link { opacity:.35; }
</style>
@endpush

@section('content')

{{-- ════════════ SHOP HERO ════════════ --}}
<div class="shop-hero">
    <div class="container position-relative">
        <div class="shop-hero-label d-inline-flex align-items-center gap-1.5 py-1 px-3 rounded-pill mb-3">
            <i class="bi bi-watch"></i> Bộ Sưu Tập
        </div>
        <h1 class="display-6 fw-bold">Khám Phá <span>Đồng Hồ</span></h1>
        <nav class="shop-hero-bc d-flex align-items-center flex-wrap gap-1 mt-2">
            <a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a>
            <span>›</span>
            <span style="color:rgba(240,236,224,0.55);">Sản phẩm</span>
            @if(request('category'))
                <span>›</span>
                <span style="color:rgba(240,236,224,0.55);">{{ request('category') }}</span>
            @endif
        </nav>
    </div>
</div>

{{-- ════════════ MAIN CONTENT ════════════ --}}
<div style="background:#f5f4f0; padding:44px 0 72px; min-height:60vh;">
    <div class="container">
        <div class="row g-4">

            {{-- ────── SIDEBAR ────── --}}
            <div class="col-lg-3">
                <div class="filter-sidebar position-sticky" style="top:100px; z-index:10;">
                    <form method="GET" action="{{ route('products.index') }}" id="filterForm">

                        {{-- Active filter pills --}}
                        @if(request()->hasAny(['category','brand','price_min','price_max']))
                        <div class="active-filters d-flex flex-wrap gap-2 mb-3">
                            @if(request('category'))
                                <span class="filter-pill d-inline-flex align-items-center gap-1 py-1.5 px-2.5 rounded-pill"><i class="bi bi-grid"></i>{{ request('category') }}</span>
                            @endif
                            @if(request('brand'))
                                <span class="filter-pill d-inline-flex align-items-center gap-1 py-1.5 px-2.5 rounded-pill"><i class="bi bi-tag"></i>{{ request('brand') }}</span>
                            @endif
                            @if(request('price_min') || request('price_max'))
                                <span class="filter-pill d-inline-flex align-items-center gap-1 py-1.5 px-2.5 rounded-pill"><i class="bi bi-cash"></i>Giá: {{ number_format(request('price_min',0)) }} – {{ request('price_max') ? number_format(request('price_max')) : '∞' }}</span>
                            @endif
                        </div>
                        @endif

                        {{-- Danh mục --}}
                        <div class="filter-card">
                            <div class="filter-card-header px-3 py-2.5 d-flex align-items-center gap-2">
                                <i class="bi bi-grid-3x3-gap"></i>
                                <span>Danh Mục</span>
                            </div>
                            <div class="filter-card-body p-3 d-flex flex-column gap-1">
                                <a href="{{ route('products.index', array_merge(request()->except('category'), [])) }}"
                                   class="filter-cat-link d-flex align-items-center gap-2 text-decoration-none {{ !request('category') ? 'active' : '' }}">
                                    <span class="dot"></span>Tất cả sản phẩm
                                </a>
                                @foreach($categories as $cat)
                                <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $cat->slug])) }}"
                                   class="filter-cat-link d-flex align-items-center gap-2 text-decoration-none {{ request('category') === $cat->slug ? 'active' : '' }}">
                                    <span class="dot"></span>{{ $cat->name }}
                                </a>
                                @foreach($cat->children as $child)
                                <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $child->slug])) }}"
                                   class="filter-cat-link filter-child-link d-flex align-items-center gap-2 text-decoration-none {{ request('category') === $child->slug ? 'active' : '' }}">
                                    <span class="dot" style="width:4px;height:4px;"></span>{{ $child->name }}
                                </a>
                                @endforeach
                                @endforeach
                            </div>
                        </div>

                        {{-- Thương hiệu --}}
                        <div class="filter-card">
                            <div class="filter-card-header px-3 py-2.5 d-flex align-items-center gap-2">
                                <i class="bi bi-award"></i>
                                <span>Thương Hiệu</span>
                            </div>
                            <div class="filter-card-body p-3 d-flex flex-column gap-1">
                                @foreach($brands as $brand)
                                <label class="filter-brand-item d-flex align-items-center gap-2 w-100">
                                    <input class="filter-brand-radio" type="radio" name="brand"
                                           value="{{ $brand->slug }}"
                                           {{ request('brand') === $brand->slug ? 'checked' : '' }}
                                           onchange="document.getElementById('filterForm').submit()">
                                    <span class="filter-brand-label">{{ $brand->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Khoảng giá --}}
                        <div class="filter-card">
                            <div class="filter-card-header px-3 py-2.5 d-flex align-items-center gap-2">
                                <i class="bi bi-cash-coin"></i>
                                <span>Khoảng Giá</span>
                            </div>
                            <div class="filter-card-body p-3">
                                <div class="d-flex gap-2 mb-3">
                                    <input type="number" name="price_min" class="price-input"
                                           placeholder="Từ (đ)" value="{{ request('price_min') }}">
                                    <input type="number" name="price_max" class="price-input"
                                           placeholder="Đến (đ)" value="{{ request('price_max') }}">
                                </div>
                                <button type="submit" class="btn-apply-filter d-flex align-items-center justify-content-center gap-1">
                                    <i class="bi bi-funnel me-1"></i>Áp dụng
                                </button>
                            </div>
                        </div>

                        {{-- Xoá bộ lọc --}}
                        @if(request()->hasAny(['category','brand','price_min','price_max']))
                        <a href="{{ route('products.index') }}" class="btn-clear-filter d-flex align-items-center justify-content-center gap-1 mt-2 py-2 px-3 text-center text-decoration-none">
                            <i class="bi bi-x-circle me-1"></i>Xoá tất cả bộ lọc
                        </a>
                        @endif

                    </form>
                </div>
            </div>

            {{-- ────── PRODUCT GRID ────── --}}
            <div class="col-lg-9">

                {{-- Toolbar --}}
                <div class="shop-toolbar px-3 py-2.5 mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3">
                    <p class="toolbar-count mb-0">
                        Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm
                        @if(request('category')) trong <strong>{{ request('category') }}</strong>@endif
                    </p>
                    <select class="sort-select" onchange="window.location=this.value">
                        <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'newest'])) }}"
                                {{ request('sort','newest') === 'newest' ? 'selected' : '' }}>
                            🕐 Mới nhất
                        </option>
                        <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price_asc'])) }}"
                                {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                            💰 Giá tăng dần
                        </option>
                        <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price_desc'])) }}"
                                {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                            💰 Giá giảm dần
                        </option>
                        <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'popular'])) }}"
                                {{ request('sort') === 'popular' ? 'selected' : '' }}>
                            🔥 Phổ biến nhất
                        </option>
                        <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'rating'])) }}"
                                {{ request('sort') === 'rating' ? 'selected' : '' }}>
                            ⭐ Đánh giá cao
                        </option>
                    </select>
                </div>

                {{-- Grid --}}
                <div class="row g-3 mb-4">
                    @forelse($products as $product)
                        @include('customer.partials.product-card', ['product' => $product])
                    @empty
                    <div class="col-12">
                        <div class="empty-products d-flex flex-column align-items-center justify-content-center">
                            <div class="empty-products-icon d-flex align-items-center justify-content-center">🔍</div>
                            <h5>Không tìm thấy sản phẩm</h5>
                            <p class="text-muted small mb-3">
                                Thử điều chỉnh bộ lọc hoặc từ khoá tìm kiếm
                            </p>
                            <a href="{{ route('products.index') }}" class="btn-reset-shop d-inline-flex align-items-center gap-2">
                                <i class="bi bi-arrow-counterclockwise"></i>Xem tất cả sản phẩm
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                <div class="shop-pagination d-flex justify-content-center">
                    <ul class="pagination mb-0">
                        @if($products->onFirstPage())
                            <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i></span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a></li>
                        @endif

                        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            <li class="page-item {{ $products->currentPage() === $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        @if($products->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right"></i></span></li>
                        @endif
                    </ul>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection