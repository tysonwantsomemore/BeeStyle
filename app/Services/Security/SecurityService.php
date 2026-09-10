<?php

namespace App\Services\Security;

use App\Events\PasswordChangedEvent;
use App\Models\User;
use App\Services\Auth\SessionManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SecurityService
{
    protected SessionManagerService $sessionManager;

    public function __construct(SessionManagerService $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * Xử lý đổi mật khẩu an toàn bọc trong DB Transaction, thu hồi các phiên đăng nhập khác và bắn sự kiện cảnh báo
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword, Request $request): bool
    {
        // 1. Kiểm tra hash mật khẩu hiện tại
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Mật khẩu hiện tại không chính xác. Vui lòng kiểm tra lại!'],
            ]);
        }

        // 2. Đảm bảo mật khẩu mới khác mật khẩu cũ
        if (Hash::check($newPassword, $user->password)) {
            throw ValidationException::withMessages([
                'new_password' => ['Mật khẩu mới không được trùng với mật khẩu đang sử dụng.'],
            ]);
        }

        // 3. Thực thi cập nhật trong DB Transaction
        DB::transaction(function () use ($user, $newPassword, $request) {
            $user->update([
                'password'            => Hash::make($newPassword),
                'password_changed_at' => now(),
            ]);

            // Thu hồi toàn bộ session trên các thiết bị khác (ngoại trừ phiên hiện tại)
            $currentSessionId = $request->session()->getId();
            $this->sessionManager->revokeOtherSessions($user, $currentSessionId);

            // Bắn sự kiện đổi mật khẩu thành công để gửi email cảnh báo bảo mật
            event(new PasswordChangedEvent(
                $user,
                $request->ip(),
                now(),
                $request->userAgent()
            ));
        });

        return true;
    }
}
