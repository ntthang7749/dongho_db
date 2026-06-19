<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\Otp;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    // Tạo OTP mới và gửi email
    public function sendOtp(string $email, string $type): string
    {
        // Xoá OTP cũ của email + type này
        Otp::where('email', $email)->where('type', $type)->delete();

        // Tạo mã 6 số ngẫu nhiên
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Lưu vào DB, hết hạn sau 10 phút
        Otp::create([
            'email' => $email,
            'otp' => $otp,
            'type' => $type,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Gửi email
        Mail::to($email)->send(new OtpMail($otp, $type));

        return $otp;
    }

    // Xác minh OTP
    public function verifyOtp(string $email, string $otp, string $type): bool
    {
        $record = Otp::where('email', $email)
            ->where('otp', $otp)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $record) {
            return false;
        }

        // Đánh dấu đã dùng
        $record->update(['is_used' => true]);

        return true;
    }
}
