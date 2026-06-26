<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // Hiển thị form đăng nhập
    public function showForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',   // email HOẶC username
            'password' => 'required|string',
        ], [
            'login.required' => 'Vui lòng nhập email hoặc tên đăng nhập.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // Rate limit: chặn brute force (5 lần/phút)
        $key = 'login_'.Str::lower($request->login).'_'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->with('error',
                "⛔ Quá nhiều lần thử. Vui lòng thử lại sau {$seconds} giây.");
        }

        // Xác định login bằng email hay username
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $loginField => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Kiểm tra tài khoản bị khoá
            if (! auth()->user()->is_active) {
                Auth::logout();

                return back()->with('error', '🚫 Tài khoản của bạn đã bị khoá.');
            }

            RateLimiter::clear($key);
            $request->session()->regenerate();

            // Admin → trang admin, customer → trang chủ
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('home'));
        }

        // Tăng số lần thất bại
        RateLimiter::hit($key, 60);

        return back()
            ->with('error', '❌ Email/Tên đăng nhập hoặc mật khẩu không đúng.')
            ->withInput(['login' => $request->login]);
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', '👋 Đã đăng xuất thành công!');
    }
}
