<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        // Xoá banner cũ
        Banner::truncate();

        $banners = [
            [
                'title' => 'Đồng Hồ Chính Hãng — Uy Tín 10 Năm',
                'image' => 'banners/banner-1.jpg',
                'link' => '/san-pham',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Sale Đến 40% — Đồng Hồ Nam Cao Cấp',
                'image' => 'banners/banner-2.jpg',
                'link' => '/san-pham?category=dong-ho-nam',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Bộ Sưu Tập Đồng Hồ Nữ 2024',
                'image' => 'banners/banner-3.jpg',
                'link' => '/san-pham?category=dong-ho-nu',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }

        $this->command->info('✅ Đã tạo '.count($banners).' banner!');
    }
}
