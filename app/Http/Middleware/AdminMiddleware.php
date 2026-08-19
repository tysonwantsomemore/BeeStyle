<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Vui lòng đăng nhập với tài khoản Quản trị viên để truy cập trang quản trị!');
        }

        if (!Auth::user()->isAdmin()) {
            return redirect()->route('client.home')
                ->with('error', 'Bạn không có quyền truy cập vào khu vực Quản trị hệ thống (Chỉ dành cho Admin)!');
        }

        return $next($request);
    }
}
