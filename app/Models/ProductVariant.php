<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'color',
        'color_code',
        'size',
        'material',
        'price',
        'original_price',
        'stock',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'original_price' => 'integer',
            'stock' => 'integer',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'active' && $this->stock > 0;
    }

    public function getIsSaleActiveAttribute(): bool
    {
        $product = $this->relationLoaded('product') ? $this->product : null;
        return $product ? $product->is_sale_active : false;
    }

    public function getEffectivePriceAttribute(): int
    {
        $product = $this->relationLoaded('product') ? $this->product : null;
        if ($product && ($product->sale_starts_at || $product->sale_ends_at)) {
            if (!$product->is_sale_active) {
                return (int) ($this->original_price ?: ($product->original_price ?: $this->price));
            }
        }
        return (int) ($this->price ?: ($this->original_price ?: 0));
    }
}
