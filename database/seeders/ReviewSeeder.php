<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'customer')->first();
        $products = Product::where('is_active', true)->take(10)->get();

        $comments = [
            5 => [
                'Sản phẩm rất đẹp, đúng như mô tả. Giao hàng nhanh, đóng gói cẩn thận!',
                'Mình mua tặng bạn trai, anh ấy rất thích. Chất lượng tốt, giá hợp lý.',
                'Đồng hồ đẹp hơn ảnh, dây đeo thoải mái. Sẽ ủng hộ shop lần sau!',
                'Hàng chính hãng, tem nhãn đầy đủ. Rất hài lòng với sản phẩm.',
            ],
            4 => [
                'Sản phẩm ổn, chỉ hơi lâu giao hàng một chút nhưng không sao.',
                'Đồng hồ đẹp nhưng dây hơi cứng ban đầu. Đeo vài ngày quen rồi ổn.',
                'Chất lượng tốt so với giá tiền. Mình khá hài lòng.',
            ],
            3 => [
                'Sản phẩm tạm được, không khác nhiều so với đồng hồ thông thường.',
                'Màu sắc không đúng 100% như ảnh nhưng vẫn đẹp.',
            ],
        ];

        foreach ($products as $product) {
            // Tạo 3-8 đánh giá mỗi sản phẩm
            $reviewCount = rand(3, 8);

            for ($j = 0; $j < $reviewCount; $j++) {
                $rating = $this->weightedRandom();

                $commentPool = $comments[$rating] ?? $comments[5];
                $comment = $commentPool[array_rand($commentPool)];

                Review::create([
                    'user_id' => $user?->id ?? 1,
                    'product_id' => $product->id,
                    'rating' => $rating,
                    'comment' => $comment,
                    'status' => 'approved',
                    'created_at' => now()->subDays(rand(1, 60)),
                ]);
            }

            // Cập nhật rating trung bình
            $avg = Review::where('product_id', $product->id)
                ->where('status', 'approved')->avg('rating');
            $count = Review::where('product_id', $product->id)
                ->where('status', 'approved')->count();

            $product->update([
                'rating_avg' => round($avg, 2),
                'rating_count' => $count,
            ]);
        }

        $this->command->info('✅ Đã tạo đánh giá cho '.$products->count().' sản phẩm!');
    }

    // Random rating có trọng số (ưu tiên 4-5 sao)
    private function weightedRandom(): int
    {
        $weights = [5 => 50, 4 => 30, 3 => 15, 2 => 3, 1 => 2];
        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $rating => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $rating;
            }
        }

        return 5;
    }
}
