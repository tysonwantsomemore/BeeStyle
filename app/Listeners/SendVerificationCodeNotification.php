<?php

namespace App\Listeners;

use App\Events\AccountRegisteredEvent;
use App\Events\ContactVerificationRequestedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendVerificationCodeNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Xử lý gửi OTP khi tài khoản mới đăng ký
     */
    public function handleRegistered(AccountRegisteredEvent $event): void
    {
        if (!$event->verificationCode) {
            return;
        }

        $code = $event->verificationCode->code;
        $contact = $event->verificationCode->contact;
        $user = $event->user;

        Log::info("[OTP NOTIFICATION] Gửi mã kích hoạt tài khoản ({$code}) tới {$contact}");

        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::raw("Chào {$user->name},\n\nMã xác thực kích hoạt tài khoản BeeStyle của bạn là: {$code}\nMã có hiệu lực trong 5 phút. Vui lòng không chia sẻ mã này cho bất kỳ ai!", function ($mail) use ($contact) {
                    $mail->to($contact)
                        ->subject('[BeeStyle] Mã xác thực kích hoạt tài khoản');
                });
            } catch (\Throwable $e) {
                Log::error("[OTP EMAIL FAILED] " . $e->getMessage());
            }
        }
    }

    /**
     * Xử lý gửi OTP khi yêu cầu đổi Email / Số điện thoại
     */
    public function handleContactChange(ContactVerificationRequestedEvent $event): void
    {
        $code = $event->verificationCode->code;
        $target = $event->newContactValue;
        $type = $event->contactType === 'email' ? 'Địa chỉ Email' : 'Số điện thoại';

        Log::info("[OTP CONTACT CHANGE] Gửi mã xác nhận cập nhật {$type} ({$code}) tới {$target}");

        if ($event->contactType === 'email' && filter_var($target, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::raw("Mã OTP để xác nhận thay đổi Email tài khoản BeeStyle của bạn là: {$code}\nMã có hiệu lực trong 5 phút.", function ($mail) use ($target) {
                    $mail->to($target)
                        ->subject('[BeeStyle] Xác nhận thay đổi địa chỉ Email');
                });
            } catch (\Throwable $e) {
                Log::error("[OTP CONTACT EMAIL FAILED] " . $e->getMessage());
            }
        }
    }
}
