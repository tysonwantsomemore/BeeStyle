<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'gender',
        'dob',
        'address',
        'city',
        'district',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'bank_branch',
        'password_changed_at',
        'role',
        'rank',
        'points',
        'total_spent',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'dob' => 'date',
            'password' => 'hashed',
            'points' => 'integer',
            'total_spent' => 'integer',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class)->orderBy('is_default', 'desc')->orderBy('created_at', 'desc');
    }

    public function defaultAddress()
    {
        return $this->hasOne(UserAddress::class)->where('is_default', true);
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function hasChangedPassword(): bool
    {
        return !is_null($this->password_changed_at);
    }
}
