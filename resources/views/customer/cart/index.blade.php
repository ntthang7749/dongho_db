@extends('layouts.customer')
@section('title', 'Giỏ Hàng')

@push('styles')
<style>
    /* ── Hero ── */
    .cart-hero {
        background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%);
        border-bottom: 1px solid rgba(201,168,76,0.18);
    }
    .cart-hero h1 {
        font-family: 'Playfair Display', serif;
        color: #f0ece0;
    }
    .cart-hero .subtitle { color: rgba(240,236,224,0.5); }
    .cart-hero .breadcrumb-item a { color: var(--gold); text-decoration: none; }
    .cart-hero .breadcrumb-item.active { color: rgba(240,236,224,0.45); }
    .cart-hero .breadcrumb-item+.breadcrumb-item::before { color: rgba(201,168,76,0.35); }

    /* ── Progress steps ── */
    .cart-step.active .cart-step-circle {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
    }
    .cart-step.inactive .cart-step-circle {
        background: rgba(240,236,224,0.12);
        color: rgba(240,236,224,0.4);
    }
    .cart-step.active .cart-step-label { color: var(--gold); }
    .cart-step.inactive .cart-step-label { color: rgba(240,236,224,0.35); }
    .cart-step-line {
        height: 1px;
        background: rgba(201,168,76,0.2);
        max-width: 60px;
    }

    /* ── Section card ── */
    .cart-section {
        border-color: #f0ece4 !important;
        box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    }
    .cart-section-header .sec-icon {
        background: linear-gradient(135deg, rgba(201,168,76,0.18), rgba(201,168,76,0.06));
        color: var(--gold);
    }

    /* ── Cart item row ── */
    .cart-item {
        border-bottom: 1px solid #f5f2ec;
        transition: background 0.18s;
    }
    .cart-item:last-child { border-bottom: none; }
    .cart-item:hover { background: #fdfcf9; }

    .cart-item-brand {
        color: var(--gold-dark);
        letter-spacing: 0.7px;
    }
    .cart-item-name {
        color: #1a1a2e;
        text-decoration: none;
        transition: color 0.18s;
    }
    .cart-item-name:hover { color: var(--gold-dark); }
    .cart-item-subtotal small { color: #e94560; }

    /* Qty stepper */
    .qty-stepper {
        border-color: #e9e4d8 !important;
    }
    .qty-btn {
        transition: all 0.18s;
    }
    .qty-btn:hover { background: #ebe8df !important; color: #1a1a2e !important; }
    .qty-input {
        border-left: 1px solid #e9e4d8 !important;
        border-right: 1px solid #e9e4d8 !important;
    }

    /* Delete btn */
    .btn-del {
        border: 1.5px solid #fca5a5 !important;
        color: #dc2626;
        transition: all 0.18s;
    }
    .btn-del:hover { background: #fee2e2 !important; border-color: #ef4444 !important; transform: scale(1.08); }

    /* ── Coupon box ── */
    .coupon-applied {
        background: linear-gradient(135deg, rgba(34,197,94,0.07), rgba(34,197,94,0.03));
        border: 1px solid rgba(34,197,94,0.3) !important;
    }
    .coupon-applied .icon {
        background: rgba(34,197,94,0.12);
        color: #16a34a;
    }
    .coupon-applied strong { color: #166534; }
    .coupon-input-wrap .form-control {
        background: #f5f4f0;
        border: 1.5px solid #e9e4d8;
    }
    .coupon-input-wrap .form-control:focus {
        border-color: var(--gold);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(201,168,76,0.12);
    }
    .coupon-input-wrap .btn-apply {
        background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
        transition: all 0.2s;
    }
    .coupon-input-wrap .btn-apply:hover {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
    }
    .btn-remove-coupon {
        border: 1.5px solid #fca5a5 !important;
        color: #dc2626;
        transition: all 0.18s;
    }
    .btn-remove-coupon:hover { background: #fee2e2; }

    /* ── Summary card ── */
    .summary-row .lbl { color: #888; }
    .summary-row .val { color: #1a1a2e; }
    .summary-row.discount .val { color: #16a34a; }
    .summary-row.free .val { color: #16a34a; }
    .summary-divider { border-top: 2px dashed #f0ece4 !important; }
    .summary-total .val { color: #e94560; }

    /* Checkout btn */
    .btn-checkout {
        background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
        transition: all 0.25s;
        letter-spacing: 0.3px;
    }
    .btn-checkout:hover {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(201,168,76,0.35);
    }
    .btn-continue {
        border: 1.5px solid #e0dcd0 !important;
        color: #555;
        transition: all 0.2s;
    }
    .btn-continue:hover { border-color: #1a1a2e !important; color: #1a1a2e; background: #f5f4f0; }

    /* Secure badges */
    .secure-badge i { color: var(--gold); }

    /* ── Empty state ── */
    .cart-empty {
        border: 2px dashed rgba(201,168,76,0.3) !important;
    }
    .cart-empty-icon {
        background: linear-gradient(135deg, rgba(201,168,76,0.1), rgba(201,168,76,0.04));
        border: 2px dashed rgba(201,168,76,0.3);
    }
    .cart-empty-icon i { color: var(--gold); }
    .btn-shop-now {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
        transition: all 0.22s;
    }
    .btn-shop-now:hover {
        background: linear-gradient(135deg, var(--gold-light), var(--gold));
        color: #0a0a0f;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(201,168,76,0.35);
    }

    /* Sticky sidebar */
    @media (min-width: 992px) {
        .cart-sticky { position: sticky; top: 80px; }
    }
</style>
@endpush

@section('content')

{{-- ── Hero Header ── --}}
<div class="cart-hero py-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="font-size: 0.79rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
                <li class="breadcrumb-item active">Giỏ hàng</li>
            </ol>
        </nav>
        <h1 class="m-0" style="font-size: 1.65rem; font-weight: 700;">
            <i class="bi bi-cart3 me-2" style="color:var(--gold);font-size:1.3rem;vertical-align:middle;"></i>
            Giỏ Hàng
        </h1>
        <p class="subtitle m-0" style="font-size: 0.84rem;">Kiểm tra lại sản phẩm trước khi đặt hàng</p>

        {{-- Progress steps --}}
        @if(!empty($cart))
        <div class="cart-steps d-flex align-items-center gap-0 mt-3 mb-0">
            <div class="cart-step active d-flex align-items-center gap-2" style="font-size: 0.78rem; font-weight: 600;">
                <div class="cart-step-circle rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.75rem; font-weight: 700;">1</div>
                <span class="cart-step-label">Giỏ hàng</span>
            </div>
            <div class="cart-step-line flex-grow-1 mx-2" style="max-width: 60px;"></div>
            <div class="cart-step inactive d-flex align-items-center gap-2" style="font-size: 0.78rem; font-weight: 600;">
                <div class="cart-step-circle rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.75rem; font-weight: 700;">2</div>
                <span class="cart-step-label">Đặt hàng</span>
            </div>
            <div class="cart-step-line flex-grow-1 mx-2" style="max-width: 60px;"></div>
            <div class="cart-step inactive d-flex align-items-center gap-2" style="font-size: 0.78rem; font-weight: 600;">
                <div class="cart-step-circle rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.75rem; font-weight: 700;">3</div>
                <span class="cart-step-label">Thanh toán</span>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="cart-page bg-light py-5">
<div class="container">

    @if(empty($cart))
    {{-- ── Empty ── --}}
    <div class="cart-empty text-center p-5 bg-white rounded-4 shadow-sm mx-auto" style="max-width: 480px;">
        <div class="cart-empty-icon rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 100px; height: 100px;">
            <i class="bi bi-cart-x" style="font-size: 2.6rem;"></i>
        </div>
        <h5 class="mb-2" style="font-family: 'Playfair Display', serif; font-size: 1.3rem; font-weight: 700; color: #1a1a2e;">Giỏ hàng đang trống</h5>
        <p class="text-secondary mb-4" style="font-size: 0.875rem;">Hãy thêm những chiếc đồng hồ bạn yêu thích vào giỏ hàng nhé!</p>
        <a href="{{ route('products.index') }}" class="btn-shop-now btn d-inline-flex align-items-center gap-2 rounded-pill px-4 py-2 border-0 font-weight-bold" style="font-size: 0.9rem; text-decoration: none;">
            <i class="bi bi-bag-heart"></i>Mua Sắm Ngay
        </a>
    </div>

    @else
    <div class="row g-4 align-items-start">

        {{-- ══ LEFT: Danh sách sản phẩm ══ --}}
        <div class="col-lg-8">

            {{-- Product list --}}
            <div class="cart-section bg-white rounded-4 border overflow-hidden mb-3">
                <div class="cart-section-header d-flex align-items-center gap-2 p-3 bg-light border-bottom" style="font-weight: 700; font-size: 0.9rem; color: #1a1a2e;">
                    <input type="checkbox" id="check-all" class="form-check-input me-1" checked style="cursor: pointer; width: 17px; height: 17px; border-color: var(--gold);">
                    <div class="sec-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.95rem;"><i class="bi bi-bag"></i></div>
                    Sản Phẩm Trong Giỏ
                    <span class="cart-count-tag ms-auto badge bg-secondary-subtle text-secondary font-weight-bold px-2 py-1" style="font-size: 0.72rem; border-radius: 100px;">{{ count($cart) }} sản phẩm</span>
                </div>

                @foreach($cart as $id => $item)
                <div class="cart-item d-flex align-items-center gap-3 p-3 border-bottom" data-id="{{ $id }}" data-price="{{ $item['price'] }}" data-qty="{{ $item['quantity'] }}">
                    <input type="checkbox" class="cart-item-checkbox form-check-input flex-shrink-0 me-1" value="{{ $id }}" checked style="cursor: pointer; width: 17px; height: 17px; border-color: var(--gold);">
                    {{-- Thumbnail --}}
                    <div class="cart-thumb-wrap position-relative flex-shrink-0">
                        <a href="{{ route('products.show', $item['slug']) }}">
                            <img class="cart-thumb rounded-3 border object-fit-cover"
                                 src="{{ $item['thumbnail'] ? asset('storage/'.$item['thumbnail']) : asset('images/no-image.png') }}"
                                 alt="{{ $item['name'] }}"
                                 style="width: 80px; height: 80px;">
                        </a>
                    </div>

                    {{-- Info --}}
                    <div class="cart-item-info flex-grow-1 min-w-0">
                        <div class="cart-item-brand font-weight-bold text-uppercase" style="font-size: 0.7rem;">Đồng hồ cao cấp</div>
                        <a href="{{ route('products.show', $item['slug']) }}" class="cart-item-name font-weight-bold d-block" style="font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $item['name'] }}
                        </a>
                        <div class="cart-item-price text-secondary mt-1" style="font-size: 0.8rem;">
                            <i class="bi bi-tag me-1"></i>{{ number_format($item['price']) }}đ / chiếc
                        </div>
                    </div>

                    {{-- Qty stepper --}}
                    <form method="POST" action="{{ route('cart.update') }}" id="form-qty-{{ $id }}" class="flex-shrink-0">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $id }}">
                        <div class="qty-stepper d-flex align-items-center rounded-3 bg-light border overflow-hidden">
                            <button type="button" class="qty-btn btn border-0 p-0 text-dark d-flex align-items-center justify-content-center" onclick="changeQty('{{ $id }}', -1)" style="width: 34px; height: 36px; font-weight: 700; font-size: 1rem;">−</button>
                            <input type="number" name="quantity" id="qty-{{ $id }}"
                                   class="qty-input form-control text-center border-0 bg-transparent p-0"
                                   value="{{ $item['quantity'] }}"
                                   min="1" max="{{ $item['stock'] }}"
                                   onchange="document.getElementById('form-qty-{{ $id }}').submit()"
                                   style="width: 44px; height: 36px; font-weight: 700; font-size: 0.88rem; outline: none; box-shadow: none;">
                            <button type="button" class="qty-btn btn border-0 p-0 text-dark d-flex align-items-center justify-content-center" onclick="changeQty('{{ $id }}', 1)" style="width: 34px; height: 36px; font-weight: 700; font-size: 1rem;">+</button>
                        </div>
                    </form>

                    {{-- Subtotal --}}
                    <div class="cart-item-subtotal font-weight-bold text-end flex-shrink-0" style="font-size: 1rem; min-width: 100px;">
                        {{ number_format($item['price'] * $item['quantity']) }}đ
                        <small class="d-block text-secondary font-weight-bold" style="font-size: 0.78rem;">{{ $item['quantity'] }} × {{ number_format($item['price']) }}đ</small>
                    </div>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('cart.remove', $id) }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="btn-del btn d-flex align-items-center justify-content-center rounded-3 p-0" title="Xoá sản phẩm"
                                onclick="return confirm('Xoá {{ addslashes($item['name']) }} khỏi giỏ hàng?')" style="width: 34px; height: 34px;">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>

            {{-- Continue shopping --}}
            <a href="{{ route('products.index') }}" class="btn-continue btn d-flex align-items-center justify-content-center gap-2 rounded-3 w-100 p-2 mt-2" style="font-size: 0.855rem; text-decoration: none;">
                <i class="bi bi-arrow-left"></i>Tiếp Tục Mua Sắm
            </a>
        </div>

        {{-- ══ RIGHT: Summary ══ --}}
        <div class="col-lg-4">
            <div class="cart-sticky">

                {{-- Coupon --}}
                <div class="cart-section bg-white rounded-4 border overflow-hidden mb-3">
                    <div class="cart-section-header d-flex align-items-center gap-2 p-3 bg-light border-bottom" style="font-weight: 700; font-size: 0.9rem; color: #1a1a2e;">
                        <div class="sec-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.95rem;"><i class="bi bi-ticket-perforated"></i></div>
                        Mã Giảm Giá
                    </div>
                    <div class="p-3">
                        @if($coupon)
                        <div class="coupon-applied d-flex align-items-center gap-2 p-3 rounded-3" style="font-size: 0.855rem;">
                            <div class="icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.9rem;"><i class="bi bi-check-circle-fill"></i></div>
                            <div class="flex-grow-1">
                                <div class="text-secondary" style="font-size:0.72rem;margin-bottom:2px;">Mã đang áp dụng</div>
                                <strong class="font-weight-bold">{{ $coupon['code'] }}</strong>
                                <span class="text-secondary" style="font-size:0.8rem;margin-left:6px;">
                                    − {{ number_format($summary['discount']) }}đ
                                </span>
                            </div>
                            <form method="POST" action="{{ route('cart.remove-coupon') }}">
                                @csrf
                                <button type="submit" class="btn-remove-coupon btn d-inline-flex align-items-center justify-content-center gap-1 rounded-3 p-1 px-3" style="font-size: 0.8rem; font-weight: 600;">
                                    <i class="bi bi-x"></i>Xoá
                                </button>
                            </form>
                        </div>
                        @else
                        <form method="POST" action="{{ route('cart.coupon') }}">
                            @csrf
                            <div class="input-group coupon-input-wrap">
                                <input type="text" name="code" class="form-control"
                                       placeholder="Nhập mã giảm giá..."
                                       style="border-radius: 10px 0 0 10px; font-size: 0.855rem; padding: 9px 14px;">
                                <button class="btn btn-apply text-white font-weight-bold px-3" type="submit" style="border-radius: 0 10px 10px 0; font-size: 0.84rem;">Áp dụng</button>
                            </div>
                        </form>
                        @if($errors->has('code'))
                        <div class="text-danger mt-2" style="font-size:0.79rem;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first('code') }}
                        </div>
                        @endif
                        @endif
                    </div>
                </div>

                {{-- Order summary --}}
                <div class="cart-section bg-white rounded-4 border overflow-hidden mb-3">
                    <div class="cart-section-header d-flex align-items-center gap-2 p-3 bg-light border-bottom" style="font-weight: 700; font-size: 0.9rem; color: #1a1a2e;">
                        <div class="sec-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.95rem;"><i class="bi bi-receipt"></i></div>
                        Tổng Đơn Hàng
                    </div>
                    <div class="p-3">
                        <div class="summary-row d-flex justify-content-between align-items-center py-2" style="font-size: 0.875rem;">
                            <span class="lbl" id="summary-subtotal-lbl">Tạm tính ({{ count($cart) }} sản phẩm)</span>
                            <span class="val font-weight-bold" id="summary-subtotal-val">{{ number_format($summary['subtotal']) }}đ</span>
                        </div>
                        <div class="summary-row discount d-flex justify-content-between align-items-center py-2 {{ $summary['discount'] > 0 ? '' : 'd-none' }}" id="summary-discount-row" style="font-size: 0.875rem;">
                            <span class="lbl"><i class="bi bi-percent me-1"></i>Giảm giá <span id="summary-coupon-code">({{ $coupon['code'] ?? '' }})</span></span>
                            <span class="val font-weight-bold" id="summary-discount-val">−{{ number_format($summary['discount']) }}đ</span>
                        </div>
                        <div class="summary-row free d-flex justify-content-between align-items-center py-2" style="font-size: 0.875rem;">
                            <span class="lbl"><i class="bi bi-truck me-1"></i>Phí vận chuyển</span>
                            <span class="val font-weight-bold">Miễn phí</span>
                        </div>

                        <hr class="summary-divider my-2">

                        <div class="summary-total d-flex justify-content-between align-items-center py-2">
                            <span class="lbl font-weight-bold" style="font-size: 0.95rem; color: #1a1a2e;">Tổng thanh toán</span>
                            <span class="val font-weight-bold" id="summary-total-val" style="font-size: 1.45rem;">{{ number_format($summary['total']) }}đ</span>
                        </div>

                        <button type="button" id="btn-checkout-partial" class="btn-checkout btn d-flex align-items-center justify-content-center gap-2 rounded-3 w-100 p-3 mt-3 text-white font-weight-bold border-0" style="font-size: 0.95rem;">
                            <i class="bi bi-bag-check-fill"></i>
                            Tiến Hành Đặt Hàng
                            <i class="bi bi-arrow-right"></i>
                        </button>

                        {{-- Secure badges --}}
                        <div class="secure-badges d-flex justify-content-center gap-3 border-top pt-3 mt-3" style="font-size: 0.67rem;">
                            <div class="secure-badge d-flex flex-column align-items-center gap-1 text-secondary">
                                <i class="bi bi-shield-check" style="font-size: 1.1rem;"></i>
                                Bảo mật
                            </div>
                            <div class="secure-badge d-flex flex-column align-items-center gap-1 text-secondary">
                                <i class="bi bi-arrow-repeat" style="font-size: 1.1rem;"></i>
                                Đổi trả
                            </div>
                            <div class="secure-badge d-flex flex-column align-items-center gap-1 text-secondary">
                                <i class="bi bi-headset" style="font-size: 1.1rem;"></i>
                                Hỗ trợ 24/7
                            </div>
                            <div class="secure-badge d-flex flex-column align-items-center gap-1 text-secondary">
                                <i class="bi bi-award" style="font-size: 1.1rem;"></i>
                                Chính hãng
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- sticky --}}
        </div>

    </div>
    @endif

</div>
</div>

@push('scripts')
<script>
function changeQty(productId, delta) {
    const input = document.getElementById('qty-' + productId);
    const val   = parseInt(input.value) + delta;
    const min   = parseInt(input.min);
    const max   = parseInt(input.max);
    if (val >= min && val <= max) {
        input.value = val;
        document.getElementById('form-qty-' + productId).submit();
    }
}

// Partial checkout and real-time total updates
const coupon = @json(session('coupon'));

function updateSummary() {
    let subtotal = 0;
    let checkedCount = 0;
    
    document.querySelectorAll('.cart-item-checkbox:checked').forEach(cb => {
        const row = cb.closest('.cart-item');
        if (row) {
            const price = parseFloat(row.dataset.price) || 0;
            const qty = parseInt(row.dataset.qty) || 0;
            subtotal += price * qty;
            checkedCount++;
        }
    });
    
    // Calculate discount
    let discount = 0;
    if (coupon && subtotal >= coupon.min_order) {
        if (coupon.type === 'percent') {
            discount = subtotal * (coupon.value / 100);
            if (coupon.max_discount) {
                discount = Math.min(discount, coupon.max_discount);
            }
        } else {
            discount = coupon.value;
        }
    }
    
    let total = Math.max(0, subtotal - discount);
    
    // Update DOM
    const subtotalLbl = document.getElementById('summary-subtotal-lbl');
    const subtotalVal = document.getElementById('summary-subtotal-val');
    const discountRow = document.getElementById('summary-discount-row');
    const discountVal = document.getElementById('summary-discount-val');
    const totalVal = document.getElementById('summary-total-val');
    
    if (subtotalLbl) subtotalLbl.textContent = `Tạm tính (${checkedCount} sản phẩm)`;
    if (subtotalVal) subtotalVal.textContent = subtotal.toLocaleString('vi-VN') + 'đ';
    
    if (discountVal) discountVal.textContent = `−${discount.toLocaleString('vi-VN')}đ`;
    if (discountRow) {
        if (discount > 0) {
            discountRow.classList.remove('d-none');
        } else {
            discountRow.classList.add('d-none');
        }
    }
    
    if (totalVal) totalVal.textContent = total.toLocaleString('vi-VN') + 'đ';
    
    // Enable/disable checkout button based on items selected
    const checkoutBtn = document.getElementById('btn-checkout-partial');
    if (checkoutBtn) {
        if (checkedCount > 0) {
            checkoutBtn.disabled = false;
            checkoutBtn.style.opacity = '1';
            checkoutBtn.style.cursor = 'pointer';
        } else {
            checkoutBtn.disabled = true;
            checkoutBtn.style.opacity = '0.5';
            checkoutBtn.style.cursor = 'not-allowed';
        }
    }
}

// Listeners
document.getElementById('check-all')?.addEventListener('change', function() {
    const checked = this.checked;
    document.querySelectorAll('.cart-item-checkbox').forEach(cb => {
        cb.checked = checked;
    });
    updateSummary();
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('cart-item-checkbox')) {
        const allCheckboxes = document.querySelectorAll('.cart-item-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.cart-item-checkbox:checked');
        const checkAll = document.getElementById('check-all');
        
        if (checkAll) {
            checkAll.checked = allCheckboxes.length === checkedCheckboxes.length;
        }
        updateSummary();
    }
});

// Partial Checkout redirect
document.getElementById('btn-checkout-partial')?.addEventListener('click', function() {
    const selectedIds = Array.from(document.querySelectorAll('.cart-item-checkbox:checked')).map(cb => cb.value);
    if (selectedIds.length === 0) {
        alert('Vui lòng chọn ít nhất 1 sản phẩm để thanh toán!');
        return;
    }
    
    window.location.href = `{{ route('orders.checkout') }}?selected_items=` + selectedIds.join(',');
});

// Run on page load to initialize summary
document.addEventListener('DOMContentLoaded', function() {
    updateSummary();
});
</script>
@endpush

@endsection