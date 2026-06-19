@extends('layouts.customer')
@section('title', 'Tài Khoản Của Tôi')

@push('styles')
<style>
/* ── PAGE HERO ── */
.profile-hero {
    background: linear-gradient(135deg, #0a0a0f 0%, #111128 60%, #0a0a0f 100%);
    border-bottom: 1px solid rgba(201,168,76,0.12);
    position: relative;
    overflow: hidden;
}
.profile-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse at 0% 100%, rgba(201,168,76,0.1) 0%, transparent 50%),
        radial-gradient(ellipse at 100% 0%, rgba(99,60,150,0.07) 0%, transparent 45%);
    pointer-events: none;
}
.profile-hero-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(201,168,76,0.035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(201,168,76,0.035) 1px, transparent 1px);
    background-size: 56px 56px;
    pointer-events: none;
}

.profile-avatar-wrap {
    width: 96px;
    height: 96px;
    flex-shrink: 0;
}
.profile-avatar-img {
    width: 96px;
    height: 96px;
    border-radius: 24px;
    object-fit: cover;
    border: 3px solid rgba(201,168,76,0.4);
    box-shadow: 0 8px 28px rgba(0,0,0,0.35);
}
.profile-avatar-initial {
    width: 96px;
    height: 96px;
    border-radius: 24px;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #0a0a0f;
    font-size: 2.2rem;
    font-weight: 800;
    border: 3px solid rgba(201,168,76,0.4);
    box-shadow: 0 8px 28px rgba(201,168,76,0.3);
}
.profile-avatar-ring {
    position: absolute;
    inset: -4px;
    border-radius: 28px;
    border: 2px solid rgba(201,168,76,0.25);
    pointer-events: none;
}
.profile-verified-dot {
    position: absolute;
    bottom: 4px;
    right: 4px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #22c55e;
    border: 2.5px solid #0d0d18;
}

.profile-hero-name {
    font-family: 'Playfair Display', serif;
    color: #fff;
}
.profile-hero-email {
    color: rgba(240,236,224,0.48);
}
.profile-hero-badge {
    background: rgba(201,168,76,0.1);
    border: 1px solid rgba(201,168,76,0.25);
    color: var(--gold);
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}

/* Stats */
.stat-item {
    border-right: 1px solid rgba(201,168,76,0.1);
}
.stat-item:last-child {
    border-right: none;
}
.stat-num {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--gold);
}
.stat-label {
    font-size: 0.7rem;
    color: rgba(240,236,224,0.4);
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Nav tabs */
.profile-tab-link {
    font-size: 0.835rem;
    font-weight: 600;
    color: rgba(240,236,224,0.48);
    border-bottom: 2.5px solid transparent;
    transition: all 0.2s;
    letter-spacing: 0.2px;
}
.profile-tab-link:hover {
    color: rgba(240,236,224,0.8);
    background: rgba(255,255,255,0.03);
}
.profile-tab-link.active {
    color: var(--gold);
    border-bottom-color: var(--gold);
    background: rgba(201,168,76,0.05);
}

/* ── CARDS ── */
.section-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 3px 20px rgba(0,0,0,0.07);
    border: 1px solid #f0ece0;
}
.section-card-header {
    background: linear-gradient(135deg, #0d0d18, #1a1a35);
    border-bottom: 1px solid rgba(201,168,76,0.12);
}
.section-card-title {
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: rgba(240,236,224,0.88);
}
.section-card-title i {
    color: var(--gold);
}

/* ── FORM FIELDS ── */
.form-label-profile {
    font-size: 0.75rem;
    font-weight: 700;
    color: #aaa;
    letter-spacing: 0.8px;
    text-transform: uppercase;
}
.form-input-profile {
    background: #f8f7f3;
    border: 1.5px solid #ebe9e0;
    color: #1a1a2e;
    transition: all 0.22s;
}
.form-input-profile:focus {
    border-color: var(--gold);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(201,168,76,0.1);
}
.form-input-profile.readonly {
    background: #f3f2ee;
    color: #aaa;
    cursor: not-allowed;
    border-color: #e8e6de;
}
.form-input-profile.readonly:focus {
    box-shadow: none;
    border-color: #e8e6de;
}

.form-input-icon {
    color: #ccc;
    font-size: 0.95rem;
    transition: color 0.2s;
}
.form-input-icon-wrap:focus-within .form-input-icon {
    color: var(--gold);
}

.pw-toggle {
    background: none;
    border: none;
    color: #bbb;
    cursor: pointer;
    font-size: 0.9rem;
    transition: color .2s;
}
.pw-toggle:hover {
    color: var(--gold);
}

/* Avatar upload */
.avatar-upload-area {
    background: #f8f7f3;
    border: 2px dashed #e0ddd5;
    transition: all 0.2s;
    cursor: pointer;
}
.avatar-upload-area:hover {
    border-color: rgba(201,168,76,0.4);
    background: rgba(201,168,76,0.03);
}
.avatar-preview {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    object-fit: cover;
    border: 2px solid rgba(201,168,76,0.3);
    flex-shrink: 0;
}
.avatar-preview-initial {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #0a0a0f;
    font-weight: 800;
    font-size: 1.3rem;
    flex-shrink: 0;
}

/* Submit buttons */
.btn-profile-save {
    background: linear-gradient(135deg, #1a1a2e, #0d0d18);
    color: #fff;
    border: none;
    font-size: 0.875rem;
    font-weight: 700;
    transition: all 0.25s;
    box-shadow: 0 4px 14px rgba(0,0,0,0.15);
}
.btn-profile-save:hover {
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #0a0a0f;
    box-shadow: 0 8px 24px rgba(201,168,76,0.4);
    transform: translateY(-2px);
}

.btn-profile-pw {
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #0a0a0f;
    border: none;
    font-size: 0.875rem;
    font-weight: 700;
    transition: all 0.25s;
    box-shadow: 0 4px 14px rgba(201,168,76,0.28);
}
.btn-profile-pw:hover {
    background: linear-gradient(135deg, var(--gold-light), var(--gold));
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(201,168,76,0.45);
}

/* Order Rows */
.order-row {
    border-bottom: 1px solid #f8f7f3;
    transition: background 0.18s;
}
.order-row:last-child {
    border-bottom: none;
}
.order-row:hover {
    background: #faf9f5;
}

.order-code {
    font-weight: 700;
    font-size: 0.875rem;
    color: #1a1a2e;
}
.order-date {
    font-size: 0.76rem;
    color: #bbb;
}
.order-date i {
    color: var(--gold);
    font-size: 0.65rem;
}

.order-status-pill {
    font-size: 0.73rem;
    font-weight: 700;
    letter-spacing: 0.3px;
}
.status-pending   { background: rgba(245,158,11,0.1); color: #d97706; border:1px solid rgba(245,158,11,0.25); }
.status-confirmed { background: rgba(59,130,246,0.1); color: #2563eb; border:1px solid rgba(59,130,246,0.2); }
.status-shipping  { background: rgba(99,102,241,0.1); color: #6366f1; border:1px solid rgba(99,102,241,0.2); }
.status-delivered { background: rgba(34,197,94,0.1);  color: #16a34a; border:1px solid rgba(34,197,94,0.2); }
.status-cancelled { background: rgba(239,68,68,0.1);  color: #dc2626; border:1px solid rgba(239,68,68,0.2); }

.order-total {
    font-weight: 800;
    font-size: 0.95rem;
    color: #e94560;
}

.btn-view-all-orders {
    font-size: 0.78rem;
    font-weight: 600;
    color: #a07c30;
    border: 1.5px solid rgba(201,168,76,0.35);
    transition: all 0.22s;
}
.btn-view-all-orders:hover {
    background: var(--gold);
    border-color: var(--gold);
    color: #0a0a0f;
}

/* Quick links hover */
.quick-link-item {
    transition: background 0.2s;
}
.quick-link-item:hover {
    background: #faf9f5 !important;
}

/* Google badge */
.google-badge {
    background: rgba(66,133,244,0.07);
    border: 1px solid rgba(66,133,244,0.2);
    font-size: 0.845rem;
    color: #4285f4;
    font-weight: 600;
}
</style>
@endpush

@section('content')

{{-- ══════════════ PROFILE HERO ══════════════ --}}
<div class="profile-hero pt-5">
    <div class="profile-hero-grid"></div>
    <div class="container position-relative pb-4">

        {{-- Nav breadcrumb --}}
        <nav class="mb-3">
            <ol class="breadcrumb mb-0 bg-transparent p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none" style="color:rgba(201,168,76,0.65); font-size:0.8rem;">Trang chủ</a>
                </li>
                <li class="breadcrumb-item active" style="color:rgba(240,236,224,0.35); font-size:0.8rem;">Tài khoản</li>
            </ol>
        </nav>

        {{-- Avatar + Info --}}
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <div class="profile-avatar-wrap position-relative">
                @if($user->avatarUrl())
                    <img src="{{ $user->avatarUrl() }}" class="profile-avatar-img" alt="{{ $user->name }}">
                @else
                    <div class="profile-avatar-initial d-flex align-items-center justify-content-center">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="profile-avatar-ring"></div>
                <div class="profile-verified-dot" title="Tài khoản đã xác minh"></div>
            </div>

            <div>
                <h1 class="profile-hero-name mb-1 fs-2 fw-bold text-white">{{ $user->name }}</h1>
                <p class="profile-hero-email mb-2">{{ $user->email }}</p>
                <div class="profile-hero-badge badge d-inline-flex align-items-center gap-1.5 py-1.5 px-3 rounded-pill text-uppercase">
                    <i class="bi bi-person-check"></i>
                    Thành viên {{ $user->created_at->format('Y') }}
                    @if($user->google_id)
                        · <i class="bi bi-google ms-1"></i> Google
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats --}}
        @php
            $totalOrders = $orders->count();
            $totalSpent  = $orders->where('status', 'delivered')->sum('total');
            $wishCount   = $user->wishlists->count();
        @endphp
        <div class="profile-stats mt-4 rounded-4 overflow-hidden" style="max-width:420px;">
            <div class="d-flex w-100 text-center">
                <div class="stat-item flex-fill py-2.5 px-3">
                    <span class="stat-num d-block">{{ $totalOrders }}</span>
                    <span class="stat-label d-block text-uppercase">{{ 'Đơn hàng' }}</span>
                </div>
                <div class="stat-item flex-fill py-2.5 px-3">
                    <span class="stat-num d-block">{{ number_format($totalSpent / 1000000, 1) }}M</span>
                    <span class="stat-label d-block text-uppercase">{{ 'Đã chi tiêu' }}</span>
                </div>
                <div class="stat-item flex-fill py-2.5 px-3">
                    <span class="stat-num d-block">{{ $wishCount }}</span>
                    <span class="stat-label d-block text-uppercase">{{ 'Yêu thích' }}</span>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="profile-tabs d-flex flex-wrap mt-4">
            <a href="{{ route('profile.index') }}" class="profile-tab-link active d-flex align-items-center gap-2 py-3 px-4 text-decoration-none">
                <i class="bi bi-person"></i>Thông tin
            </a>
            <a href="{{ route('orders.history') }}" class="profile-tab-link d-flex align-items-center gap-2 py-3 px-4 text-decoration-none">
                <i class="bi bi-bag"></i>Đơn hàng
            </a>
            <a href="{{ route('wishlist.index') }}" class="profile-tab-link d-flex align-items-center gap-2 py-3 px-4 text-decoration-none">
                <i class="bi bi-heart"></i>Yêu thích
            </a>
        </div>
    </div>
</div>

{{-- ══════════════ MAIN CONTENT ══════════════ --}}
<div style="background:#f5f4f0; padding:48px 0 72px; min-height:60vh;">
    <div class="container">

        {{-- Flash / Errors using Bootstrap Alert --}}
        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 rounded-4 p-3 mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 rounded-4 p-3 mb-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle-fill fs-5"></i>
            <div>{{ session('error') }}</div>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2 rounded-4 p-3 mb-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
            <div>
                <strong class="d-block mb-1">Vui lòng kiểm tra lại:</strong>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="row g-4">

            {{-- ─── LEFT COL ─── --}}
            <div class="col-lg-7">

                {{-- ── THÔNG TIN CÁ NHÂN ── --}}
                <div class="section-card card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <div class="section-card-header px-4 py-3 d-flex align-items-center justify-content-between">
                        <h2 class="section-card-title d-flex align-items-center gap-2 mb-0">
                            <i class="bi bi-person-lines-fill"></i>Thông Tin Cá Nhân
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                {{-- Họ tên --}}
                                <div class="col-md-6 mb-2">
                                    <label class="form-label-profile d-block mb-2">Họ & Tên</label>
                                    <div class="form-input-icon-wrap position-relative">
                                        <i class="bi bi-person form-input-icon position-absolute" style="left:14px; top:50%; transform:translateY(-50%);"></i>
                                        <input type="text" name="name"
                                               class="form-control form-input-profile py-2.5 ps-5 rounded-3"
                                               value="{{ old('name', $user->name) }}"
                                               placeholder="Nhập họ tên...">
                                    </div>
                                </div>

                                {{-- Tên đăng nhập --}}
                                <div class="col-md-6 mb-2">
                                    <label class="form-label-profile d-block mb-2">Tên đăng nhập</label>
                                    <div class="form-input-icon-wrap position-relative">
                                        <i class="bi bi-at form-input-icon position-absolute" style="left:14px; top:50%; transform:translateY(-50%);"></i>
                                        <input type="text" class="form-control form-input-profile readonly py-2.5 ps-5 rounded-3"
                                               value="{{ $user->username }}" readonly>
                                    </div>
                                    <small class="text-muted d-block mt-1 small">Không thể thay đổi</small>
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6 mb-2">
                                    <label class="form-label-profile d-block mb-2">Email</label>
                                    <div class="form-input-icon-wrap position-relative">
                                        <i class="bi bi-envelope form-input-icon position-absolute" style="left:14px; top:50%; transform:translateY(-50%);"></i>
                                        <input type="email" class="form-control form-input-profile readonly py-2.5 ps-5 rounded-3"
                                               value="{{ $user->email }}" readonly>
                                    </div>
                                </div>

                                {{-- SĐT --}}
                                <div class="col-md-6 mb-2">
                                    <label class="form-label-profile d-block mb-2">Số điện thoại</label>
                                    <div class="form-input-icon-wrap position-relative">
                                        <i class="bi bi-telephone form-input-icon position-absolute" style="left:14px; top:50%; transform:translateY(-50%);"></i>
                                        <input type="text" name="phone"
                                               class="form-control form-input-profile py-2.5 ps-5 rounded-3"
                                               value="{{ old('phone', $user->phone) }}"
                                               placeholder="0xxxxxxxxx">
                                    </div>
                                </div>

                                {{-- Địa chỉ --}}
                                <div class="col-12 mb-2">
                                    <label class="form-label-profile d-block mb-2">Địa chỉ giao hàng</label>
                                    <div class="form-input-icon-wrap position-relative">
                                        <i class="bi bi-geo-alt form-input-icon position-absolute" style="left:14px; top:18px;"></i>
                                        <textarea name="address"
                                                  class="form-control form-input-profile ps-5 rounded-3"
                                                  style="resize:none;"
                                                  rows="2"
                                                  placeholder="Số nhà, đường, quận/huyện, tỉnh/thành phố...">{{ old('address', $user->address) }}</textarea>
                                    </div>
                                </div>

                                {{-- Avatar upload --}}
                                <div class="col-12">
                                    <label class="form-label-profile d-block mb-2">Ảnh đại diện</label>
                                    <div class="avatar-upload-area d-flex align-items-center gap-3 p-3 rounded-3 position-relative" id="avatarDropArea">
                                        <input type="file" name="avatar" accept="image/*"
                                               id="avatarInput" onchange="previewAvatar(event)"
                                               class="position-absolute w-100 h-100 opacity-0 cursor-pointer" style="inset:0;">
                                        @if($user->avatarUrl())
                                            <img src="{{ $user->avatarUrl() }}" class="avatar-preview" id="avatarPreviewImg" alt="Avatar">
                                        @else
                                            <div class="avatar-preview-initial d-flex align-items-center justify-content-center" id="avatarPreviewInitial">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="avatar-upload-text flex-grow-1">
                                            <strong class="d-block mb-1"><i class="bi bi-cloud-upload me-1 text-gold"></i>Tải ảnh lên</strong>
                                            <small class="text-muted d-block">Kéo thả hoặc bấm để chọn · JPG, PNG, tối đa 2MB</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-4 pt-3 border-top border-light">
                                <button type="submit" class="btn btn-profile-save d-inline-flex align-items-center gap-2 px-4 py-2.5 rounded-3">
                                    <i class="bi bi-floppy-disk"></i>Lưu Thay Đổi
                                </button>
                                <small class="text-muted">Cập nhật lần cuối: {{ $user->updated_at->diffForHumans() }}</small>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ── ĐỔI MẬT KHẨU ── --}}
                @if(!$user->google_id || $user->password)
                <div class="section-card card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <div class="section-card-header px-4 py-3 d-flex align-items-center justify-content-between">
                        <h2 class="section-card-title d-flex align-items-center gap-2 mb-0">
                            <i class="bi bi-shield-lock"></i>Bảo Mật & Mật Khẩu
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12 mb-2">
                                    <label class="form-label-profile d-block mb-2">Mật khẩu hiện tại</label>
                                    <div class="pw-wrap position-relative">
                                        <div class="form-input-icon-wrap position-relative">
                                            <i class="bi bi-lock form-input-icon position-absolute" style="left:14px; top:50%; transform:translateY(-50%);"></i>
                                            <input type="password" name="current_password"
                                                   id="currentPw"
                                                   class="form-control form-input-profile py-2.5 ps-5 rounded-3"
                                                   placeholder="Nhập mật khẩu hiện tại..."
                                                   style="padding-right:44px;">
                                        </div>
                                        <button type="button" class="pw-toggle position-absolute" style="right:12px; top:50%; transform:translateY(-50%);" onclick="togglePw('currentPw',this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label class="form-label-profile d-block mb-2">Mật khẩu mới</label>
                                    <div class="pw-wrap position-relative">
                                        <div class="form-input-icon-wrap position-relative">
                                            <i class="bi bi-key form-input-icon position-absolute" style="left:14px; top:50%; transform:translateY(-50%);"></i>
                                            <input type="password" name="password"
                                                   id="newPw"
                                                   class="form-control form-input-profile py-2.5 ps-5 rounded-3"
                                                   placeholder="Tối thiểu 8 ký tự..."
                                                   style="padding-right:44px;"
                                                   oninput="checkPwStrength(this.value)">
                                        </div>
                                        <button type="button" class="pw-toggle position-absolute" style="right:12px; top:50%; transform:translateY(-50%);" onclick="togglePw('newPw',this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    {{-- Strength bar using Bootstrap Progress --}}
                                    <div class="progress mt-2 rounded-pill" style="height:6px; background-color:#eee;">
                                        <div class="progress-bar rounded-pill" id="pwStrengthFill" style="width:0%; transition:all 0.3s;"></div>
                                    </div>
                                    <div class="small mt-1" id="pwStrengthText" style="font-size:0.72rem; color:#aaa;"></div>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label class="form-label-profile d-block mb-2">Xác nhận mật khẩu mới</label>
                                    <div class="pw-wrap position-relative">
                                        <div class="form-input-icon-wrap position-relative">
                                            <i class="bi bi-key form-input-icon position-absolute" style="left:14px; top:50%; transform:translateY(-50%);"></i>
                                            <input type="password" name="password_confirmation"
                                                   id="confirmPw"
                                                   class="form-control form-input-profile py-2.5 ps-5 rounded-3"
                                                   placeholder="Nhập lại mật khẩu mới..."
                                                   style="padding-right:44px;">
                                        </div>
                                        <button type="button" class="pw-toggle position-absolute" style="right:12px; top:50%; transform:translateY(-50%);" onclick="togglePw('confirmPw',this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top border-light">
                                <button type="submit" class="btn btn-profile-pw d-inline-flex align-items-center gap-2 px-4 py-2.5 rounded-3">
                                    <i class="bi bi-shield-check"></i>Cập Nhật Mật Khẩu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                {{-- Google login notice --}}
                <div class="section-card card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <div class="section-card-header px-4 py-3 d-flex align-items-center justify-content-between">
                        <h2 class="section-card-title d-flex align-items-center gap-2 mb-0"><i class="bi bi-shield-lock"></i>Bảo Mật</h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="google-badge d-inline-flex align-items-center gap-2 py-2.5 px-3 rounded-3 w-100">
                            <i class="bi bi-google"></i>
                            <span>Tài khoản của bạn được bảo vệ bởi Google. Không cần đặt mật khẩu riêng.</span>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            {{-- ─── RIGHT COL ─── --}}
            <div class="col-lg-5">

                {{-- ── ĐƠN HÀNG GẦN ĐÂY ── --}}
                <div class="section-card card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <div class="section-card-header px-4 py-3 d-flex align-items-center justify-content-between">
                        <h2 class="section-card-title d-flex align-items-center gap-2 mb-0">
                            <i class="bi bi-bag-heart"></i>Đơn Hàng Gần Đây
                        </h2>
                        <a href="{{ route('orders.history') }}" class="btn btn-view-all-orders d-inline-flex align-items-center gap-1 py-1 px-3 rounded-pill text-decoration-none text-uppercase">
                            Xem tất cả <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    @forelse($orders as $order)
                    @php
                        $sMap = [
                            'pending'   => ['label' => 'Chờ xác nhận', 'class' => 'status-pending',   'icon' => 'bi-clock'],
                            'confirmed' => ['label' => 'Đã xác nhận',  'class' => 'status-confirmed', 'icon' => 'bi-check-circle'],
                            'shipping'  => ['label' => 'Đang giao',    'class' => 'status-shipping',  'icon' => 'bi-truck'],
                            'delivered' => ['label' => 'Đã giao',      'class' => 'status-delivered', 'icon' => 'bi-bag-check'],
                            'cancelled' => ['label' => 'Đã huỷ',       'class' => 'status-cancelled', 'icon' => 'bi-x-circle'],
                        ];
                        $s = $sMap[$order->status] ?? ['label' => $order->status, 'class' => '', 'icon' => 'bi-circle'];
                    @endphp
                    <a href="{{ route('orders.history') }}" class="order-row d-flex align-items-center justify-content-between p-3 text-decoration-none">
                        <div class="flex-grow-1 min-w-0 me-3">
                            <span class="order-code d-block fw-bold text-dark mb-0.5"># {{ $order->order_code }}</span>
                            <span class="order-date d-flex align-items-center gap-1.5 text-muted small">
                                <i class="bi bi-calendar3"></i>
                                {{ $order->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                        <span class="badge order-status-pill {{ $s['class'] }} d-inline-flex align-items-center gap-1 py-1.5 px-2.5 rounded-pill text-uppercase">
                            <i class="bi {{ $s['icon'] }}"></i>{{ $s['label'] }}
                        </span>
                        <span class="order-total fw-extrabold text-danger ms-3">{{ number_format($order->total) }}đ</span>
                    </a>
                    @empty
                    <div class="empty-orders py-5 text-center">
                        <i class="bi bi-bag display-6 text-muted mb-2"></i>
                        <p class="text-muted mb-3">Bạn chưa có đơn hàng nào</p>
                        <a href="{{ route('products.index') }}" class="btn btn-profile-pw d-inline-flex align-items-center gap-1.5 px-4 py-2 rounded-3 text-decoration-none">
                            <i class="bi bi-watch"></i>Mua sắm ngay
                        </a>
                    </div>
                    @endforelse
                </div>

                {{-- ── LIÊN KẾT NHANH ── --}}
                <div class="section-card card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <div class="section-card-header px-4 py-3 d-flex align-items-center justify-content-between">
                        <h2 class="section-card-title d-flex align-items-center gap-2 mb-0"><i class="bi bi-lightning"></i>Liên Kết Nhanh</h2>
                    </div>
                    <div class="p-2">
                        @php
                        $quickLinks = [
                            ['icon' => 'bi-bag', 'label' => 'Lịch sử đơn hàng', 'sub' => $totalOrders . ' đơn hàng', 'url' => route('orders.history'), 'color' => '#6366f1'],
                            ['icon' => 'bi-heart', 'label' => 'Sản phẩm yêu thích', 'sub' => $wishCount . ' sản phẩm', 'url' => route('wishlist.index'), 'color' => '#e94560'],
                            ['icon' => 'bi-watch', 'label' => 'Tiếp tục mua sắm', 'sub' => 'Khám phá bộ sưu tập mới', 'url' => route('products.index'), 'color' => '#c9a84c'],
                            ['icon' => 'bi-camera', 'label' => 'Nhận diện AI', 'sub' => 'Tìm đồng hồ bằng ảnh', 'url' => route('ai.image.form'), 'color' => '#22c55e'],
                        ];
                        @endphp
                        @foreach($quickLinks as $link)
                        <a href="{{ $link['url'] }}"
                           class="quick-link-item d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none mb-1">
                            <div class="d-flex align-items-center justify-content-center rounded-3 fs-5" style="width:40px; height:40px; background:{{ $link['color'] }}18; color:{{ $link['color'] }}; flex-shrink:0;">
                                <i class="bi {{ $link['icon'] }}"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <span class="d-block fw-bold text-dark fs-6 mb-0.5">{{ $link['label'] }}</span>
                                <span class="d-block text-muted small">{{ $link['sub'] }}</span>
                            </div>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── Avatar preview ──
function previewAvatar(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const wrap = document.getElementById('avatarDropArea');
        const existing = wrap.querySelector('.avatar-preview, .avatar-preview-initial');
        if (existing) existing.remove();
        const img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'avatar-preview';
        img.id = 'avatarPreviewImg';
        wrap.insertBefore(img, wrap.querySelector('.avatar-upload-text'));
    };
    reader.readAsDataURL(file);
}

// ── Toggle password ──
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// ── Password strength ──
function checkPwStrength(val) {
    const fill = document.getElementById('pwStrengthFill');
    const text = document.getElementById('pwStrengthText');
    if (!fill) return;

    let score = 0;
    if (val.length >= 8)  score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const configs = [
        { w: '0%',   color: '#eee',    label: '' },
        { w: '25%',  color: '#ef4444', label: '⚠ Yếu' },
        { w: '50%',  color: '#f59e0b', label: '⬆ Trung bình' },
        { w: '75%',  color: '#3b82f6', label: '✓ Khá mạnh' },
        { w: '100%', color: '#22c55e', label: '✓✓ Mạnh' },
    ];
    const c = configs[score] || configs[0];
    fill.style.width = c.w;
    fill.style.background = c.color;
    text.textContent = c.label;
    text.style.color = c.color;
}
</script>
@endpush

@endsection