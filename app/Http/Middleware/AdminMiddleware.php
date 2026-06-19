<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Chưa đăng nhập
        if (! auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập.');
        }

        // Không phải admin
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        // Tài khoản bị khoá
        if (! auth()->user()->is_active) {
            auth()->logout();

            return redirect()->route('login')
                ->with('error', '🚫 Tài khoản của bạn đã bị khoá.');
        }

        return $next($request);
    }
}
