<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyDeal extends Model
{
    use HasFactory;

    protected $table = 'daily_deals';

    protected $fillable = [
        'product_id',
        'title',
        'discount_percent',
        'deal_price',
        'deal_date',
        'start_time',
        'end_time',
        'slot_name',
        'quantity_limit',
        'sold_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_percent' => 'integer',
            'deal_price' => 'integer',
            'deal_date' => 'date',
            'quantity_limit' => 'integer',
            'sold_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationship: Deal belongs to a Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope: Deals that are active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Deals that are applicable for today
     */
    public function scopeForToday($query)
    {
        $today = now()->toDateString();
        return $query->where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('deal_date')
                  ->orWhereDate('deal_date', $today);
            });
    }

    /**
     * Scope: Deals that are currently running right now
     */
    public function scopeRunningNow($query)
    {
        $today = now()->toDateString();
        $currentTime = now()->toTimeString();

        return $query->where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('deal_date')
                  ->orWhereDate('deal_date', $today);
            })
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->where(function ($q) {
                $q->where('quantity_limit', 0)
                  ->orWhereColumn('sold_count', '<', 'quantity_limit');
            });
    }

    /**
     * Scope: Deals upcoming later today
     */
    public function scopeUpcomingToday($query)
    {
        $today = now()->toDateString();
        $currentTime = now()->toTimeString();

        return $query->where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('deal_date')
                  ->orWhereDate('deal_date', $today);
            })
            ->where('start_time', '>', $currentTime);
    }

    /**
     * Check if deal is currently active & running
     */
    public function getIsRunningAttribute(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $today = now()->toDateString();
        $currentTime = now()->toTimeString();

        $dateValid = is_null($this->deal_date) || $this->deal_date->format('Y-m-d') === $today;
        $timeValid = $this->start_time <= $currentTime && $this->end_time >= $currentTime;
        $stockValid = $this->quantity_limit == 0 || $this->sold_count < $this->quantity_limit;

        return $dateValid && $timeValid && $stockValid;
    }

    /**
     * Status label for UI display
     */
    public function getStatusLabelAttribute(): string
    {
        if (!$this->is_active) {
            return 'Đã tắt';
        }

        $today = now()->toDateString();
        $currentTime = now()->toTimeString();

        $dealDateStr = $this->deal_date ? $this->deal_date->format('Y-m-d') : null;

        if ($dealDateStr && $dealDateStr < $today) {
            return 'Đã qua ngày';
        }

        if ($dealDateStr && $dealDateStr > $today) {
            return 'Sắp diễn ra';
        }

        if ($this->start_time > $currentTime) {
            return 'Sắp mở bán (' . substr($this->start_time, 0, 5) . ')';
        }

        if ($this->end_time < $currentTime) {
            return 'Đã kết thúc (' . substr($this->end_time, 0, 5) . ')';
        }

        if ($this->quantity_limit > 0 && $this->sold_count >= $this->quantity_limit) {
            return 'Cháy hàng';
        }

        return 'Đang diễn ra';
    }

    /**
     * Badge CSS class for status
     */
    public function getStatusBadgeClassAttribute(): string
    {
        if (!$this->is_active) {
            return 'bg-secondary text-white';
        }

        $label = $this->status_label;
        if (str_starts_with($label, 'Đang diễn ra')) {
            return 'bg-danger text-white';
        }
        if (str_starts_with($label, 'Sắp')) {
            return 'bg-warning text-dark';
        }
        return 'bg-secondary-subtle text-muted';
    }

    /**
     * Get target end DateTime for countdown timer
     */
    public function getTargetEndDateTime(): Carbon
    {
        $date = $this->deal_date ? $this->deal_date->format('Y-m-d') : now()->toDateString();
        return Carbon::parse("{$date} {$this->end_time}");
    }

    /**
     * Formatted Time Slot (e.g., 08:00 - 12:00)
     */
    public function getFormattedSlotAttribute(): string
    {
        if ($this->slot_name) {
            return $this->slot_name;
        }
        $start = substr($this->start_time, 0, 5);
        $end = substr($this->end_time, 0, 5);
        return "{$start} - {$end}";
    }

    /**
     * Calculate savings amount
     */
    public function getSavingsAmountAttribute(): int
    {
        if (!$this->product) {
            return 0;
        }
        $basePrice = $this->product->original_price ?: $this->product->price;
        return max(0, $basePrice - ($this->deal_price ?: $this->product->price));
    }
}
