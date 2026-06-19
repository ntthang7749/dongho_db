<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@dongho.com'],
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'email' => 'admin@dongho.com',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Tài khoản khách hàng test
        User::updateOrCreate(
            ['email' => 'customer@dongho.com'],
            [
                'name' => 'Nguyễn Văn A',
                'username' => 'customer',
                'email' => 'customer@dongho.com',
                'password' => Hash::make('Customer@123'),
                'role' => 'customer',
                'phone' => '0987654321',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
