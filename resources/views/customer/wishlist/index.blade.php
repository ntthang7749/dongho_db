@extends('layouts.customer')
@section('title', 'Sản Phẩm Yêu Thích')

@push('styles')
<style>
    /* Premium Hover & Animation Effects */
    .wish-card {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .wish-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.15) !important;
    }
    .wish-card:hover .wish-img-wrap img {
        transform: scale(1.06);
    }
    .wish-img-wrap img {
        transition: transform 0.4s ease;
    }
    
    /* Line clamp for product names */
    .wish-name {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s ease;
    }
    .wish-name:hover {
        color: var(--gold-dark) !important;
    }

    /* Interactive Buttons */
    .btn-remove-wish {
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
    }
    .btn-remove-wish:hover {
        background: #fee2e2 !important;
        color: #dc2626 !important;
        transform: scale(1.1);
    }

    .btn-add-cart {
        background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
        transition: all 0.25s ease;
    }
    .btn-add-cart:hover {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f !important;
        box-shadow: 0 6px 18px rgba(201, 168, 76, 0.3);
    }
    .btn-add-cart:disabled, .btn-add-cart.disabled {
        background: #e9e4d8 !important;
        color: #aaa !important;
    }

    /* Empty state explore button hover */
    .btn-explore {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
        transition: all 0.25s ease;
    }
    .btn-explore:hover {
        background: linear-gradient(135deg, var(--gold-light), var(--gold));
        color: #0a0a0f;
        box-shadow: 0 8px 24px rgba(201, 168, 76, 0.35);
    }

    /* Heartbeat keyframes for empty state */
    @keyframes heartbeat {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.06); }
    }
    .wish-empty-icon {
        animation: heartbeat 2.5s ease-in-out infinite;
    }

    /* Page delay animation */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(15px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .wish-card-animate {
        animation: fadeUp 0.4s ease both;
    }
    @for($i=1; $i<=12; $i++)
        .wish-card-animate:nth-child({{ $i }}) { animation-delay: {{ ($i - 1) * 0.05 }}s; }
    @endfor
</style>
@endpush

@section('content')

{{-- ── Hero Header ── --}}
<div class="py-4 mb-4 border-bottom" style="background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%); border-color: rgba(201,168,76,0.18) !important;">
    <div class="container py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--gold);">
                        <i class="bi bi-house me-1"></i>Trang chủ
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page" style="color: rgba(240,236,224,0.45);">Yêu thích</li>
            </ol>
        </nav>
        <h1 class="fs-3 fw-bold mb-1" style="font-family: 'Playfair Display', serif; color: #f0ece0;">
            <i class="bi bi-heart-fill me-2 align-middle" style="color:#e94560; font-size:1.3rem;"></i>
            Sản Phẩm Yêu Thích
        </h1>
        <p class="text-white-50 fs-7 mb-0">Những chiếc đồng hồ bạn đã lưu lại để xem sau</p>
        @if($wishlists->total() > 0)
        <div class="d-inline-flex align-items-center gap-2 fw-semibold rounded-pill px-3 py-1 mt-2 text-warning fs-8" style="background: rgba(201,168,76,0.13); border: 1px solid rgba(201,168,76,0.3); color: var(--gold) !important;">
            <i class="bi bi-heart-fill"></i>
            {{ $wishlists->total() }} sản phẩm yêu thích
        </div>
        @endif
    </div>
</div>

<div class="py-5" style="background: #f5f4f0; min-height: 55vh;">
    <div class="container py-2">

        @forelse($wishlists as $item)
        @php $product = $item->product; @endphp

        {{-- Grid wrapper: only open once --}}
        @if($loop->first)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @endif

            <div class="col wish-card-animate">
                <div class="wish-card bg-white rounded-4 border-0 shadow-sm overflow-hidden h-100 d-flex flex-column position-relative">
                    {{-- Image --}}
                    <div class="wish-img-wrap position-relative overflow-hidden" style="background: #faf9f5; height: 210px;">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ $product->thumbnail ? asset('storage/'.$product->thumbnail) : asset('images/no-image.png') }}"
                                 alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                        </a>

                        {{-- Sale badge --}}
                        @if($product->sale_price)
                        @php $pct = round((1 - $product->sale_price / $product->price) * 100); @endphp
                        <span class="position-absolute top-0 start-0 m-3 px-2 py-1 rounded-pill bg-danger text-white fw-bold fs-8" style="letter-spacing: 0.3px;">−{{ $pct }}%</span>
                        @endif

                        {{-- Out of stock overlay --}}
                        @if($product->stock <= 0)
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center">
                            <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold text-white fs-7" style="letter-spacing: 0.4px;">Hết hàng</span>
                        </div>
                        @endif

                        {{-- Remove button --}}
                        <form method="POST" action="{{ route('wishlist.toggle', $product->id) }}" style="display:contents;">
                            @csrf
                            <button type="submit" class="btn-remove-wish border-0 rounded-circle d-flex align-items-center justify-content-center position-absolute" style="top: 12px; right: 12px; width: 34px; height: 34px; color: #e94560; box-shadow: 0 2px 8px rgba(0,0,0,0.12);" title="Xoá khỏi yêu thích">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Body --}}
                    <div class="p-3 flex-grow-1 d-flex flex-column justify-content-between">
                        <div>
                            @if($product->brand)
                            <div class="text-uppercase fw-bold mb-1" style="font-size: 0.72rem; color: var(--gold-dark); letter-spacing: 0.8px;">{{ $product->brand->name }}</div>
                            @endif

                            <a href="{{ route('products.show', $product->slug) }}" class="wish-name fw-bold text-decoration-none text-dark mb-2">
                                {{ $product->name }}
                            </a>

                            {{-- Price --}}
                            <div class="mb-2">
                                @if($product->sale_price)
                                    <span class="text-danger fw-extrabold fs-5">{{ number_format($product->sale_price) }}đ</span>
                                    <span class="text-muted text-decoration-line-through fs-7 ms-2">{{ number_format($product->price) }}đ</span>
                                @else
                                    <span class="text-dark fw-extrabold fs-5">{{ number_format($product->price) }}đ</span>
                                @endif
                            </div>

                            {{-- Stock --}}
                            <div>
                                @if($product->stock > 0)
                                <span class="d-inline-flex align-items-center gap-1 fs-8 fw-semibold rounded-pill px-2 py-1 bg-success-subtle text-success mt-2">
                                    <i class="bi bi-check-circle-fill"></i>Còn {{ $product->stock }} sản phẩm
                                </span>
                                @else
                                <span class="d-inline-flex align-items-center gap-1 fs-8 fw-semibold rounded-pill px-2 py-1 bg-danger-subtle text-danger mt-2">
                                    <i class="bi bi-x-circle-fill"></i>Hết hàng
                                </span>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-3 pt-3 border-top">
                            @if($product->stock > 0)
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-add-cart w-100 d-flex align-items-center justify-content-center gap-2 py-2 rounded-3 text-white border-0 fw-semibold fs-7">
                                    <i class="bi bi-cart-plus"></i>Thêm vào giỏ hàng
                                </button>
                            </form>
                            @else
                            <span class="btn btn-add-cart disabled w-100 d-flex align-items-center justify-content-center gap-2 py-2 rounded-3 fw-semibold fs-7">
                                <i class="bi bi-cart-x"></i>Hết hàng
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @if($loop->last)
        </div>{{-- end .row --}}
        @endif

        @empty
        <div class="mx-auto my-5 text-center p-5 bg-white rounded-4 shadow-sm" style="max-width: 480px;">
            <div class="wish-empty-icon rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 96px; height: 96px; background: linear-gradient(135deg, rgba(233,69,96,0.1), rgba(233,69,96,0.04)); border: 2px dashed rgba(233,69,96,0.3); color: #e94560; font-size: 2rem;">
                <i class="bi bi-heart"></i>
            </div>
            <h5 class="font-playfair fs-4 fw-bold text-dark mb-2">Chưa có sản phẩm yêu thích</h5>
            <p class="text-muted fs-7 mb-4">Hãy khám phá bộ sưu tập đồng hồ cao cấp và lưu những mẫu bạn yêu thích!</p>
            <a href="{{ route('products.index') }}" class="btn btn-explore d-inline-flex align-items-center gap-2 px-4 py-2.5 rounded-pill fw-bold text-decoration-none fs-7 text-dark">
                <i class="bi bi-compass"></i>Khám Phá Ngay
            </a>
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($wishlists->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $wishlists->links() }}
        </div>
        @endif

    </div>
</div>
@endsection