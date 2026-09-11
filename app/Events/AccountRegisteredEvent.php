<?php

namespace App\Events;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountRegisteredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public ?VerificationCode $verificationCode;

    public function __construct(User $user, ?VerificationCode $verificationCode = null)
    {
        $this->user = $user;
        $this->verificationCode = $verificationCode;
    }
}
