<?php

namespace App\Listeners;

use App\Events\PasswordChangedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPasswordChangedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Xử lý gửi email thông báo khi mật khẩu được thay đổi
     */
    public function handle(PasswordChangedEvent $event): void
    {
        $user = $event->user;
        $ip = $event->ipAddress;
        $timeStr = $event->time->format('H:i:s d/m/Y');
        $device = $event->userAgent ?? 'Thiết bị không xác định';

        $message = "Mật khẩu của tài khoản {$user->email} vừa được thay đổi thành công vào lúc {$timeStr} từ địa chỉ IP {$ip} ({$device}). Nếu bạn không thực hiện thay đổi này, vui lòng liên hệ ngay tổng đài bảo mật của BeeStyle!";

        // Ghi log hệ thống an toàn
        Log::channel('single')->warning("[SECURITY ALERT] Password changed for User ID: {$user->id} | Email: {$user->email} | Time: {$timeStr} | IP: {$ip}");

        // Nếu user có email, gửi mail thông báo bảo mật
        if (!empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::raw($message, function ($mail) use ($user) {
                    $mail->to($user->email)
                        ->subject('[BeeStyle] Cảnh báo bảo mật: Mật khẩu tài khoản của bạn vừa thay đổi');
                });
            } catch (\Throwable $e) {
                Log::error("[SECURITY MAIL ERROR] Không thể gửi email thông báo đổi mật khẩu tới {$user->email}: " . $e->getMessage());
            }
        }
    }
}
