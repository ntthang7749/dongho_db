<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    // Hiển thị form đăng ký
    public function showForm()
    {
        return view('auth.register');
    }

    // Bước 1: Nhận form, gửi OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'username.alpha_dash' => 'Tên đăng nhập chỉ được chứa chữ, số, dấu - và _',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được đăng ký.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Lưu tạm thông tin vào session (chờ xác nhận OTP)
        session([
            'register_data' => [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password), // đã hash
            ],
        ]);

        // Gửi OTP
        $this->otpService->sendOtp($request->email, 'register');

        return redirect()->route('auth.otp.register.form')
            ->with('success', '📧 Mã OTP đã được gửi đến '.$request->email);
    }

    // Hiển thị form nhập OTP
    public function showOtpForm()
    {
        // Nếu không có session register_data → quay về đăng ký
        if (! session('register_data')) {
            return redirect()->route('register')
                ->with('error', 'Phiên đăng ký đã hết hạn. Vui lòng thử lại.');
        }

        return view('auth.verify-otp', ['type' => 'register']);
    }

    // Bước 2: Xác nhận OTP → Tạo tài khoản
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Vui lòng nhập mã OTP.',
            'otp.digits' => 'Mã OTP phải gồm 6 chữ số.',
        ]);

        $data = session('register_data');

        if (! $data) {
            return redirect()->route('register')
                ->with('error', 'Phiên đăng ký đã hết hạn. Vui lòng thử lại.');
        }

        // Kiểm tra OTP
        if (! $this->otpService->verifyOtp($data['email'], $request->otp, 'register')) {
            return back()->with('error', '❌ Mã OTP không đúng hoặc đã hết hạn.');
        }

        // Tạo tài khoản
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'email_verified_at' => now(), // đã xác minh qua OTP
        ]);

        // Xoá session tạm
        session()->forget('register_data');

        // Đăng nhập luôn
        auth()->login($user);

        return redirect()->route('home')
            ->with('success', '🎉 Đăng ký thành công! Chào mừng '.$user->name);
    }

    // Gửi lại OTP
    public function resendOtp()
    {
        $data = session('register_data');

        if (! $data) {
            return redirect()->route('register')
                ->with('error', 'Phiên đăng ký đã hết hạn.');
        }

        $this->otpService->sendOtp($data['email'], 'register');

        return back()->with('success', '📧 Đã gửi lại mã OTP!');
    }
}
