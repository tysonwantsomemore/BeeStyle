<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdministrativeMismatchException extends Exception
{
    protected $message = 'Địa giới hành chính không hợp lệ: Phường/Xã không thuộc Quận/Huyện hoặc Tỉnh/Thành phố đã chọn.';

    public function render(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'code'    => 'ADMINISTRATIVE_MISMATCH',
            ], 422);
        }

        return back()->with('error', $this->getMessage());
    }
}
