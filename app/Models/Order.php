<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'city',
        'district',
        'notes',
        'payment_method',
        'payment_status',
        'momo_trans_id',
        'shipping_status',
        'status_step',
        'subtotal',
        'discount_amount',
        'shipping_fee',
        'total_amount',
        'coupon_code',
        'admin_notes',
        'review_notified',
        'cancel_reason',
        'cancelled_by',
        'cancelled_at',
    ];

    protected $casts = [
        'status_step' => 'integer',
        'subtotal' => 'integer',
        'discount_amount' => 'integer',
        'shipping_fee' => 'integer',
        'total_amount' => 'integer',
        'review_notified' => 'boolean',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function returns()
    {
        return $this->hasMany(OrderReturn::class);
    }

    public function latestReturn()
    {
        return $this->hasOne(OrderReturn::class)->latestOfMany();
    }

    /**
     * Khách hàng có thể tự hủy đơn khi đơn chưa bàn giao đóng gói/giao hàng
     */
    public function canBeCancelledByCustomer(): bool
    {
        return in_array($this->shipping_status, ['pending', 'confirmed']) && $this->shipping_status !== 'cancelled';
    }

    /**
     * Khách hàng có thể yêu cầu đổi trả khi đơn đã giao hàng thành công
     */
    public function canBeReturnedByCustomer(): bool
    {
        if (!in_array($this->shipping_status, ['delivered', 'completed']) && $this->status_step < 5) {
            return false;
        }

        // Kiểm tra xem đã có yêu cầu đổi trả đang chờ xử lý hay chưa
        $hasPendingReturn = $this->returns()->whereIn('status', ['pending', 'approved', 'received', 'completed'])->exists();
        return !$hasPendingReturn;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->shipping_status) {
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang đóng gói',
            'shipping' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'completed' => 'Hoàn tất',
            'cancelled' => 'Đã hủy',
            default => 'Đang xử lý',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match (strtoupper((string)$this->payment_status)) {
            'PAID' => 'Đã thanh toán',
            'REFUNDED' => 'Đã hoàn tiền',
            'PENDING_PAYMENT', 'UNPAID' => 'Chờ thanh toán',
            'PAYMENT_FAILED' => 'Thanh toán thất bại',
            'CANCELLED' => 'Đã hủy',
            'EXPIRED' => 'Hết hạn',
            default => 'Chưa thanh toán',
        };
    }

    public function getPaymentMethodNameAttribute(): string
    {
        return match ($this->payment_method) {
            'online' => 'Thanh toán Online (ATM/Banking/Visa)',
            'momo' => 'Thanh toán trực tuyến qua MoMo',
            'zalopay' => 'Ví Điện Tử ZaloPay',
            'vietqr' => 'Chuyển khoản VietQR',
            'vnpay' => 'Cổng VNPAY',
            'exchange' => 'Đơn Đổi Hàng (0₫ - Bảo hành RMA)',
            default => 'Thanh toán khi nhận hàng (COD)',
        };
    }

    public function getPaymentMethodBadgeAttribute(): string
    {
        return match ($this->payment_method) {
            'online' => '<span class="badge bg-info-subtle text-info fw-bold"><i class="fa-solid fa-credit-card me-1"></i> Online Banking</span>',
            'momo' => '<span class="badge text-white fw-bold" style="background-color: #d82d8b;"><i class="fa-solid fa-wallet me-1"></i> MoMo</span>',
            'zalopay' => '<span class="badge text-white fw-bold" style="background-color: #008fe5;"><i class="fa-solid fa-wallet me-1"></i> Ví ZaloPay</span>',
            'vietqr', 'vnpay' => '<span class="badge bg-primary-subtle text-primary fw-bold"><i class="fa-solid fa-qrcode me-1"></i> Online</span>',
            'exchange' => '<span class="badge bg-warning text-dark fw-bold"><i class="fa-solid fa-arrow-right-arrow-left me-1"></i> Đổi Hàng RMA</span>',
            default => '<span class="badge bg-secondary-subtle text-secondary fw-bold"><i class="fa-solid fa-hand-holding-dollar me-1"></i> COD</span>',
        };
    }
}