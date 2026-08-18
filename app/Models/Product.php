<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'slug',
        'price',
        'original_price',
        'discount_percent',
        'stock',
        'sold_count',
        'rating',
        'reviews_count',
        'image',
        'short_description',
        'description',
        'colors',
        'sizes',
        'is_new',
        'is_featured',
        'is_best_seller',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'original_price' => 'integer',
            'discount_percent' => 'integer',
            'stock' => 'integer',
            'sold_count' => 'integer',
            'rating' => 'float',
            'reviews_count' => 'integer',
            'colors' => 'array',
            'sizes' => 'array',
            'is_new' => 'boolean',
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if ($product->original_price && $product->original_price > $product->price) {
                $product->discount_percent = round((($product->original_price - $product->price) / $product->original_price) * 100);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved')->orderBy('created_at', 'desc');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('status', 'active')->where('is_featured', true);
    }

    public function scopeBestSeller($query)
    {
        return $query->where('status', 'active')->where('is_best_seller', true);
    }

    public function scopeNewArrivals($query)
    {
        return $query->where('status', 'active')->where('is_new', true);
    }
}
