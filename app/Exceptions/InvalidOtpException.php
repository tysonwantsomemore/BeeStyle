<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvalidOtpException extends Exception
{
    protected $message = 'Mã xác thực OTP không chính xác hoặc đã hết hạn sử dụng.';

    public function render(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'code'    => 'INVALID_OTP',
            ], 422);
        }

        return back()->withErrors(['otp' => $this->getMessage()]);
    }
}
