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
        'brand_id',
        'sku',
        'name',
        'slug',
        'product_type',
        'price',
        'original_price',
        'discount_percent',
        'stock',
        'sold_count',
        'views',
        'rating',
        'reviews_count',
        'image',
        'short_description',
        'description',
        'colors',
        'sizes',
        'specifications',
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
            'views' => 'integer',
            'rating' => 'float',
            'reviews_count' => 'integer',
            'colors' => 'array',
            'sizes' => 'array',
            'specifications' => 'array',
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

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('id', 'asc');
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('status', 'active')->orderBy('id', 'asc');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved')->orderBy('created_at', 'desc');
    }

    public function allReviews()
    {
        return $this->hasMany(Review::class)->orderBy('created_at', 'desc');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function hasVariants(): bool
    {
        return $this->product_type === 'variant' && $this->variants()->exists();
    }

    /**
     * Accessor tính toán % giảm giá chính xác
     */
    public function getDiscountPercentAttribute($value)
    {
        if ($value && $value > 0) {
            return (int)$value;
        }
        if ($this->original_price && $this->original_price > $this->price) {
            return (int)round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }

    public function dailyDeals()
    {
        return $this->hasMany(DailyDeal::class);
    }

    public function runningDailyDeal()
    {
        return $this->hasOne(DailyDeal::class)
            ->where('is_active', true)
            ->where(function ($q) {
                $today = now()->toDateString();
                $q->whereNull('deal_date')->orWhereDate('deal_date', $today);
            })
            ->where('start_time', '<=', now()->toTimeString())
            ->where('end_time', '>=', now()->toTimeString())
            ->where(function ($q) {
                $q->where('quantity_limit', 0)->orWhereColumn('sold_count', '<', 'quantity_limit');
            });
    }

    /**
     * Check if product currently has an active daily deal
     */
    public function getIsOnDailyDealAttribute(): bool
    {
        return (bool) $this->current_daily_deal;
    }

    /**
     * Get current active daily deal for this product
     */
    public function getCurrentDailyDealAttribute()
    {
        if ($this->relationLoaded('dailyDeals')) {
            return $this->dailyDeals->first(fn($deal) => $deal->is_running);
        }
        return $this->runningDailyDeal;
    }

    /**
     * Get effective sale price (takes daily deal into account)
     */
    public function getEffectivePriceAttribute(): int
    {
        $deal = $this->current_daily_deal;
        if ($deal) {
            return (int) ($deal->deal_price ?: round($this->price * (100 - $deal->discount_percent) / 100));
        }
        return (int) $this->price;
    }

    /**
     * Scope: Products with active daily deals
     */
    public function scopeHasDailyDeal($query)
    {
        return $query->whereHas('dailyDeals', function ($q) {
            $q->runningNow();
        });
    }

    /**
     * Accessor kiểm tra trạng thái đang kinh doanh hay tạm dừng
     */
    public function getIsActiveAttribute(): bool
    {
        return ($this->attributes['status'] ?? 'active') === 'active';
    }

    /**
     * Mutator thiết lập trạng thái kinh doanh
     */
    public function setIsActiveAttribute($value): void
    {
        $this->attributes['status'] = $value ? 'active' : 'inactive';
    }

    // Phạm vi truy vấn (Scopes)

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
