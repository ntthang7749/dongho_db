@extends('layouts.app')
@section('title', 'Xác Nhận OTP - Đồng Hồ Online')
@section('content')

<div class="auth-card">

    {{-- Header --}}
    <div class="auth-header">
        <div class="auth-logo">📧</div>
        <h1 class="auth-title">Xác Nhận OTP</h1>
        <p class="auth-subtitle">
            {{ $type === 'register' ? 'Xác nhận tài khoản đăng ký' : 'Xác nhận đặt lại mật khẩu' }}
        </p>
    </div>

    {{-- Body --}}
    <div class="auth-body" style="text-align: center;">

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

        {{-- Icon --}}
        <div class="otp-icon-wrapper">
            <i class="bi bi-envelope-check" style="color: var(--gold); font-size: 36px;"></i>
        </div>

        <h5 style="font-size: 1.05rem; font-weight: 700; color: var(--text-primary); margin-bottom: 10px;">
            Kiểm tra hộp thư của bạn!
        </h5>
        <p style="font-size: 0.855rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 16px;">
            Chúng tôi đã gửi mã OTP gồm <strong style="color: var(--text-primary);">6 chữ số</strong> đến email.<br>
            Mã có hiệu lực trong <strong style="color: var(--gold-light);">10 phút</strong>.
        </p>

        @php
            $email = $type === 'register'
                ? (session('register_data')['email'] ?? '')
                : (session('reset_email') ?? '');
            $parts  = explode('@', $email);
            $masked = substr($parts[0], 0, 3) . '***@' . ($parts[1] ?? '');
        @endphp

        <div class="email-badge">
            <i class="bi bi-envelope-fill"></i>
            <span>{{ $masked }}</span>
        </div>

        @if($type === 'register')
            @php $verifyRoute = route('auth.otp.register.verify'); $resendRoute = route('auth.otp.register.resend'); @endphp
        @else
            @php $verifyRoute = route('auth.otp.reset.verify'); $resendRoute = route('auth.otp.reset.resend'); @endphp
        @endif

        {{-- OTP Form --}}
        <form method="POST" action="{{ $verifyRoute }}" style="margin-bottom: 16px;">
            @csrf
            <div style="margin-bottom: 20px;">
                <input type="text" name="otp" maxlength="6"
                       class="otp-input-styled @error('otp') is-invalid @enderror"
                       placeholder="• • • • • •"
                       autocomplete="one-time-code"
                       inputmode="numeric">
                @error('otp')
                    <div class="invalid-feedback text-start">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn-auth-primary">
                <i class="bi bi-check-circle me-2"></i>Xác Nhận OTP
            </button>
        </form>

        {{-- Resend OTP --}}
        <form method="POST" action="{{ $resendRoute }}" style="margin-top: 12px;">
            @csrf
            <button type="submit" class="btn-resend">
                <i class="bi bi-arrow-repeat me-1"></i>Gửi lại mã OTP
            </button>
        </form>

        {{-- Back --}}
        <div style="margin-top: 16px;">
            <a href="{{ $type === 'register' ? route('register') : route('password.request') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

    </div>
</div>

@endsection