<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Redirect HTTP -> HTTPS in production only.
     * Local (XAMPP, php artisan serve) tiếp tục dùng HTTP để không vỡ dev flow.
     * Có thể override bằng env FORCE_HTTPS=true.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $shouldForce = app()->environment('production')
            || filter_var(env('FORCE_HTTPS', false), FILTER_VALIDATE_BOOLEAN);

        if ($shouldForce && ! $request->secure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // Khi đứng sau Cloudflare/proxy, set scheme từ X-Forwarded-Proto
        if ($shouldForce) {
            \URL::forceScheme('https');
        }

        return $next($request);
    }
}
