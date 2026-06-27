<?php

if (!function_exists('img_url')) {
    /**
     * Trả về URL đúng cho ảnh — hỗ trợ cả local storage và Cloudinary.
     *
     * Dùng trong Blade: {{ img_url($product->thumbnail) }}
     * Thay thế: asset('storage/' . $product->thumbnail)
     *
     * @param  string|null  $path     Đường dẫn ảnh lưu trong DB
     * @param  string|null  $default  URL ảnh mặc định nếu $path rỗng
     */
    function img_url(?string $path, ?string $default = null): string
    {
        $default ??= asset('images/no-image.png');

        if (empty($path)) {
            return $default;
        }

        // Đã là URL đầy đủ (Google avatar, Cloudinary URL cũ...)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Dùng Storage::disk('public') — tự động dùng Cloudinary nếu đã override trong AppServiceProvider
        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    }
}
