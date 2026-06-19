<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Chuyển hướng sang Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google callback
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', '❌ Đăng nhập Google thất bại. Vui lòng thử lại.');
        }

        // Tìm user theo google_id hoặc email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Đã có tài khoản → cập nhật google_id nếu chưa có
            if (! $user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            // Kiểm tra bị khoá
            if (! $user->is_active) {
                return redirect()->route('login')
                    ->with('error', '🚫 Tài khoản của bạn đã bị khoá.');
            }
        } else {
            // Chưa có tài khoản → tạo mới
            $username = $this->generateUsername($googleUser->getName());

            $user = User::create([
                'name' => $googleUser->getName(),
                'username' => $username,
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => null, // Google login không cần password
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home')
            ->with('success', '🎉 Đăng nhập bằng Google thành công!');
    }

    // Tự động tạo username từ tên Google
    private function generateUsername(string $name): string
    {
        // Chuyển tên thành username: "Nguyễn Văn A" → "nguyenvana"
        $base = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
        $base = $base ?: 'user';

        $username = $base;
        $counter = 1;

        // Nếu trùng → thêm số vào sau
        while (User::where('username', $username)->exists()) {
            $username = $base.$counter;
            $counter++;
        }

        return $username;
    }
}
