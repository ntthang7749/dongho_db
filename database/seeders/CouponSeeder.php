<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'percent',
                'value' => 10,
                'min_order' => 500000,
                'max_usage' => 100,
                'expires_at' => now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'SALE50K',
                'type' => 'fixed',
                'value' => 50000,
                'min_order' => 1000000,
                'max_discount' => 50000,
                'max_usage' => 50,
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
