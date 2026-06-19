<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('role', 'customer')->first();
        $products = Product::where('is_active', true)->get();

        if (! $customer || $products->isEmpty()) {
            $this->command->warn('⚠️ Cần có user customer và sản phẩm trước!');

            return;
        }

        $statuses = [
            'pending', 'pending',
            'confirmed', 'confirmed',
            'shipping', 'shipping', 'shipping',
            'delivered', 'delivered', 'delivered',
            'delivered', 'delivered',
            'cancelled',
        ];

        // Tạo 30 đơn hàng mẫu trải dài 6 tháng
        for ($i = 0; $i < 30; $i++) {
            // Random ngày trong 6 tháng qua
            $createdAt = now()->subDays(rand(1, 180));

            // Random 1-3 sản phẩm/đơn
            $selectedProducts = $products->random(rand(1, 3));
            $subtotal = 0;
            $items = [];

            foreach ($selectedProducts as $product) {
                $qty = rand(1, 3);
                $price = $product->sale_price ?? $product->price;
                $sub = $price * $qty;
                $subtotal += $sub;

                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $product->thumbnail,
                    'price' => $price,
                    'quantity' => $qty,
                    'subtotal' => $sub,
                ];
            }

            $discount = 0;
            $total = $subtotal - $discount;
            $status = $statuses[array_rand($statuses)];

            $order = Order::create([
                'order_code' => 'DH'.$createdAt->format('Ymd').strtoupper(Str::random(4)),
                'user_id' => $customer->id,
                'coupon_id' => null,
                'receiver_name' => $customer->name,
                'receiver_phone' => '0987654321',
                'receiver_address' => '123 Đường ABC, Quận 1, TP.HCM',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => rand(0, 1) ? 'cod' : 'vnpay',
                'payment_status' => $status === 'delivered' ? 'paid' : 'pending',
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            foreach ($items as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }
        }

        $this->command->info('✅ Đã tạo 30 đơn hàng mẫu!');
    }
}
