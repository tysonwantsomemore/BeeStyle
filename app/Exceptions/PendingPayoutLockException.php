<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PendingPayoutLockException extends Exception
{
    protected $message = 'Không thể thay đổi thông tin ngân hàng do tài khoản đang có yêu cầu đổi trả / hoàn tiền chưa hoàn tất.';

    public function render(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'code'    => 'PENDING_PAYOUT_LOCKED',
            ], 422);
        }

        return back()->with('error', $this->getMessage());
    }
}
