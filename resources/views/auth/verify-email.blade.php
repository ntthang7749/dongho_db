@extends('layouts.app')
@section('title', 'Xác Minh Email — Đồng Hồ Online')
@section('content')

<div class="auth-card">

    {{-- Header --}}
    <div class="auth-header">
        <div class="auth-logo">✉️</div>
        <h1 class="auth-title">Xác Minh Email</h1>
        <p class="auth-subtitle">Xác thực tài khoản để bắt đầu mua sắm</p>
    </div>

    {{-- Body --}}
    <div class="auth-body">

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        {{-- Verification link sent status --}}
        @if (session('status') == 'verification-link-sent')
            <div class="success-badge">
                <i class="bi bi-check-circle-fill me-2" style="color: #22c55e;"></i>
                <span>{{ __('Một liên kết xác minh mới đã được gửi đến địa chỉ email bạn đã cung cấp khi đăng ký.') }}</span>
            </div>
        @endif

        <div style="background: rgba(201,168,76,0.07); border: 1px solid rgba(201,168,76,0.15); border-radius: 12px; padding: 14px 16px; margin-bottom: 24px;">
            <p style="font-size: 0.855rem; color: rgba(240,236,224,0.65); margin: 0; line-height: 1.6;">
                <i class="bi bi-info-circle me-2" style="color: var(--gold);"></i>
                {{ __('Cảm ơn bạn đã đăng ký thành viên! Trước khi bắt đầu, vui lòng xác nhận địa chỉ email của bạn bằng cách nhấn vào liên kết mà chúng tôi vừa gửi. Nếu bạn không nhận được email, chúng tôi rất sẵn lòng gửi lại một liên kết khác.') }}
            </p>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-resend">
                    <i class="bi bi-send-fill me-2"></i>{{ __('Gửi Lại Email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-back border-0 bg-transparent p-0 m-0">
                    <i class="bi bi-box-arrow-right me-1"></i>{{ __('Đăng xuất') }}
                </button>
            </form>
        </div>

    </div>
</div>

@endsection
