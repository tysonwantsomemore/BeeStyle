<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ShipperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'shipper1@beestyle.vn'],
            [
                'name' => 'Nguyễn Văn Giao (Bưu Tá 1)',
                'phone' => '0912345678',
                'role' => 'shipper',
                'status' => 'active',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'avatar' => '/assets/img/team/40x40/58.webp',
                'address' => 'Đội giao vận BeeStyle Express - Khu vực Cầu Giấy & Đống Đa',
                'city' => 'Hà Nội',
                'district' => 'Cầu Giấy',
            ]
        );

        User::updateOrCreate(
            ['email' => 'shipper2@beestyle.vn'],
            [
                'name' => 'Trần Văn Vận (Bưu Tá 2)',
                'phone' => '0987654321',
                'role' => 'shipper',
                'status' => 'active',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'avatar' => '/assets/img/team/40x40/59.webp',
                'address' => 'Đội giao vận BeeStyle Express - Khu vực Thanh Xuân & Nam Từ Liêm',
                'city' => 'Hà Nội',
                'district' => 'Thanh Xuân',
            ]
        );
    }
}
