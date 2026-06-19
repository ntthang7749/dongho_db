<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        $sanitizedInput = $this->sanitizeArray($input);
        
        // Cập nhật lại request input sau khi đã lọc sạch
        $request->merge($sanitizedInput);

        return $next($request);
    }

    /**
     * Đệ quy làm sạch mảng dữ liệu.
     */
    protected function sanitizeArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                $data[$key] = $this->sanitize($value);
            }
        }
        return $data;
    }

    /**
     * Lọc sạch XSS ra khỏi chuỗi văn bản.
     */
    protected function sanitize(string $value): string
    {
        // 1. Loại bỏ cặp thẻ <script>...</script> và nội dung bên trong
        $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);

        // 2. Loại bỏ cặp thẻ <iframe>...</iframe> và nội dung bên trong
        $value = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $value);

        // 3. Loại bỏ các inline HTML event handlers (onload, onerror, onclick,...) có dấu nháy
        $value = preg_replace('/on\w+\s*=\s*(["\'])(.*?)\1/is', '', $value);

        // 4. Loại bỏ các inline HTML event handlers không dùng dấu nháy (e.g. onerror=alert(1))
        $value = preg_replace('/on\w+\s*=\s*([^\s>]+)/is', '', $value);

        // 5. Loại bỏ giả giao thức javascript: (e.g. <a href="javascript:alert(1)">)
        $value = preg_replace('/javascript\s*:/is', '', $value);

        return $value;
    }
}
