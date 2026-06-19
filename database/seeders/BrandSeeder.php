<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Casio' => 'Casio.png',
            'Seiko' => 'Seiko.png',
            'Citizen' => 'citizen.png',
            'Orient' => 'Orient.png',
            'Rolex' => 'Rolex.png',
            'Tissot' => 'Tissit.png',
            'Longines' => 'Longines.jpg',
            'Omega' => 'Omega.png',
            'Tag Heuer' => 'Tag Heure.png',
            'Fossil' => 'Fossil.png'
        ];

        // Đảm bảo thư mục đích tồn tại
        $destPath = storage_path('app/public/brands');
        if (!File::exists($destPath)) {
            File::makeDirectory($destPath, 0755, true);
        }

        foreach ($brands as $name => $logoFile) {
            $slug = Str::slug($name);
            $logoPath = null;

            // Sao chép logo từ public sang storage
            $srcFile = public_path("images/Logo/{$logoFile}");
            if (File::exists($srcFile)) {
                $ext = File::extension($srcFile);
                $newName = "{$slug}.{$ext}";
                File::copy($srcFile, "{$destPath}/{$newName}");
                $logoPath = "brands/{$newName}";
            }

            Brand::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'slug' => $slug,
                    'description' => "Thương hiệu đồng hồ {$name} nổi tiếng thế giới.",
                    'logo' => $logoPath,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✅ Đã tạo & gán logo cho ' . count($brands) . ' thương hiệu!');
    }
}
