<?php

namespace App\Providers;

use App\Services\GeminiService;
use App\Services\OtpService;
use App\Services\VietQRService;
use App\Services\VNPayService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // OTP Service
        $this->app->singleton(OtpService::class);

        // VNPay Service
        $this->app->singleton(VNPayService::class);
        $this->app->singleton(VietQRService::class);

        // AI
        $this->app->singleton(GeminiService::class);
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Layout site dùng Bootstrap 5 (CDN), không dùng Tailwind cho paginator.
        // Mặc định Laravel render link phân trang bằng Tailwind classes — không có
        // CSS Tailwind sẽ làm SVG mũi tên khổng lồ + link rời rạc. Override sang BS5.
        Paginator::useBootstrapFive();

        // 1. Giới hạn tần suất gọi các API AI (Tối đa 15 lần/phút) để ngăn spam và tiết kiệm chi phí
        RateLimiter::for('ai-operations', function (Request $request) {
            return Limit::perMinute(15)->by($request->user()?->id ?: $request->ip());
        });

        // 2. Giới hạn tần suất gửi các biểu mẫu công cộng (Tối đa 5 lần/phút) để chống spam bot
        RateLimiter::for('public-submissions', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });
    }
}
