<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnverifiedAccountException extends Exception
{
    protected $message = 'Tài khoản của bạn chưa được kích hoạt/xác thực. Vui lòng xác thực Email hoặc Số điện thoại trước khi thực hiện đặt hàng!';

    public function render(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'code'    => 'ACCOUNT_UNVERIFIED',
            ], 403);
        }

        return redirect()->route('client.profile', ['tab' => 'security'])->with('error', $this->getMessage());
    }
}
