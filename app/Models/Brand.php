<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'banner',
        'description',
        'website',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    protected $appends = [
        'logo_url',
        'banner_url',
        'has_logo',
        'has_banner',
    ];

    public function getLogoUrlAttribute(): string
    {
        $raw = trim($this->logo ?? '', " \t\n\r\0\x0B'\"");
        if (empty($raw)) {
            return asset('assets/img/icons/icon-1.png');
        }
        if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
            return $raw;
        }
        $cleanPath = ltrim($raw, '/');
        return asset($cleanPath);
    }

    public function getBannerUrlAttribute(): string
    {
        $raw = trim($this->banner ?? '', " \t\n\r\0\x0B'\"");
        if (empty($raw)) {
            return asset('assets/img/generic/1.png');
        }
        if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
            return $raw;
        }
        $cleanPath = ltrim($raw, '/');
        return asset($cleanPath);
    }

    public function getHasLogoAttribute(): bool
    {
        $raw = trim($this->logo ?? '', " \t\n\r\0\x0B'\"");
        return !empty($raw);
    }

    public function getHasBannerAttribute(): bool
    {
        $raw = trim($this->banner ?? '', " \t\n\r\0\x0B'\"");
        return !empty($raw);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }
}
