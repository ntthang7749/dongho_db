<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    // BƯỚC 1: Form nhập email
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // BƯỚC 1: Gửi OTP đến email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.exists' => 'Email này chưa được đăng ký.',
        ]);

        // Lưu email vào session
        session(['reset_email' => $request->email]);

        $this->otpService->sendOtp($request->email, 'reset_password');

        return redirect()->route('auth.otp.reset.form')
            ->with('success', '📧 Mã OTP đã được gửi đến '.$request->email);
    }

    // BƯỚC 2: Form nhập OTP
    public function showOtpForm()
    {
        if (! session('reset_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Phiên làm việc hết hạn. Vui lòng thử lại.');
        }

        return view('auth.verify-otp', ['type' => 'reset_password']);
    }

    // BƯỚC 2: Xác nhận OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Vui lòng nhập mã OTP.',
            'otp.digits' => 'Mã OTP phải gồm 6 chữ số.',
        ]);

        $email = session('reset_email');

        if (! $email) {
            return redirect()->route('password.request')
                ->with('error', 'Phiên làm việc hết hạn.');
        }

        if (! $this->otpService->verifyOtp($email, $request->otp, 'reset_password')) {
            return back()->with('error', '❌ Mã OTP không đúng hoặc đã hết hạn.');
        }

        // Lưu trạng thái đã xác minh OTP
        session(['reset_verified' => true]);

        return redirect()->route('auth.new-password.form');
    }

    // BƯỚC 3: Form đặt mật khẩu mới
    public function showNewPasswordForm()
    {
        if (! session('reset_verified') || ! session('reset_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Vui lòng xác minh OTP trước.');
        }

        return view('auth.new-password');
    }

    // BƯỚC 3: Cập nhật mật khẩu mới
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu phải ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $email = session('reset_email');

        if (! $email || ! session('reset_verified')) {
            return redirect()->route('password.request')
                ->with('error', 'Phiên làm việc hết hạn.');
        }

        // Cập nhật mật khẩu
        User::where('email', $email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Xoá session
        session()->forget(['reset_email', 'reset_verified']);

        return redirect()->route('login')
            ->with('success', '✅ Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
    }

    // Gửi lại OTP reset password
    public function resendOtp()
    {
        $email = session('reset_email');
        if (! $email) {
            return redirect()->route('password.request');
        }
        $this->otpService->sendOtp($email, 'reset_password');

        return back()->with('success', '📧 Đã gửi lại mã OTP!');
    }
}
