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

    protected $appends = [
        'avatar_url',
        'actual_total_spent',
    ];

    /**
     * Lấy tổng chi tiêu thực tế của khách hàng từ tất cả các đơn hàng không bị hủy
     */
    public function getActualTotalSpentAttribute(): int
    {
        if ($this->relationLoaded('orders')) {
            $sum = (int) $this->orders->where('shipping_status', '!=', 'cancelled')->sum('total_amount');
            if ($sum > 0) {
                return $sum;
            }
        }

        $spent = (int) $this->orders()->where('shipping_status', '!=', 'cancelled')->sum('total_amount');
        if ($spent > 0) {
            return $spent;
        }

        return (int) ($this->attributes['total_spent'] ?? 0);
    }

    /**
     * Lấy URL avatar chuẩn xác 100% của khách hàng
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->avatar)) {
            $cleanPath = ltrim($this->avatar, '/');
            if (file_exists(public_path($cleanPath))) {
                return asset($cleanPath);
            }
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
        }

        
        // Sinh avatar chuẩn nhận diện thương hiệu theo tên tài khoản khách
        $name = urlencode($this->name ?: 'Khách Hàng');
        return "https://ui-avatars.com/api/?name={$name}&background=f59e0b&color=111827&bold=true&size=128";
    }

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

    /**
     * Lấy danh sách các món hàng thuộc các đơn hoàn tất/đã giao mà khách hàng CHƯA đánh giá
     */
    public function getPendingReviewItems()
    {
        $reviewedProductIds = $this->reviews()->pluck('product_id')->toArray();

        return OrderItem::whereHas('order', function ($q) {
            $q->where('user_id', $this->id)
              ->whereIn('shipping_status', ['completed', 'delivered']);
        })
        ->whereNotIn('product_id', $reviewedProductIds)
        ->with(['product', 'order'])
        ->latest()
        ->get()
        ->unique('product_id');
    }

    /**
     * Lấy các món hàng thuộc các đơn hoàn tất CHƯA từng được thông báo pop-up lần nào (review_notified = false)
     * Đảm bảo chỉ gửi/bật pop-up thông báo đúng 1 LẦN DUY NHẤT cho mỗi đơn hàng
     */
    public function getUnnotifiedPendingReviewItems()
    {
        $reviewedProductIds = $this->reviews()->pluck('product_id')->toArray();

        return OrderItem::whereHas('order', function ($q) {
            $q->where('user_id', $this->id)
              ->whereIn('shipping_status', ['completed', 'delivered'])
              ->where('review_notified', false);
        })
        ->whereNotIn('product_id', $reviewedProductIds)
        ->with(['product', 'order'])
        ->latest()
        ->get()
        ->unique('product_id');
    }

    /**
     * Đánh dấu các đơn hàng đã được gửi thông báo đánh giá 1 lần thành công
     */
    public function markOrdersAsReviewNotified(array $orderIds): void
    {
        if (!empty($orderIds)) {
            Order::where('user_id', $this->id)->whereIn('id', $orderIds)->update(['review_notified' => true]);
        }
    }

    /**
     * Kiểm tra người dùng có sản phẩm cần đánh giá hay không
     */
    public function hasPendingReviews(): bool
    {
        return $this->getPendingReviewItems()->isNotEmpty();
    }
}


