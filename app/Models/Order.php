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
            'vietqr' => 'Chuyển khoản VietQR',
            'momo' => 'Ví điện tử MoMo',
            'vnpay' => 'Cổng VNPAY',
            default => 'Thanh toán khi nhận hàng (COD)',
        };
    }
}
