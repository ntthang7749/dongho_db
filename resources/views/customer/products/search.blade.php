@extends('layouts.customer')
@section('title', 'Tìm kiếm: ' . $q)

@push('styles')
<style>
    .search-hero {
        background: linear-gradient(135deg, #0a0a0f, #111128);
        border-bottom: 1px solid rgba(201,168,76,0.15);
    }
    .search-hero::before {
        content:'';
        position:absolute;
        inset:0;
        background: radial-gradient(ellipse at 10% 80%, rgba(201,168,76,0.08), transparent 55%),
                    radial-gradient(ellipse at 90% 20%, rgba(99,60,150,0.07), transparent 50%);
    }
    .search-hero h1 {
        font-family:'Playfair Display',serif;
    }
    .search-label {
        letter-spacing: 2px;
        background: rgba(201,168,76,0.1);
        border: 1px solid rgba(201,168,76,0.25);
    }
    .bg-gold-transparent {
        background: rgba(201,168,76,0.08);
    }

    /* Re-search form */
    .search-refine-input {
        background: rgba(255,255,255,0.08);
        border: 1.5px solid rgba(201,168,76,0.25);
        outline: none;
        transition: border-color .2s;
    }
    .search-refine-input::placeholder { color: rgba(240,236,224,0.35); }
    .search-refine-input:focus { 
        border-color: var(--gold); 
        background: rgba(255,255,255,0.12);
        box-shadow: none;
    }
    .search-refine-btn {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f !important;
        transition: all .25s;
    }
    .search-refine-btn:hover { 
        background: linear-gradient(135deg, var(--gold-light), var(--gold)); 
    }

    /* Empty search suggestions */
    .suggestion-pill {
        background: #fdfdfb; 
        border: 1.5px solid #ebe9e0;
        transition: all 0.2s;
    }
    .suggestion-pill:hover {
        background: var(--gold) !important; 
        border-color: var(--gold) !important;
        color: #0a0a0f !important;
    }

    .sort-select-custom:focus {
        border-color: var(--gold);
        box-shadow: none;
    }
    
    .btn-gold-action {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f !important;
        transition: all 0.25s;
        box-shadow: 0 4px 14px rgba(201,168,76,0.25);
    }
    .btn-gold-action:hover {
        background: linear-gradient(135deg, var(--gold-light), var(--gold));
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(201, 168, 76, 0.4);
    }
</style>
@endpush

@section('content')

{{-- ════════ SEARCH HERO ════════ --}}
<div class="search-hero py-5 position-relative overflow-hidden text-white">
    <div class="container position-relative">
        <nav class="breadcrumb d-flex align-items-center mb-3 small">
            <a href="{{ route('home') }}" class="text-white-50 text-decoration-none hover-gold"><i class="bi bi-house me-1"></i>Trang chủ</a>
            <span class="text-white-50 mx-2 small">/</span>
            <a href="{{ route('products.index') }}" class="text-white-50 text-decoration-none hover-gold">Sản phẩm</a>
            <span class="text-white-50 mx-2 small">/</span>
            <span class="text-white-50 opacity-75">Tìm kiếm</span>
        </nav>

        <div class="search-label d-inline-flex align-items-center gap-2 rounded-pill px-3 py-1 fw-bold text-uppercase text-gold small mb-3">
            <i class="bi bi-search text-gold"></i> KẾT QUẢ TÌM KIẾM
        </div>
        <h1 class="fs-2 fw-bold mb-2">Tìm kiếm: <span class="keyword text-gold bg-gold-transparent rounded px-2 d-inline-block">{{ $q }}</span></h1>
        <div class="d-inline-flex align-items-center gap-2 small text-white-50 mt-2">
            <i class="bi bi-grid-3x3-gap text-gold"></i>
            Tìm thấy <strong class="text-gold">{{ $products->total() }}</strong> sản phẩm phù hợp
        </div>

        {{-- Re-search --}}
        <form class="d-flex w-100 mt-4" style="max-width:480px;" action="{{ route('products.search') }}" method="GET">
            <input class="search-refine-input form-control border-end-0 rounded-start-3 rounded-end-0 px-3 py-2 text-white bg-transparent" type="search" name="q"
                   value="{{ $q }}" placeholder="Tìm kiếm sản phẩm khác..." autocomplete="off">
            <button class="search-refine-btn btn rounded-end-3 rounded-start-0 px-4 py-2 fw-bold border-0" type="submit">
                <i class="bi bi-search me-1"></i> Tìm
            </button>
        </form>
    </div>
</div>

{{-- ════════ RESULTS ════════ --}}
<div class="bg-light py-5" style="min-height:50vh;">
    <div class="container">

        @if($products->count() > 0)

        {{-- Toolbar --}}
        <div class="d-flex align-items-center justify-content-between bg-white rounded-4 p-3 shadow-sm border mb-4 flex-wrap gap-2" style="border: 1px solid rgba(0,0,0,0.05) !important;">
            <p class="mb-0 small text-muted">
                Hiển thị <strong class="text-dark">{{ $products->firstItem() }}–{{ $products->lastItem() }}</strong>
                trong <strong class="text-dark">{{ $products->total() }}</strong> kết quả
                cho "<strong style="color:var(--gold-dark);">{{ $q }}</strong>"
            </p>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.8px;">Sắp xếp</span>
                <select class="form-select sort-select-custom rounded-3 border-light small" style="min-width: 170px;" onchange="window.location=this.value">
                    <option value="{{ route('products.search', ['q' => $q, 'sort' => 'newest']) }}"
                            {{ request('sort','newest') === 'newest' ? 'selected' : '' }}>🕐 Mới nhất</option>
                    <option value="{{ route('products.search', ['q' => $q, 'sort' => 'price_asc']) }}"
                            {{ request('sort') === 'price_asc' ? 'selected' : '' }}>💰 Giá tăng dần</option>
                    <option value="{{ route('products.search', ['q' => $q, 'sort' => 'price_desc']) }}"
                            {{ request('sort') === 'price_desc' ? 'selected' : '' }}>💰 Giá giảm dần</option>
                    <option value="{{ route('products.search', ['q' => $q, 'sort' => 'popular']) }}"
                            {{ request('sort') === 'popular' ? 'selected' : '' }}>🔥 Phổ biến nhất</option>
                </select>
            </div>
        </div>

        {{-- Product grid --}}
        <div class="row g-3">
            @foreach($products as $product)
                @include('customer.partials.product-card', ['product' => $product])
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="shop-pagination d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
        @endif

        @else
        {{-- Empty state --}}
        <div class="empty-search text-center p-5 bg-white rounded-4 shadow-sm border mb-5 border-light">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-gold mx-auto mb-4 fs-2" style="width: 80px; height: 80px;">
                <i class="bi bi-search"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2" style="font-family:'Playfair Display',serif;">Không tìm thấy "{{ $q }}"</h5>
            <p class="text-muted small mx-auto mb-4" style="max-width:380px;">
                Hãy thử kiểm tra lại chính tả từ khoá hoặc xem các đề xuất danh mục phổ biến bên dưới.
            </p>

            <div class="small fw-bold text-muted text-uppercase mb-3" style="letter-spacing: 1px;">Có thể bạn quan tâm</div>
            <div class="mb-4 d-flex justify-content-center flex-wrap gap-2">
                @foreach(['Casio', 'Seiko', 'Citizen', 'Tissot', 'Đồng hồ nam', 'Đồng hồ nữ'] as $s)
                <a href="{{ route('products.search', ['q' => $s]) }}" class="suggestion-pill btn rounded-pill border small px-3 py-2 fw-medium text-dark text-decoration-none">
                    <i class="bi bi-search me-1" style="font-size:.7rem;"></i>{{ $s }}
                </a>
                @endforeach
            </div>

            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-gold-action px-5 py-3 fw-bold rounded-3">
                    <i class="bi bi-grid-3x3-gap me-2"></i> Xem Tất Cả Sản Phẩm
                </a>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection