@extends('layouts.app')
@section('title', 'Đặt Mật Khẩu Mới - Đồng Hồ Online')
@section('content')

<div class="auth-card">

    {{-- Header --}}
    <div class="auth-header">
        <div class="auth-logo">🛡️</div>
        <h1 class="auth-title">Mật Khẩu Mới</h1>
        <p class="auth-subtitle">Tạo mật khẩu mạnh để bảo vệ tài khoản</p>
    </div>

    {{-- Body --}}
    <div class="auth-body">

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        {{-- Success badge --}}
        <div class="success-badge">
            <i class="bi bi-patch-check-fill" style="color: #22c55e; font-size: 1.1rem; flex-shrink: 0;"></i>
            <span>OTP xác nhận thành công! Hãy tạo mật khẩu mới của bạn.</span>
        </div>

        <form method="POST" action="{{ route('auth.new-password.update') }}">
            @csrf

            {{-- Mật khẩu mới --}}
            <div class="form-group">
                <label class="form-label">Mật khẩu mới <span class="req">*</span></label>
                <div class="input-group position-relative has-toggle">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="newpass"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Ít nhất 8 ký tự"
                           autocomplete="new-password">
                    <button class="btn-toggle-pass" type="button" onclick="togglePassword('newpass', this)" tabindex="-1">
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
                    <input type="password" name="password_confirmation" id="newpass2"
                           class="form-control"
                           placeholder="Nhập lại mật khẩu"
                           autocomplete="new-password">
                    <button class="btn-toggle-pass" type="button" onclick="togglePassword('newpass2', this)" tabindex="-1">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-auth-primary">
                <i class="bi bi-shield-check me-2"></i>Cập Nhật Mật Khẩu
            </button>
        </form>

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