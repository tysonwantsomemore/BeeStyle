<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'contact',
        'type',
        'code',
        'token',
        'attempts',
        'max_attempts',
        'is_used',
        'expires_at',
        'used_at',
        'payload',
        'ip_address',
    ];

    protected $casts = [
        'is_used'      => 'boolean',
        'expires_at'   => 'datetime',
        'used_at'      => 'datetime',
        'payload'      => 'array',
        'attempts'     => 'integer',
        'max_attempts' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired() && $this->attempts < $this->max_attempts;
    }
}
