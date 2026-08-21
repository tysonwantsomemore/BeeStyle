<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'user_name',
        'rating',
        'comment',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    protected $appends = [
        'user_avatar_url',
    ];

    public function getUserAvatarUrlAttribute(): string
    {
        if ($this->user) {
            return $this->user->avatar_url;
        }
        $name = urlencode($this->user_name ?: 'Khách Hàng');
        return "https://ui-avatars.com/api/?name={$name}&background=f59e0b&color=111827&bold=true&size=128";
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
