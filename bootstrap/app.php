<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ForceHttps;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
        $middleware->web(
            prepend: [ForceHttps::class],
            append: [
                \App\Http\Middleware\SecureHeaders::class,
                \App\Http\Middleware\SanitizeInputs::class,
            ]
        );
        // Tin proxy headers từ Cloudflare/load balancer.
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_AWS_ELB
        );
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, Request $request) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => '⚠️ Bạn đã thực hiện thao tác quá nhanh. Vui lòng thử lại sau!'
                ], 429);
            }

            return back()->withInput()->with('warning', '⚠️ Bạn đã thực hiện thao tác quá nhanh. Vui lòng chờ một lát trước khi thử lại!');
        });
    })->create();
