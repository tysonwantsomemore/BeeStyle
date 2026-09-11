<?php

namespace App\Events;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PasswordChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $ipAddress;
    public Carbon $time;
    public ?string $userAgent;

    /**
     * Khởi tạo sự kiện đổi mật khẩu thành công
     */
    public function __construct(User $user, string $ipAddress, Carbon $time, ?string $userAgent = null)
    {
        $this->user = $user;
        $this->ipAddress = $ipAddress;
        $this->time = $time;
        $this->userAgent = $userAgent;
    }
}
