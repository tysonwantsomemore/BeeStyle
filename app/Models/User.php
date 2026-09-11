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
        'phone_verified_at',
        'email_verified_at',
        'locked_until',
        'failed_login_attempts',
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
        'is_verified',
    ];

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

    /**
     * Tổng chi tiêu thực tế của khách hàng (tính từ các đơn hàng thành công / không bị hủy)
     */
    public function getActualTotalSpentAttribute(): int
    {
        if (array_key_exists('actual_total_spent', $this->attributes)) {
            return (int) $this->attributes['actual_total_spent'];
        }

        if ($this->relationLoaded('orders')) {
            return (int) $this->orders->where('shipping_status', '!=', 'cancelled')->sum('total_amount');
        }

        return (int) ($this->total_spent ?? 0);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at'      => 'datetime',
            'phone_verified_at'      => 'datetime',
            'password_changed_at'    => 'datetime',
            'locked_until'           => 'datetime',
            'failed_login_attempts'  => 'integer',
            'dob'                    => 'date',
            'password'               => 'hashed',
            'points'                 => 'integer',
            'total_spent'            => 'integer',
        ];
    }

    public function getIsVerifiedAttribute(): bool
    {
        return $this->isVerified();
    }

    /**
     * Kiểm tra tài khoản đã được xác thực Email hoặc Số điện thoại chưa
     */
    public function isVerified(): bool
    {
        return !is_null($this->email_verified_at) || !is_null($this->phone_verified_at);
    }

    /**
     * Kiểm tra tài khoản có đang bị khóa tạm thời do nhập sai mật khẩu nhiều lần không
     */
    public function isLocked(): bool
    {
        return !is_null($this->locked_until) && now()->isBefore($this->locked_until);
    }

    public function verificationCodes()
    {
        return $this->hasMany(VerificationCode::class);
    }

    public function pendingContacts()
    {
        return $this->hasMany(UserPendingContact::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getIsAdminAttribute(): bool
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

    public function returns()
    {
        return $this->hasMany(OrderReturn::class)->orderBy('created_at', 'desc');
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


