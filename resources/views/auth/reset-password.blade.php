@extends('layouts.app')
@section('title', 'Đặt Lại Mật Khẩu — Đồng Hồ Online')
@section('content')

<div class="auth-card">

    {{-- Header --}}
    <div class="auth-header">
        <div class="auth-logo">🔄</div>
        <h1 class="auth-title">Đặt Lại Mật Khẩu</h1>
        <p class="auth-subtitle">Cập nhật mật khẩu mới cho tài khoản của bạn</p>
    </div>

    {{-- Body --}}
    <div class="auth-body">

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label">Địa chỉ Email <span class="req">*</span></label>
                <div class="input-group position-relative">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="email@gmail.com"
                           value="{{ old('email', $request->email) }}"
                           required autofocus autocomplete="username">
                </div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mật khẩu mới --}}
            <div class="form-group">
                <label class="form-label">Mật khẩu mới <span class="req">*</span></label>
                <div class="input-group position-relative has-toggle">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="pass_reset"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Ít nhất 8 ký tự"
                           required autocomplete="new-password">
                    <button class="btn-toggle-pass" type="button" onclick="togglePassword('pass_reset', this)" tabindex="-1">
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
                    <input type="password" name="password_confirmation" id="pass_reset_confirm"
                           class="form-control @error('password_confirmation') is-invalid @enderror"
                           placeholder="Nhập lại mật khẩu"
                           required autocomplete="new-password">
                    <button class="btn-toggle-pass" type="button" onclick="togglePassword('pass_reset_confirm', this)" tabindex="-1">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-auth-primary">
                <i class="bi bi-shield-check me-2"></i>Đặt Lại Mật Khẩu
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
