<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SessionManagerService
{
    /**
     * Tự động xoay vòng (Rotate) Session ID và CSRF Token khi đăng nhập
     */
    public function rotateSession(Request $request): void
    {
        $request->session()->regenerate();
        $request->session()->regenerateToken();
    }

    /**
     * Thu hồi toàn bộ session trên các thiết bị khác (chỉ giữ lại session hiện tại)
     */
    public function revokeOtherSessions(User $user, ?string $currentSessionId = null): int
    {
        if (!Schema::hasTable('sessions')) {
            return 0;
        }

        $query = DB::table('sessions')->where('user_id', $user->id);

        if ($currentSessionId) {
            $query->where('id', '!=', $currentSessionId);
        }

        return $query->delete();
    }

    /**
     * Đăng xuất toàn bộ mọi thiết bị
     */
    public function revokeAllSessions(User $user): int
    {
        if (!Schema::hasTable('sessions')) {
            return 0;
        }

        return DB::table('sessions')->where('user_id', $user->id)->delete();
    }
}
