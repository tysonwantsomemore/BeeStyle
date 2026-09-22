<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ShipperMiddleware
{
    /**
     * Xử lý kiểm tra quyền truy cập của Bưu tá giao hàng (Shipper)
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Vui lòng đăng nhập với tài khoản Bưu tá để truy cập!');
        }

        $user = Auth::user();
        if (!$user->isShipper() && !$user->isAdmin()) {
            return redirect()->route('client.home')
                ->with('error', 'Bạn không có quyền truy cập vào khu vực Bưu tá giao hàng!');
        }

        return $next($request);
    }
}
