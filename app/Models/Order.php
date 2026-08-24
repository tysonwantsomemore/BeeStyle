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
        'shipping_status',
        'status_step',
        'subtotal',
        'discount_amount',
        'shipping_fee',
        'total_amount',
        'coupon_code',
        'admin_notes',
        'review_notified',
    ];

    protected $casts = [
        'status_step' => 'integer',
        'subtotal' => 'integer',
        'discount_amount' => 'integer',
        'shipping_fee' => 'integer',
        'total_amount' => 'integer',
        'review_notified' => 'boolean',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
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
        return match ($this->payment_status) {
            'paid' => 'Đã thanh toán',
            'refunded' => 'Đã hoàn tiền',
            default => 'Chưa thanh toán',
        };
    }

    public function getPaymentMethodNameAttribute(): string
    {
        return match ($this->payment_method) {
            'online' => 'Thanh toán Online (ATM/Banking/Visa)',
            'momo' => 'Ví Điện Tử MoMo',
            'zalopay' => 'Ví Điện Tử ZaloPay',
            'vietqr' => 'Chuyển khoản VietQR',
            'vnpay' => 'Cổng VNPAY',
            default => 'Thanh toán khi nhận hàng (COD)',
        };
    }

    public function getPaymentMethodBadgeAttribute(): string
    {
        return match ($this->payment_method) {
            'online' => '<span class="badge bg-info-subtle text-info fw-bold"><i class="fa-solid fa-credit-card me-1"></i> Online Banking</span>',
            'momo' => '<span class="badge text-white fw-bold" style="background-color: #d82d8b;"><i class="fa-solid fa-wallet me-1"></i> Ví MoMo</span>',
            'zalopay' => '<span class="badge text-white fw-bold" style="background-color: #008fe5;"><i class="fa-solid fa-wallet me-1"></i> Ví ZaloPay</span>',
            'vietqr', 'vnpay' => '<span class="badge bg-primary-subtle text-primary fw-bold"><i class="fa-solid fa-qrcode me-1"></i> Online</span>',
            default => '<span class="badge bg-secondary-subtle text-secondary fw-bold"><i class="fa-solid fa-hand-holding-dollar me-1"></i> COD</span>',
        };
    }
}
