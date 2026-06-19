@extends('layouts.app')
@section('title', 'Quên Mật Khẩu - Đồng Hồ Online')
@section('content')

<div class="auth-card">

    {{-- Header --}}
    <div class="auth-header">
        <div class="auth-logo">🔐</div>
        <h1 class="auth-title">Khôi Phục Mật Khẩu</h1>
        <p class="auth-subtitle">Nhập email để nhận mã OTP xác nhận</p>
    </div>

    {{-- Body --}}
    <div class="auth-body">

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        {{-- Info text --}}
        <div style="background: rgba(201,168,76,0.07); border: 1px solid rgba(201,168,76,0.15); border-radius: 12px; padding: 14px 16px; margin-bottom: 24px;">
            <p style="font-size: 0.855rem; color: rgba(240,236,224,0.65); margin: 0; line-height: 1.6;">
                <i class="bi bi-info-circle me-2" style="color: var(--gold);"></i>
                Chúng tôi sẽ gửi mã OTP 6 chữ số đến email đăng ký của bạn. Mã có hiệu lực <strong style="color: var(--gold-light);">10 phút</strong>.
            </p>
        </div>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group" style="margin-bottom: 28px;">
                <label class="form-label">Địa chỉ Email <span class="req">*</span></label>
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
            </div>

            <button type="submit" class="btn-auth-primary">
                <i class="bi bi-send me-2"></i>Gửi Mã OTP
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
            </a>
        </div>

    </div>
</div>

@endsection