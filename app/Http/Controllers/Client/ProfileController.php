<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            // Pick default customer for guest preview
            $user = User::where('role', 'customer')->first() ?? (object)[
                'name' => 'Khách hàng BeeStyle',
                'email' => 'guest@beestyle.com',
                'phone' => '0987654321',
                'address' => 'Số 45 Đường Lê Duẩn, Quận 1, TP. Hồ Chí Minh',
                'rank' => 'Thành viên Bạc (Silver)',
                'points' => 1250,
                'avatar' => '/assets/img/team/40x40/58.webp',
            ];
            $orders = Order::with('items')->latest()->take(5)->get();
        } else {
            $orders = Order::with('items')->where('user_id', $user->id)->latest()->get();
        }

        return view('client.profile', compact('user', 'orders'));
    }
}
