<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'discount_type',
        'discount_value',
        'min_order_value',
        'max_discount_value',
        'total_limit',
        'used_count',
        'start_date',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'integer',
        'min_order_value' => 'integer',
        'max_discount_value' => 'integer',
        'total_limit' => 'integer',
        'used_count' => 'integer',
        'start_date' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValidForOrder(int $orderSubtotal): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->total_limit > 0 && $this->used_count >= $this->total_limit) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->start_date && $this->start_date->isFuture()) {
            return false;
        }

        if ($orderSubtotal < $this->min_order_value) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(int $orderSubtotal): int
    {
        if (!$this->isValidForOrder($orderSubtotal)) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            $discount = round(($orderSubtotal * $this->discount_value) / 100);
            if ($this->max_discount_value && $discount > $this->max_discount_value) {
                $discount = $this->max_discount_value;
            }
            return (int)$discount;
        }

        if ($this->discount_type === 'shipping') {
            return min($this->discount_value, 30000);
        }

        return min($this->discount_value, $orderSubtotal);
    }
}
