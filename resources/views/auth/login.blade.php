@extends('layouts.app')
@section('title', 'Đăng Nhập - Đồng Hồ Online')
@section('content')

<div class="auth-card">

    {{-- Header --}}
    <div class="auth-header">
        <div class="auth-logo">⌚</div>
        <h1 class="auth-title">Đồng Hồ Online</h1>
        <p class="auth-subtitle">Chào mừng trở lại · Đăng nhập tài khoản</p>
    </div>

    {{-- Body --}}
    <div class="auth-body">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email / Username --}}
            <div class="form-group">
                <label class="form-label">Email hoặc Tên đăng nhập</label>
                <div class="input-group position-relative">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" name="login"
                           class="form-control"
                           placeholder="Email hoặc username"
                           value="{{ old('login') }}"
                           autocomplete="username">
                </div>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label">Mật khẩu</label>
                <div class="input-group position-relative has-toggle">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="pass"
                           class="form-control"
                           placeholder="Nhập mật khẩu"
                           autocomplete="current-password">
                    <button class="btn-toggle-pass" type="button" onclick="togglePassword('pass', this)" tabindex="-1">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            {{-- Remember & Forgot --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check d-flex align-items-center gap-2">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                </div>
                <a href="{{ route('password.request') }}" class="auth-link">Quên mật khẩu?</a>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-auth-primary">
                <i class="bi bi-box-arrow-in-right me-2"></i>Đăng Nhập
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
            Đăng nhập bằng Google
        </a>

        {{-- Footer --}}
        <p class="auth-footer-text">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="auth-link fw-semibold ms-1">Đăng ký ngay →</a>
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