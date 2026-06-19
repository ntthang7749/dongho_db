<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Đồng Hồ Nam',         'slug' => 'dong-ho-nam'],
            ['name' => 'Đồng Hồ Nữ',          'slug' => 'dong-ho-nu'],
            ['name' => 'Đồng Hồ Treo Tường',  'slug' => 'dong-ho-treo-tuong'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['is_active' => true, 'sort_order' => 0])
            );
        }
    }
}
