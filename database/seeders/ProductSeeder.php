<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Đảm bảo thư mục lưu trữ tồn tại
        $destPath = storage_path('app/public/products');
        if (!File::exists($destPath)) {
            File::makeDirectory($destPath, 0755, true);
        }

        // Sao chép các tệp ảnh từ public sang storage
        $imagesToCopy = [
            'Casio/Casio G-Shock GA-2100-1A1.webp' => 'casio-g-shock-ga-2100-1a1.webp',
            'Casio/shopping.webp' => 'shopping.webp',
            'Casio/shopping (1).webp' => 'shopping-1.webp',
            'Casio/shopping (2).webp' => 'shopping-2.webp',
        ];

        foreach ($imagesToCopy as $srcName => $destName) {
            $srcFile = public_path("images/Sản phẩm/{$srcName}");
            if (File::exists($srcFile)) {
                File::copy($srcFile, "{$destPath}/{$destName}");
            }
        }

        // Lấy ID danh mục và thương hiệu từ DB
        $nam = Category::where('slug', 'dong-ho-nam')->first();
        $nu = Category::where('slug', 'dong-ho-nu')->first();
        $tuong = Category::where('slug', 'dong-ho-treo-tuong')->first();

        $casio = Brand::where('slug', 'casio')->first();
        $seiko = Brand::where('slug', 'seiko')->first();
        $citizen = Brand::where('slug', 'citizen')->first();
        $orient = Brand::where('slug', 'orient')->first();
        $tissot = Brand::where('slug', 'tissot')->first();
        $fossil = Brand::where('slug', 'fossil')->first();

        $products = [
            // ── ĐỒNG HỒ NAM ──
            [
                'name' => 'Casio G-Shock GA-2100-1A1 Đen',
                'category_id' => $nam?->id ?? 1,
                'brand_id' => $casio?->id ?? 1,
                'description' => 'Đồng hồ G-Shock GA-2100 với thiết kế mỏng nhẹ, chống va đập, chống nước 200m. Màn hình analog-digital kết hợp, phong cách thể thao hiện đại.',
                'price' => 2490000,
                'sale_price' => 1990000,
                'stock' => 25,
                'thumbnail' => 'products/casio-g-shock-ga-2100-1a1.webp',
                'sku' => 'CSO-GA2100-BLK',
                'material' => 'Nhựa carbon composite',
                'glass_material' => 'Kính khoáng',
                'band_material' => 'Dây nhựa',
                'water_resistance' => '200m',
                'movement' => 'Quartz',
                'case_size' => '45mm',
                'color' => 'Đen',
                'is_active' => true,
                'is_featured' => true,
                'rating_avg' => 4.8,
                'rating_count' => 124,
            ],
            [
                'name' => 'Casio G-Shock GW-M5610-1 Solar Wave',
                'category_id' => $nam?->id ?? 1,
                'brand_id' => $casio?->id ?? 1,
                'description' => 'Đồng hồ G-Shock năng lượng mặt trời, thu sóng vô tuyến tự động chỉnh giờ. Chống va đập, chống nước 200m.',
                'price' => 3290000,
                'sale_price' => null,
                'stock' => 18,
                'thumbnail' => 'products/casio-g-shock-ga-2100-1a1.webp',
                'sku' => 'CSO-GWM5610-BLK',
                'material' => 'Nhựa',
                'glass_material' => 'Kính khoáng',
                'band_material' => 'Dây nhựa',
                'water_resistance' => '200m',
                'movement' => 'Solar Quartz',
                'case_size' => '42mm',
                'color' => 'Đen',
                'is_active' => true,
                'is_featured' => false,
                'rating_avg' => 4.9,
                'rating_count' => 89,
            ],
            [
                'name' => 'Seiko 5 Sports SRPD55K1 Tự Động',
                'category_id' => $nam?->id ?? 1,
                'brand_id' => $seiko?->id ?? 2,
                'description' => 'Đồng hồ cơ tự động Seiko 5 Sports với cửa sổ ngày/thứ, dây thép không gỉ, mặt xanh navy sang trọng.',
                'price' => 4990000,
                'sale_price' => 4290000,
                'stock' => 12,
                'thumbnail' => 'products/shopping.webp',
                'sku' => 'SKO-SRPD55-BLU',
                'material' => 'Thép không gỉ',
                'glass_material' => 'Kính Hardlex',
                'band_material' => 'Dây thép không gỉ',
                'water_resistance' => '100m',
                'movement' => 'Automatic (4R36)',
                'case_size' => '40mm',
                'color' => 'Xanh navy',
                'is_active' => true,
                'is_featured' => true,
                'rating_avg' => 4.7,
                'rating_count' => 67,
            ],
            [
                'name' => 'Seiko Presage SARX055 Cocktail Automatic',
                'category_id' => $nam?->id ?? 1,
                'brand_id' => $seiko?->id ?? 2,
                'description' => 'Đồng hồ cơ cao cấp Seiko Presage, mặt số thiết kế cocktail lấy cảm hứng từ bầu trời đêm, vỏ thép IP vàng sang trọng.',
                'price' => 18500000,
                'sale_price' => null,
                'stock' => 5,
                'thumbnail' => 'products/shopping-1.webp',
                'sku' => 'SKO-SARX055-GLD',
                'material' => 'Thép không gỉ IP vàng',
                'glass_material' => 'Kính sapphire',
                'band_material' => 'Dây da bò nâu',
                'water_resistance' => '50m',
                'movement' => 'Automatic (4R35)',
                'case_size' => '40.5mm',
                'color' => 'Vàng champagne',
                'is_active' => true,
                'is_featured' => true,
                'rating_avg' => 4.9,
                'rating_count' => 34,
            ],
            [
                'name' => 'Citizen Eco-Drive BM7430-89E Năng Lượng Sáng',
                'category_id' => $nam?->id ?? 1,
                'brand_id' => $citizen?->id ?? 3,
                'description' => 'Đồng hồ Eco-Drive năng lượng ánh sáng, không cần thay pin, vỏ thép không gỉ mạ titan, chống nước 100m.',
                'price' => 5490000,
                'sale_price' => 4990000,
                'stock' => 20,
                'thumbnail' => 'products/shopping.webp',
                'sku' => 'CTZ-BM7430-SLV',
                'material' => 'Thép không gỉ mạ titan',
                'glass_material' => 'Kính sapphire',
                'band_material' => 'Dây thép không gỉ',
                'water_resistance' => '100m',
                'movement' => 'Eco-Drive (Light Powered)',
                'case_size' => '40mm',
                'color' => 'Bạc',
                'is_active' => true,
                'is_featured' => false,
                'rating_avg' => 4.6,
                'rating_count' => 58,
            ],
            [
                'name' => 'Orient Bambino RA-AP0003S10B Classic',
                'category_id' => $nam?->id ?? 1,
                'brand_id' => $orient?->id ?? 4,
                'description' => 'Đồng hồ cơ cổ điển Orient Bambino, thiết kế dress watch sang trọng, mặt số trắng ngà, dây da nâu.',
                'price' => 3890000,
                'sale_price' => 3290000,
                'stock' => 15,
                'thumbnail' => 'products/shopping-1.webp',
                'sku' => 'ORT-BAMBINO-WHT',
                'material' => 'Thép không gỉ',
                'glass_material' => 'Kính khoáng',
                'band_material' => 'Dây da nâu',
                'water_resistance' => '30m',
                'movement' => 'Automatic (F6922)',
                'case_size' => '40.5mm',
                'color' => 'Trắng ngà',
                'is_active' => true,
                'is_featured' => true,
                'rating_avg' => 4.5,
                'rating_count' => 45,
            ],
            [
                'name' => 'Tissot T-Classic PR 100 T101.410.11.051.00',
                'category_id' => $nam?->id ?? 1,
                'brand_id' => $tissot?->id ?? 5,
                'description' => 'Đồng hồ Tissot PR 100 Swiss Made, thiết kế thanh lịch, chống nước 100m, kính sapphire chống xước.',
                'price' => 9900000,
                'sale_price' => 8500000,
                'stock' => 8,
                'thumbnail' => 'products/shopping.webp',
                'sku' => 'TST-PR100-BLK',
                'material' => 'Thép không gỉ 316L',
                'glass_material' => 'Kính sapphire',
                'band_material' => 'Dây thép không gỉ',
                'water_resistance' => '100m',
                'movement' => 'Quartz (ETA)',
                'case_size' => '40mm',
                'color' => 'Đen',
                'is_active' => true,
                'is_featured' => true,
                'rating_avg' => 4.8,
                'rating_count' => 29,
            ],
            [
                'name' => 'Casio Edifice EFV-620D-1AVUDF Chronograph',
                'category_id' => $nam?->id ?? 1,
                'brand_id' => $casio?->id ?? 1,
                'description' => 'Đồng hồ Casio Edifice chronograph, tachymeter, thiết kế thể thao sang trọng, chống nước 100m.',
                'price' => 3490000,
                'sale_price' => 2990000,
                'stock' => 22,
                'thumbnail' => 'products/shopping.webp',
                'sku' => 'CSO-EFV620-BLK',
                'material' => 'Thép không gỉ',
                'glass_material' => 'Kính khoáng',
                'band_material' => 'Dây thép không gỉ',
                'water_resistance' => '100m',
                'movement' => 'Quartz',
                'case_size' => '43mm',
                'color' => 'Đen/Bạc',
                'is_active' => true,
                'is_featured' => false,
                'rating_avg' => 4.4,
                'rating_count' => 76,
            ],

            // ── ĐỒNG HỒ NỮ ──
            [
                'name' => 'Casio Sheen SHE-3049PG-9A Vàng Hồng',
                'category_id' => $nu?->id ?? 2,
                'brand_id' => $casio?->id ?? 1,
                'description' => 'Đồng hồ nữ Casio Sheen thiết kế sang trọng, mạ vàng hồng, đính đá Swarovski, chống nước 50m.',
                'price' => 2990000,
                'sale_price' => 2490000,
                'stock' => 20,
                'thumbnail' => 'products/shopping-2.webp',
                'sku' => 'CSO-SHE3049-GOLD',
                'material' => 'Thép không gỉ mạ vàng hồng',
                'glass_material' => 'Kính khoáng',
                'band_material' => 'Dây thép mạ vàng hồng',
                'water_resistance' => '50m',
                'movement' => 'Quartz',
                'case_size' => '33mm',
                'color' => 'Vàng hồng',
                'is_active' => true,
                'is_featured' => true,
                'rating_avg' => 4.7,
                'rating_count' => 93,
            ],
            [
                'name' => 'Citizen Eco-Drive EM0683-18A Nữ Thanh Lịch',
                'category_id' => $nu?->id ?? 2,
                'brand_id' => $citizen?->id ?? 3,
                'description' => 'Đồng hồ nữ Citizen Eco-Drive viền đính đá, mặt trắng, dây da trắng, sang trọng và nữ tính.',
                'price' => 6990000,
                'sale_price' => 5990000,
                'stock' => 10,
                'thumbnail' => 'products/shopping-2.webp',
                'sku' => 'CTZ-EM0683-WHT',
                'material' => 'Thép không gỉ',
                'glass_material' => 'Kính sapphire',
                'band_material' => 'Dây da trắng',
                'water_resistance' => '50m',
                'movement' => 'Eco-Drive',
                'case_size' => '32mm',
                'color' => 'Trắng',
                'is_active' => true,
                'is_featured' => true,
                'rating_avg' => 4.8,
                'rating_count' => 41,
            ],
            [
                'name' => 'Fossil Jacqueline ES3547 Nữ Vàng Hồng',
                'category_id' => $nu?->id ?? 2,
                'brand_id' => $fossil?->id ?? 6,
                'description' => 'Đồng hồ nữ Fossil Jacqueline thiết kế thời trang, mặt số ngọc trai, dây thép mạ vàng hồng.',
                'price' => 3490000,
                'sale_price' => 2990000,
                'stock' => 16,
                'thumbnail' => 'products/shopping-2.webp',
                'sku' => 'FSL-ES3547-RGLD',
                'material' => 'Thép không gỉ mạ vàng hồng',
                'glass_material' => 'Kính khoáng',
                'band_material' => 'Dây thép mạ vàng hồng',
                'water_resistance' => '50m',
                'movement' => 'Quartz',
                'case_size' => '36mm',
                'color' => 'Vàng hồng/Trắng',
                'is_active' => true,
                'is_featured' => false,
                'rating_avg' => 4.5,
                'rating_count' => 62,
            ],
            [
                'name' => 'Seiko Solar SUP397P1 Nữ Dây Vàng',
                'category_id' => $nu?->id ?? 2,
                'brand_id' => $seiko?->id ?? 2,
                'description' => 'Đồng hồ nữ Seiko Solar năng lượng mặt trời, viền đính đá, dây thép mạ vàng, không cần thay pin.',
                'price' => 4290000,
                'sale_price' => null,
                'stock' => 8,
                'thumbnail' => 'products/shopping-2.webp',
                'sku' => 'SKO-SUP397-GLD',
                'material' => 'Thép không gỉ mạ vàng',
                'glass_material' => 'Kính khoáng',
                'band_material' => 'Dây thép mạ vàng',
                'water_resistance' => '100m',
                'movement' => 'Solar Quartz',
                'case_size' => '29mm',
                'color' => 'Vàng',
                'is_active' => true,
                'is_featured' => false,
                'rating_avg' => 4.6,
                'rating_count' => 27,
            ],
            [
                'name' => 'Tissot T-Lady T084.210.11.117.00 Nhỏ Nhắn',
                'category_id' => $nu?->id ?? 2,
                'brand_id' => $tissot?->id ?? 5,
                'description' => 'Đồng hồ nữ Tissot T-Lady Swiss Made, thiết kế nhỏ nhắn thanh lịch, kính sapphire, dây da hồng.',
                'price' => 7990000,
                'sale_price' => 6990000,
                'stock' => 6,
                'thumbnail' => 'products/shopping-2.webp',
                'sku' => 'TST-TLADY-PINK',
                'material' => 'Thép không gỉ mạ vàng hồng',
                'glass_material' => 'Kính sapphire',
                'band_material' => 'Dây da màu hồng',
                'water_resistance' => '30m',
                'movement' => 'Quartz',
                'case_size' => '26mm',
                'color' => 'Hồng',
                'is_active' => true,
                'is_featured' => true,
                'rating_avg' => 4.9,
                'rating_count' => 18,
            ],
            [
                'name' => 'Citizen Silhouette Crystal EM0331-52A',
                'category_id' => $nu?->id ?? 2,
                'brand_id' => $citizen?->id ?? 3,
                'description' => 'Đồng hồ nữ Citizen Silhouette đính Swarovski Crystal, thiết kế sang trọng nữ tính.',
                'price' => 8500000,
                'sale_price' => 7200000,
                'stock' => 7,
                'thumbnail' => 'products/shopping.webp',
                'sku' => 'CTZ-EM0331-SLV',
                'material' => 'Thép không gỉ',
                'glass_material' => 'Kính sapphire',
                'band_material' => 'Dây thép không gỉ',
                'water_resistance' => '50m',
                'movement' => 'Eco-Drive',
                'case_size' => '28mm',
                'color' => 'Bạc/Trắng',
                'is_active' => true,
                'is_featured' => false,
                'rating_avg' => 4.7,
                'rating_count' => 22,
            ],

            // ── ĐỒNG HỒ TREO TƯỜNG ──
            [
                'name' => 'Đồng Hồ Treo Tường Seiko QXA560B Analog Cổ Điển',
                'category_id' => $tuong?->id ?? 3,
                'brand_id' => $seiko?->id ?? 2,
                'description' => 'Đồng hồ treo tường Seiko cổ điển, mặt số trắng, khung gỗ nâu, kim giờ dạ quang, chạy pin AA.',
                'price' => 890000,
                'sale_price' => 750000,
                'stock' => 30,
                'thumbnail' => 'products/shopping-1.webp',
                'sku' => 'SKO-QXA560-BRN',
                'material' => 'Khung gỗ MDF',
                'glass_material' => 'Kính trong suốt',
                'band_material' => null,
                'water_resistance' => null,
                'movement' => 'Quartz (Pin AA)',
                'case_size' => '30cm',
                'color' => 'Nâu/Trắng',
                'is_active' => true,
                'is_featured' => true,
                'rating_avg' => 4.3,
                'rating_count' => 87,
            ],
            [
                'name' => 'Đồng Hồ Treo Tường Casio IQ-150-7 Hiện Đại',
                'category_id' => $tuong?->id ?? 3,
                'brand_id' => $casio?->id ?? 1,
                'description' => 'Đồng hồ treo tường Casio thiết kế hiện đại, viền bạc, mặt số đen, kim dạ quang phát sáng ban đêm.',
                'price' => 650000,
                'sale_price' => null,
                'stock' => 40,
                'thumbnail' => 'products/shopping.webp',
                'sku' => 'CSO-IQ150-BLK',
                'material' => 'Nhựa ABS',
                'glass_material' => 'Kính trong suốt',
                'band_material' => null,
                'water_resistance' => null,
                'movement' => 'Quartz (Pin AA)',
                'case_size' => '25cm',
                'color' => 'Đen/Bạc',
                'is_active' => true,
                'is_featured' => false,
                'rating_avg' => 4.2,
                'rating_count' => 142,
            ],
            [
                'name' => 'Đồng Hồ Treo Tường Orient CF04001B Gỗ Cao Cấp',
                'category_id' => $tuong?->id ?? 3,
                'brand_id' => $orient?->id ?? 4,
                'description' => 'Đồng hồ treo tường Orient khung gỗ tự nhiên cao cấp, mặt số vân gỗ, thiết kế sang trọng cho phòng khách.',
                'price' => 1890000,
                'sale_price' => 1590000,
                'stock' => 15,
                'thumbnail' => 'products/shopping-1.webp',
                'sku' => 'ORT-CF04-WOOD',
                'material' => 'Gỗ tự nhiên',
                'glass_material' => 'Kính cường lực',
                'band_material' => null,
                'water_resistance' => null,
                'movement' => 'Quartz im lặng',
                'case_size' => '40cm',
                'color' => 'Nâu gỗ tự nhiên',
                'is_active' => true,
                'is_featured' => true,
                'rating_avg' => 4.6,
                'rating_count' => 54,
            ],
            [
                'name' => 'Đồng Hồ Treo Tường Seiko QXA791 Chime Nhạc',
                'category_id' => $tuong?->id ?? 3,
                'brand_id' => $seiko?->id ?? 2,
                'description' => 'Đồng hồ treo tường Seiko cao cấp có tính năng đổ chuông Westminster, 4/4 giờ, điều chỉnh âm lượng.',
                'price' => 3290000,
                'sale_price' => 2790000,
                'stock' => 10,
                'thumbnail' => 'products/shopping-1.webp',
                'sku' => 'SKO-QXA791-GLD',
                'material' => 'Gỗ MDF phủ gỗ thật',
                'glass_material' => 'Kính trong',
                'band_material' => null,
                'water_resistance' => null,
                'movement' => 'Quartz + Chime Westminster',
                'case_size' => '52cm',
                'color' => 'Nâu vàng đồng',
                'is_active' => true,
                'is_featured' => false,
                'rating_avg' => 4.7,
                'rating_count' => 36,
            ],
            [
                'name' => 'Đồng Hồ Treo Tường Citizen CC2104 LED Kỹ Thuật Số',
                'category_id' => $tuong?->id ?? 3,
                'brand_id' => $citizen?->id ?? 3,
                'description' => 'Đồng hồ treo tường Citizen màn hình LED lớn, hiển thị giờ, ngày, nhiệt độ, điều chỉnh từ xa.',
                'price' => 1290000,
                'sale_price' => null,
                'stock' => 25,
                'thumbnail' => 'products/shopping.webp',
                'sku' => 'CTZ-CC2104-BLK',
                'material' => 'Nhựa ABS cao cấp',
                'glass_material' => 'Không có',
                'band_material' => null,
                'water_resistance' => null,
                'movement' => 'Quartz Digital',
                'case_size' => '35cm',
                'color' => 'Đen',
                'is_active' => true,
                'is_featured' => false,
                'rating_avg' => 4.1,
                'rating_count' => 68,
            ],
        ];

        foreach ($products as $data) {
            // Tạo slug unique
            $slug = Str::slug($data['name']);
            $existingCount = Product::where('slug', 'LIKE', $slug.'%')->count();
            if ($existingCount > 0) {
                $slug .= '-'.($existingCount + 1);
            }

            // Tạo sản phẩm
            $product = Product::updateOrCreate(
                ['sku' => $data['sku']],
                array_merge($data, [
                    'slug' => $slug,
                    'view_count' => rand(50, 1500),
                ])
            );

            // Gán ảnh phụ (gallery) cho sản phẩm
            $product->images()->delete(); // xoá các ảnh cũ nếu có
            
            $galleryImages = [];
            if (isset($data['thumbnail'])) {
                if ($data['thumbnail'] === 'products/casio-g-shock-ga-2100-1a1.webp') {
                    $galleryImages = ['products/shopping.webp', 'products/shopping-1.webp'];
                } elseif ($data['thumbnail'] === 'products/shopping.webp') {
                    $galleryImages = ['products/shopping-1.webp', 'products/shopping-2.webp'];
                } elseif ($data['thumbnail'] === 'products/shopping-1.webp') {
                    $galleryImages = ['products/shopping.webp', 'products/shopping-2.webp'];
                } elseif ($data['thumbnail'] === 'products/shopping-2.webp') {
                    $galleryImages = ['products/shopping.webp', 'products/shopping-1.webp'];
                }
            }

            foreach ($galleryImages as $index => $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $img,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        $this->command->info('✅ Đã tạo '.count($products).' sản phẩm!');
    }
}
