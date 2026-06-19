@extends('layouts.app')
@section('title', 'Đăng Ký Tài Khoản - Đồng Hồ Online')
@section('content')

<div class="auth-card">

    {{-- Header --}}
    <div class="auth-header">
        <div class="auth-logo">⌚</div>
        <h1 class="auth-title">Tạo Tài Khoản</h1>
        <p class="auth-subtitle">Đăng ký để trải nghiệm mua sắm đẳng cấp</p>
    </div>

    {{-- Body --}}
    <div class="auth-body">

        {{-- Alerts --}}
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.send-otp') }}">
            @csrf

            {{-- Họ tên --}}
            <div class="form-group">
                <label class="form-label">Họ và tên <span class="req">*</span></label>
                <div class="input-group position-relative">
                    <i class="bi bi-person-badge input-icon"></i>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Nguyễn Văn A"
                           value="{{ old('name') }}"
                           autocomplete="name">
                </div>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tên đăng nhập --}}
            <div class="form-group">
                <label class="form-label">Tên đăng nhập <span class="req">*</span></label>
                <div class="input-group position-relative">
                    <i class="bi bi-at input-icon"></i>
                    <input type="text" name="username"
                           class="form-control @error('username') is-invalid @enderror"
                           placeholder="nguyenvana"
                           value="{{ old('username') }}"
                           autocomplete="username">
                </div>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <p class="text-hint">Chỉ dùng chữ, số, dấu - và _ (không dấu cách)</p>
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label">Email <span class="req">*</span></label>
                <div class="input-group position-relative">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="email@gmail.com"
                           value="{{ old('email') }}"
                           autocomplete="email">
                </div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <p class="text-hint"><i class="bi bi-shield-check me-1"></i>Mã OTP xác nhận sẽ được gửi về email này</p>
            </div>

            {{-- Mật khẩu --}}
            <div class="form-group">
                <label class="form-label">Mật khẩu <span class="req">*</span></label>
                <div class="input-group position-relative has-toggle">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Ít nhất 8 ký tự"
                           autocomplete="new-password">
                    <button class="btn-toggle-pass" type="button" onclick="togglePassword('password', this)" tabindex="-1">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Xác nhận mật khẩu --}}
            <div class="form-group" style="margin-bottom: 28px;">
                <label class="form-label">Xác nhận mật khẩu <span class="req">*</span></label>
                <div class="input-group position-relative has-toggle">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control"
                           placeholder="Nhập lại mật khẩu"
                           autocomplete="new-password">
                    <button class="btn-toggle-pass" type="button" onclick="togglePassword('password_confirmation', this)" tabindex="-1">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-auth-primary">
                <i class="bi bi-send me-2"></i>Gửi Mã OTP Xác Nhận
            </button>
        </form>

        {{-- Divider --}}
        <div class="auth-divider"><span>hoặc</span></div>

        {{-- Google --}}
        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z" fill="#4285F4"/>
                <path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 009 18z" fill="#34A853"/>
                <path d="M3.964 10.71A5.41 5.41 0 013.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 000 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/>
                <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 00.957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/>
            </svg>
            Đăng ký bằng Google
        </a>

        {{-- Footer --}}
        <p class="auth-footer-text">
            Đã có tài khoản?
            <a href="{{ route('login') }}" class="auth-link fw-semibold ms-1">Đăng nhập ngay →</a>
        </p>

    </div>
</div>

@push('scripts')
<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
@endpush
@endsection