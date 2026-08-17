<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $orders = EcommerceDataService::getOrders();
        $user = [
            'name' => 'Nguyễn Văn Hùng',
            'email' => 'hung.nguyen@gmail.com',
            'phone' => '0987 654 321',
            'address' => 'Số 45 Đường Lê Duẩn, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh',
            'rank' => 'Thành viên Bạc (Silver)',
            'points' => 1250,
            'avatar' => '/assets/img/team/40x40/58.webp'
        ];

        return view('client.profile', compact('user', 'orders'));
    }
}
