@extends('layouts.app')
@section('title', 'Xác Nhận Mật Khẩu — Đồng Hồ Online')
@section('content')

<div class="auth-card">

    {{-- Header --}}
    <div class="auth-header">
        <div class="auth-logo">🔒</div>
        <h1 class="auth-title">Xác Nhận Mật Khẩu</h1>
        <p class="auth-subtitle">Đây là khu vực bảo mật. Vui lòng xác nhận mật khẩu để tiếp tục.</p>
    </div>

    {{-- Body --}}
    <div class="auth-body">

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <div style="background: rgba(201,168,76,0.07); border: 1px solid rgba(201,168,76,0.15); border-radius: 12px; padding: 14px 16px; margin-bottom: 24px;">
            <p style="font-size: 0.855rem; color: rgba(240,236,224,0.65); margin: 0; line-height: 1.6;">
                <i class="bi bi-info-circle me-2" style="color: var(--gold);"></i>
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            {{-- Mật khẩu --}}
            <div class="form-group" style="margin-bottom: 28px;">
                <label class="form-label">Mật khẩu <span class="req">*</span></label>
                <div class="input-group position-relative has-toggle">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="pass_confirm"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Nhập mật khẩu của bạn"
                           required autocomplete="current-password">
                    <button class="btn-toggle-pass" type="button" onclick="togglePassword('pass_confirm', this)" tabindex="-1">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-auth-primary">
                <i class="bi bi-shield-check me-2"></i>Xác Nhận
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('home') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Quay lại trang chủ
            </a>
        </div>

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
