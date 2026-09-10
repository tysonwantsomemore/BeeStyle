<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StepUpAuthenticationException extends Exception
{
    protected $message = 'Xác thực bảo mật bổ sung không thành công. Mật khẩu hoặc mã OTP xác nhận không chính xác.';

    public function render(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'code'    => 'STEP_UP_AUTH_REQUIRED',
            ], 403);
        }

        return back()->withErrors(['step_up_auth' => $this->getMessage()]);
    }
}
