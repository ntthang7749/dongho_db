<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Bắt đầu tạo dữ liệu mẫu...');
        $this->command->newLine();

        // ── THỨ TỰ QUAN TRỌNG: Bảng cha trước, bảng con sau ──
        $this->call([
            AdminSeeder::class,     // 1. Tạo user admin + customer
            CategorySeeder::class,  // 2. Tạo danh mục
            BrandSeeder::class,     // 3. Tạo thương hiệu
            CouponSeeder::class,    // 4. Tạo mã giảm giá
            ProductSeeder::class,   // 5. Tạo sản phẩm (cần category + brand)
            BannerSeeder::class,    // 6. Tạo banner
            NewsSeeder::class,      // 7. Tạo tin tức (cần user)
            CommentSeeder::class,   // 7.5. Tạo bình luận bài viết (cần news + user)
            OrderSeeder::class,     // 8. Tạo đơn hàng (cần user + product)
            ReviewSeeder::class,    // 9. Tạo đánh giá (cần user + product)
            ContactSeeder::class,   // 10. Tạo liên hệ/góp ý
        ]);

        $this->command->newLine();
        $this->command->info('🎉 Hoàn thành! Dữ liệu mẫu đã được tạo thành công.');
        $this->command->newLine();
        $this->command->table(
            ['Tài khoản', 'Email', 'Mật khẩu', 'Vai trò'],
            [
                ['Admin',    'admin@dongho.com',    'Admin@123',    'Admin'],
                ['Customer', 'customer@dongho.com', 'Customer@123', 'Khách hàng'],
            ]
        );
    }
}
