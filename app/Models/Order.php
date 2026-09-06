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
        'shipping_carrier',
        'tracking_code',
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
        'confirmed_at',
        'processing_at',
        'shipping_at',
        'delivered_at',
        'completed_at',
        'paid_at',
    ];

    protected $casts = [
        'status_step' => 'integer',
        'subtotal' => 'integer',
        'discount_amount' => 'integer',
        'shipping_fee' => 'integer',
        'total_amount' => 'integer',
        'review_notified' => 'boolean',
        'cancelled_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'processing_at' => 'datetime',
        'shipping_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function ($order) {
            $stepMap = [
                'pending' => 1,
                'confirmed' => 2,
                'processing' => 3,
                'shipping' => 4,
                'delivered' => 5,
                'completed' => 6,
                'cancelled' => 0,
            ];
            if (isset($stepMap[$order->shipping_status])) {
                $order->status_step = $stepMap[$order->shipping_status];
            }

            // Tự động chuyển payment_status = paid cho đơn COD khi giao hàng thành công hoặc hoàn tất
            if (in_array($order->shipping_status, ['delivered', 'completed']) && $order->payment_method === 'cod') {
                $order->payment_status = 'paid';
                if (!$order->paid_at) {
                    $order->paid_at = now();
                }
            }
        });
    }
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
            'momo' => 'Thanh toán trực tuyến qua ví MoMo (Redirect/Deep Link)',
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
            'momo' => '<span class="badge text-white fw-bold" style="background-color: #d82d8b;"><i class="fa-solid fa-wallet me-1"></i> MoMo (Deep Link)</span>',
            'zalopay' => '<span class="badge text-white fw-bold" style="background-color: #008fe5;"><i class="fa-solid fa-wallet me-1"></i> Ví ZaloPay</span>',
            'vietqr', 'vnpay' => '<span class="badge bg-primary-subtle text-primary fw-bold"><i class="fa-solid fa-qrcode me-1"></i> Online</span>',
            'exchange' => '<span class="badge bg-warning text-dark fw-bold"><i class="fa-solid fa-arrow-right-arrow-left me-1"></i> Đổi Hàng RMA</span>',
            default => '<span class="badge bg-secondary-subtle text-secondary fw-bold"><i class="fa-solid fa-hand-holding-dollar me-1"></i> COD</span>',
        };
    }

    /**
     * Đường dẫn tra cứu hành trình bưu kiện trực tuyến (Hiển thị 100% dữ liệu thật của đơn hàng)
     */
    public function getTrackingUrlAttribute(): ?string
    {
        if (empty($this->tracking_code)) {
            return null;
        }

        return url('/tra-cuu-van-don/' . urlencode(trim($this->tracking_code)));
    }

    /**
     * Đường dẫn liên kết trực tiếp sang website ngoài đời thực của hãng vận chuyển
     */
    public function getExternalTrackingUrlAttribute(): ?string
    {
        if (empty($this->tracking_code)) {
            return null;
        }

        $carrier = mb_strtolower((string)$this->shipping_carrier, 'UTF-8');
        $code = urlencode(trim($this->tracking_code));

        if (str_contains($carrier, 'ghtk') || str_contains($carrier, 'tiết kiệm')) {
            return "https://i.ghtk.vn/{$code}";
        }
        if (str_contains($carrier, 'ghn') || str_contains($carrier, 'nhanh')) {
            return "https://donhang.ghn.vn/?order_code={$code}";
        }
        if (str_contains($carrier, 'viettel')) {
            return "https://viettelpost.com.vn/tra-cuu-hanh-trinh-don/?order_number={$code}";
        }
        if (str_contains($carrier, 'j&t') || str_contains($carrier, 'jt')) {
            return "https://jtexpress.vn/vi/tracking?billcode={$code}";
        }
        if (str_contains($carrier, 'ninja')) {
            return "https://www.ninjavan.co/vi-vn/tracking?id={$code}";
        }
        if (str_contains($carrier, 'vnpost') || str_contains($carrier, 'bưu điện')) {
            return "http://www.vnpost.vn/vi-vn/dinh-vi/buu-pham?key={$code}";
        }

        return null;
    }
}
