@extends('layouts.customer')
@section('title', 'So Sánh Sản Phẩm')

@push('styles')
<style>
    .compare-hero {
        background: linear-gradient(135deg, #0a0a0f, #111128);
        border-bottom: 1px solid rgba(201,168,76,0.15);
    }
    .compare-hero::before {
        content:'';
        position:absolute;
        inset:0;
        background: radial-gradient(ellipse at 10% 80%, rgba(201,168,76,0.08), transparent 55%),
                    radial-gradient(ellipse at 90% 20%, rgba(99,60,150,0.07), transparent 50%);
    }
    .compare-hero h1 {
        font-family:'Playfair Display',serif;
    }
    .compare-label {
        letter-spacing: 2px;
        background: rgba(201,168,76,0.1);
        border: 1px solid rgba(201,168,76,0.25);
    }
    
    .compare-container {
        border-radius: 16px;
        border: 1px solid rgba(201,168,76,0.12) !important;
        overflow: hidden;
    }
    .compare-table {
        min-width: 850px;
    }
    .compare-img-wrap {
        width: 120px;
        height: 120px;
        border: 1px solid rgba(201,168,76,0.12);
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }
    .compare-product-title {
        font-size: 0.88rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s;
    }
    .compare-product-title:hover {
        color: var(--gold-dark) !important;
    }
    .spec-label-column {
        width: 180px;
        background: #fdfdfa !important;
        font-weight: bold;
        color: #1a1a2e;
        letter-spacing: 0.5px;
    }
    .btn-premium-gold {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f !important;
        border-radius: 10px;
        box-shadow: 0 4px 14px rgba(201,168,76,0.25);
        transition: all 0.25s;
    }
    .btn-premium-gold:hover {
        background: linear-gradient(135deg, var(--gold-light), var(--gold));
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(201,168,76,0.4);
    }
    .compare-remove-btn {
        transition: all 0.2s;
        cursor: pointer;
    }
    .compare-remove-btn:hover {
        transform: scale(1.15);
    }
</style>
@endpush

@section('content')

{{-- ════════ HERO SECTION ════════ --}}
<div class="compare-hero py-5 position-relative overflow-hidden text-white">
    <div class="container position-relative">
        <nav class="breadcrumb d-flex align-items-center mb-3 small">
            <a href="{{ route('home') }}" class="text-white-50 text-decoration-none hover-gold"><i class="bi bi-house me-1"></i>Trang chủ</a>
            <span class="text-white-50 mx-2">/</span>
            <a href="{{ route('products.index') }}" class="text-white-50 text-decoration-none hover-gold">Sản phẩm</a>
            <span class="text-white-50 mx-2">/</span>
            <span class="text-white-50 opacity-75">So sánh sản phẩm</span>
        </nav>

        <div class="compare-label d-inline-flex align-items-center gap-2 rounded-pill px-3 py-1 fw-bold text-uppercase text-gold small mb-3">
            <i class="bi bi-arrow-left-right text-gold"></i> ĐỐI SÁCH SIÊU PHẨM
        </div>
        <h1 class="fs-2 fw-bold mb-2">Đặt Lên Bàn Cân</h1>
        <p class="text-white-50 mb-0 small">So sánh cấu hình, vật liệu và mức giá chi tiết của tối đa 3 tuyệt tác thời gian.</p>
    </div>
</div>

{{-- ════════ COMPARE SHEET ════════ --}}
<div class="bg-light py-5" style="min-height:60vh;">
    <div class="container">

        @if($products->count() > 0)
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-semibold small">
                    <i class="bi bi-plus-lg me-1"></i> Thêm tuyệt phẩm khác
                </a>
                <button id="compare-clear-all-btn" class="btn btn-sm btn-outline-danger rounded-3 px-3 py-2 fw-semibold small">
                    <i class="bi bi-trash me-1"></i> Xóa toàn bộ so sánh
                </button>
            </div>

            <div class="compare-container bg-white shadow-sm overflow-auto mb-5">
                <table class="compare-table table table-bordered align-middle text-center mb-0">
                    <thead>
                        <tr class="table-light">
                            <th class="spec-label-column text-start text-dark fw-bold small text-uppercase py-3">Thông số kỹ thuật</th>
                            @for($i = 0; $i < 3; $i++)
                                @if(isset($products[$i]))
                                    @php $p = $products[$i]; @endphp
                                    <th class="p-3 position-relative align-top" style="min-width: 220px; background: #fff;">
                                        <span class="compare-remove-btn position-absolute top-0 end-0 m-2 text-danger fs-5 compare-item-remove" data-id="{{ $p->id }}" title="Xoá khỏi so sánh">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </span>
                                        <div class="compare-img-wrap mx-auto mb-3 rounded-3 overflow-hidden bg-light">
                                            <img src="{{ img_url($p->thumbnail) }}" class="w-100 h-100 object-fit-cover" alt="{{ $p->name }}">
                                        </div>
                                        <a href="{{ route('products.show', $p->slug) }}" class="compare-product-title fw-bold text-dark text-decoration-none d-block small mb-2" title="{{ $p->name }}">
                                            {{ $p->name }}
                                        </a>
                                        <div class="mt-2 mb-2">
                                            @if($p->sale_price)
                                                <span class="fs-6 fw-bold text-danger d-block">{{ number_format($p->sale_price) }}đ</span>
                                                <span class="text-muted text-decoration-line-through small">{{ number_format($p->price) }}đ</span>
                                            @else
                                                <span class="fs-6 fw-bold text-danger d-block">{{ number_format($p->price) }}đ</span>
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-center align-items-center gap-1 mb-3">
                                            @for($s = 1; $s <= 5; $s++)
                                                <i class="bi bi-star{{ $s <= $p->rating_avg ? '-fill' : ($s - 0.5 <= $p->rating_avg ? '-half' : '') }}" style="color:#f59e0b; font-size: 11px;"></i>
                                            @endfor
                                            <span class="text-muted ms-1" style="font-size: 0.72rem;">({{ $p->rating_count }})</span>
                                        </div>
                                        
                                        @if($p->stock > 0)
                                            <form method="POST" action="{{ route('cart.add') }}" class="add-to-cart-form">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $p->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-sm btn-dark w-100 py-2 rounded-3 border-0 fw-bold" style="background:#1a1a2e;">
                                                    <i class="bi bi-bag-plus me-1"></i> Thêm vào giỏ
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary w-100 py-2 rounded-3 text-muted disabled" disabled>
                                                <i class="bi bi-x-circle me-1"></i> Hết hàng
                                            </button>
                                        @endif
                                    </th>
                                @else
                                    <th class="p-3 align-top bg-light bg-opacity-50" style="min-width: 220px;">
                                        <div class="d-flex flex-column align-items-center justify-content-center border border-dashed rounded-3 p-4 h-100 bg-white" style="border: 2px dashed #dee2e6 !important;">
                                            <div class="fs-2 text-muted mb-2"><i class="bi bi-watch"></i></div>
                                            <div class="small text-muted fw-semibold">Chưa Chọn Mẫu</div>
                                            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-warning mt-3 rounded-3 px-3 py-1.5 small fw-semibold" style="color: var(--gold-dark); border-color: var(--gold);">
                                                Thêm tuyệt phẩm
                                            </a>
                                        </div>
                                    </th>
                                @endif
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="spec-label-column text-start py-2.5">Thương hiệu</td>
                            @for($i = 0; $i < 3; $i++)
                                <td>{{ isset($products[$i]) ? ($products[$i]->brand->name ?? '—') : '—' }}</td>
                            @endfor
                        </tr>
                        <tr>
                            <td class="spec-label-column text-start py-2.5">Bộ sưu tập</td>
                            @for($i = 0; $i < 3; $i++)
                                <td>{{ isset($products[$i]) ? ($products[$i]->category->name ?? '—') : '—' }}</td>
                            @endfor
                        </tr>
                        <tr>
                            <td class="spec-label-column text-start py-2.5">Bộ máy (Movement)</td>
                            @for($i = 0; $i < 3; $i++)
                                <td><span class="fw-semibold text-dark">{{ isset($products[$i]) ? ($products[$i]->movement ?? '—') : '—' }}</span></td>
                            @endfor
                        </tr>
                        <tr>
                            <td class="spec-label-column text-start py-2.5">Mặt kính</td>
                            @for($i = 0; $i < 3; $i++)
                                <td>{{ isset($products[$i]) ? ($products[$i]->glass_material ?? '—') : '—' }}</td>
                            @endfor
                        </tr>
                        <tr>
                            <td class="spec-label-column text-start py-2.5">Dây đeo</td>
                            @for($i = 0; $i < 3; $i++)
                                <td>{{ isset($products[$i]) ? ($products[$i]->band_material ?? '—') : '—' }}</td>
                            @endfor
                        </tr>
                        <tr>
                            <td class="spec-label-column text-start py-2.5">Chất liệu vỏ</td>
                            @for($i = 0; $i < 3; $i++)
                                <td>{{ isset($products[$i]) ? ($products[$i]->material ?? '—') : '—' }}</td>
                            @endfor
                        </tr>
                        <tr>
                            <td class="spec-label-column text-start py-2.5">Kích thước vỏ mặt</td>
                            @for($i = 0; $i < 3; $i++)
                                <td><span class="badge bg-light text-dark border p-2 small fw-normal">{{ isset($products[$i]) ? ($products[$i]->case_size ?? '—') : '—' }}</span></td>
                            @endfor
                        </tr>
                        <tr>
                            <td class="spec-label-column text-start py-2.5">Mức chống nước</td>
                            @for($i = 0; $i < 3; $i++)
                                <td>
                                    @if(isset($products[$i]) && $products[$i]->water_resistance)
                                        <i class="bi bi-droplet-fill text-info me-1"></i>{{ $products[$i]->water_resistance }}
                                    @else
                                        —
                                    @endif
                                </td>
                            @endfor
                        </tr>
                        <tr>
                            <td class="spec-label-column text-start py-2.5">Tone màu sắc</td>
                            @for($i = 0; $i < 3; $i++)
                                <td>{{ isset($products[$i]) ? ($products[$i]->color ?? '—') : '—' }}</td>
                            @endfor
                        </tr>
                        <tr>
                            <td class="spec-label-column text-start py-2.5">Tồn kho</td>
                            @for($i = 0; $i < 3; $i++)
                                <td>
                                    @if(isset($products[$i]))
                                        @if($products[$i]->stock > 0)
                                            <span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Sẵn có ({{ $products[$i]->stock }})</span>
                                        @else
                                            <span class="text-danger fw-semibold"><i class="bi bi-x-circle-fill me-1"></i> Hết hàng</span>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    </tbody>
                </table>
            </div>

        @else
            {{-- Empty compare list state --}}
            <div class="empty-compare-state text-center p-5 bg-white rounded-4 shadow-sm mb-5 border border-light">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-gold mx-auto mb-4 fs-2" style="width: 80px; height: 80px;">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2" style="font-family:'Playfair Display',serif;">Chưa Có Sản Phẩm So Sánh</h4>
                <p class="text-muted small mx-auto mb-4" style="max-width: 440px;">Danh sách so sánh của bạn hiện đang trống. Hãy quay lại trang sản phẩm và bấm vào nút so sánh trên mỗi chiếc đồng hồ để đặt chúng lên bàn cân chi tiết!</p>
                <a href="{{ route('products.index') }}" class="btn btn-premium-gold px-5 py-2.5 rounded-3 fw-bold">
                    <i class="bi bi-compass me-2"></i> Khám phá sản phẩm ngay
                </a>
            </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Clear all action within compare page
    const clearAllBtn = document.getElementById('compare-clear-all-btn');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrf) return;

            fetch('{{ route("products.compare.clear") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        });
    }
});
</script>
@endpush

@endsection
