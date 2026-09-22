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
        'ward',
        'shipping_address_snapshot',
        'notes',
        'payment_method',
        'payment_status',
        'momo_trans_id',
        'vnpay_trans_id',
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
        'delivery_proof_image',
        'delivery_proof_note',
        'delivery_proof_at',
        'is_deposit_required',
        'deposit_amount',
        'remaining_amount',
        'deposit_status',
        'printed_at',
        'print_count',
        'shipper_id',
        'handover_image',
        'deposit_paid_at',
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
        'status_step'               => 'integer',
        'subtotal'                  => 'integer',
        'discount_amount'           => 'integer',
        'shipping_fee'              => 'integer',
        'total_amount'              => 'integer',
        'shipping_address_snapshot' => 'array',
        'is_deposit_required'       => 'boolean',
        'deposit_amount'            => 'integer',
        'remaining_amount'          => 'integer',
        'deposit_paid_at'           => 'datetime',
        'review_notified'           => 'boolean',
        'cancelled_at'              => 'datetime',
        'confirmed_at'              => 'datetime',
        'processing_at'             => 'datetime',
        'shipping_at'               => 'datetime',
        'delivered_at'              => 'datetime',
        'completed_at'              => 'datetime',
        'paid_at'                   => 'datetime',
        'delivery_proof_at'         => 'datetime',
        'printed_at'                => 'datetime',
        'print_count'               => 'integer',
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

            // Tự động chuyển payment_status = paid cho đơn COD khi giao hàng thành công hoặc hoàn tất (trừ khi đã hoàn tiền - refunded)
            if (in_array($order->shipping_status, ['delivered', 'completed']) && $order->payment_method === 'cod') {
                if ($order->payment_status !== 'refunded') {
                    $order->payment_status = 'paid';
                    if (!$order->paid_at) {
                        $order->paid_at = now();
                    }
                }
            }
        });
    }

    public function getFullShippingAddressAttribute(): string
    {
        if (!empty($this->shipping_address_snapshot['full_address'])) {
            return $this->shipping_address_snapshot['full_address'];
        }
        $parts = array_filter([$this->shipping_address, $this->ward, $this->district, $this->city]);
        return implode(', ', $parts);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shipper()
    {
        return $this->belongsTo(User::class, 'shipper_id');
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

    public function isCustomerRejected(): bool
    {
        return $this->shipping_status === 'cancelled' && $this->cancelled_by === 'customer_rejected';
    }

    /**
     * Danh sách ma trận các trạng thái hợp lệ tiếp theo (chuyển trạng thái nhảy tuần tự từng bước 1)
     */
    public function getAllowedNextStatuses(): array
    {
        $transitions = [
            'pending'    => ['confirmed', 'cancelled'],
            'confirmed'  => ['processing', 'cancelled'],
            'processing' => ['shipping', 'cancelled'],
            'shipping'   => ['delivered', 'cancelled'],
            'delivered'  => ['completed'],
            'completed'  => [], // Trạng thái đóng cuối cùng - không được chuyển trạng thái
            'cancelled'  => [], // Trạng thái đóng cuối cùng - không được chuyển trạng thái
        ];

        return $transitions[$this->shipping_status] ?? [];
    }

    /**
     * Kiểm tra xem đơn hàng có được phép chuyển sang trạng thái mới hay không
     */
    public function canTransitionTo(string $newStatus): bool
    {
        if ($this->shipping_status === $newStatus) {
            return true; // Giữ nguyên trạng thái hiện tại (cho phép update ghi chú hoặc payment_status)
        }

        return in_array($newStatus, $this->getAllowedNextStatuses(), true);
    }

    /**
     * Kiểm tra xem đơn hàng có đang ở trạng thái đóng (hoàn tất hoặc đã hủy) hay không
     */
    public function isFinalStatus(): bool
    {
        return in_array($this->shipping_status, ['completed', 'cancelled'], true);
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->isCustomerRejected()) {
            return 'Khách không nhận (Chuyển hoàn)';
        }

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
            'DEPOSIT_PAID' => 'Đã đặt cọc 50%',
            'REFUNDED' => 'Đã hoàn tiền',
            'PENDING_PAYMENT', 'UNPAID' => ($this->is_deposit_required ? 'Chờ cọc 50%' : 'Chờ thanh toán'),
            'PAYMENT_FAILED' => 'Thanh toán thất bại',
            'CANCELLED' => 'Đã hủy',
            'EXPIRED' => 'Hết hạn',
            default => 'Chưa thanh toán',
        };
    }

    public function getPaymentMethodNameAttribute(): string
    {
        return match ($this->payment_method) {
            'online' => 'Chuyển khoản VietQR 24/7 (Techcombank)',
            'momo' => 'Thanh toán trực tuyến MoMo Payment (ATM)',
            'zalopay' => 'Ví Điện Tử ZaloPay',
            'vietqr' => 'Chuyển khoản VietQR 24/7 (Techcombank)',
            'vnpay' => 'Cổng Thanh Toán VNPAY (ATM / QR / Visa)',
            'exchange' => 'Đơn Đổi Hàng (0₫ - Bảo hành RMA)',
            default => 'Thanh toán khi nhận hàng (COD)',
        };
    }

    public function getPaymentMethodBadgeAttribute(): string
    {
        return match ($this->payment_method) {
            'online' => '<span class="badge bg-info-subtle text-info fw-bold"><i class="fa-solid fa-credit-card me-1"></i> Online Banking</span>',
            'momo' => '<span class="badge text-white fw-bold" style="background-color: #a50064;"><i class="fa-solid fa-credit-card me-1"></i> MoMo Payment</span>',
            'zalopay' => '<span class="badge text-white fw-bold" style="background-color: #008fe5;"><i class="fa-solid fa-wallet me-1"></i> Ví ZaloPay</span>',
            'vnpay' => '<span class="badge text-white fw-bold" style="background-color: #005baa;"><i class="fa-solid fa-credit-card me-1"></i> VNPAY</span>',
            'vietqr' => '<span class="badge bg-primary-subtle text-primary fw-bold"><i class="fa-solid fa-qrcode me-1"></i> Online</span>',
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

    /**
     * Ảnh bằng chứng giao hàng bưu tá gửi về kho (Proof of Delivery - POD)
     */
    public function getDeliveryProofUrlAttribute(): ?string
    {
        if (empty($this->delivery_proof_image)) {
            if (in_array($this->shipping_status, ['delivered', 'completed']) || ($this->status_step ?? 0) >= 5) {
                return asset('assets/img/delivery-proofs/sample_pod_1.jpg');
            }
            return null;
        }

        if (str_starts_with($this->delivery_proof_image, 'http') 
            || str_starts_with($this->delivery_proof_image, '/') 
            || str_starts_with($this->delivery_proof_image, 'assets/')) {
            return asset($this->delivery_proof_image);
        }

        return asset('storage/' . $this->delivery_proof_image);
    }

    /**
     * Ảnh kiện hàng xuất kho bàn giao bưu tá
     */
    public function getHandoverImageUrlAttribute(): ?string
    {
        if (empty($this->handover_image)) {
            return null;
        }

        if (str_starts_with($this->handover_image, 'http') 
            || str_starts_with($this->handover_image, '/') 
            || str_starts_with($this->handover_image, 'assets/')) {
            return asset($this->handover_image);
        }

        return asset('storage/' . $this->handover_image);
    }

    /**
     * Đơn hàng đã được in phiếu đóng gói chưa
     */
    public function getIsPrintedAttribute(): bool
    {
        return !is_null($this->printed_at);
    }

    /**
     * Tự động quét và hoàn tất các đơn hàng đã giao quá 7 ngày mà khách hàng không có khiếu nại/đổi trả
     */
    public static function autoCompleteEligibleDeliveredOrders(): int
    {
        $sevenDaysAgo = now()->subDays(7);

        $orders = static::where('shipping_status', 'delivered')
            ->whereNotNull('delivered_at')
            ->where('delivered_at', '<=', $sevenDaysAgo)
            ->whereDoesntHave('returns', function ($q) {
                $q->whereIn('status', ['pending', 'approved', 'received']);
            })
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            $order->update([
                'shipping_status' => 'completed',
                'status_step'     => 6,
                'completed_at'    => now(),
                'payment_status'  => 'paid',
                'paid_at'         => $order->paid_at ?: now(),
                'review_notified' => false,
            ]);

            // Tích điểm thưởng & doanh số cho khách hàng thành viên
            if ($order->user_id) {
                $user = User::find($order->user_id);
                if ($user) {
                    $earnedPoints = (int)floor($order->total_amount / 10000);
                    $user->increment('points', $earnedPoints);
                    $user->increment('total_spent', $order->total_amount);
                }
            }
            $count++;
        }

        return $count;
    }
}