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
        'images',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'images' => 'array',
    ];

    protected $appends = [
        'user_avatar_url',
        'images_urls',
        'has_images',
    ];

    public function getUserAvatarUrlAttribute(): string
    {
        if ($this->user) {
            return $this->user->avatar_url;
        }
        $name = urlencode($this->user_name ?: 'Khách Hàng');
        return "https://ui-avatars.com/api/?name={$name}&background=f59e0b&color=111827&bold=true&size=128";
    }

    public function getImagesUrlsAttribute(): array
    {
        $raw = $this->images;
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            $raw = is_array($decoded) ? $decoded : [$raw];
        }
        if (empty($raw) || !is_array($raw)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($img) {
            if (empty($img) || !is_string($img)) return null;
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                return $img;
            }
            return asset(ltrim($img, '/'));
        }, $raw)));
    }

    public function getHasImagesAttribute(): bool
    {
        return !empty($this->images_urls);
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
