<?php

namespace App\Events;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactVerificationRequestedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public VerificationCode $verificationCode;
    public string $contactType; // 'email' hoặc 'phone'
    public string $newContactValue;

    public function __construct(User $user, VerificationCode $verificationCode, string $contactType, string $newContactValue)
    {
        $this->user = $user;
        $this->verificationCode = $verificationCode;
        $this->contactType = $contactType;
        $this->newContactValue = $newContactValue;
    }
}
