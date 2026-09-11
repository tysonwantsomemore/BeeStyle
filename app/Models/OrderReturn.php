<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_code',
        'order_id',
        'user_id',
        'order_item_id',
        'type',
        'reason',
        'customer_notes',
        'image_proofs',
        'exchange_size',
        'exchange_color',
        'refund_amount',
        'refund_method',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'bank_branch',
        'status',
        'admin_notes',
        'rejected_reason',
        'approved_at',
        'received_at',
        'completed_at',
        'rejected_at',
    ];

    protected $casts = [
        'image_proofs' => 'array',
        'refund_amount' => 'integer',
        'approved_at' => 'datetime',
        'received_at' => 'datetime',
        'completed_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Danh sách ma trận các trạng thái hợp lệ tiếp theo được phép chuyển từ trạng thái hiện tại (State Machine)
     */
    public function getAllowedNextStatuses(): array
    {
        $transitions = [
            'pending'   => ['approved', 'rejected'],
            'approved'  => ['received', 'rejected'],
            'received'  => ['completed', 'rejected'],
            'completed' => [], // Trạng thái đóng cuối cùng - không được chuyển trạng thái
            'rejected'  => [], // Trạng thái đóng cuối cùng - không được chuyển trạng thái
        ];

        return $transitions[$this->status] ?? [];
    }

    /**
     * Kiểm tra xem phiếu đổi trả có được phép chuyển sang trạng thái mới hay không
     */
    public function canTransitionTo(string $newStatus): bool
    {
        if ($this->status === $newStatus) {
            return true; // Giữ nguyên trạng thái hiện tại (cho phép update ghi chú)
        }

        return in_array($newStatus, $this->getAllowedNextStatuses(), true);
    }

    /**
     * Kiểm tra xem phiếu đổi trả đã ở trạng thái kết thúc (hoàn tất hoặc từ chối) hay chưa
     */
    public function isFinalStatus(): bool
    {
        return in_array($this->status, ['completed', 'rejected'], true);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt (Chờ gửi hàng)',
            'received' => 'Kho đã nhận hàng',
            'completed' => 'Hoàn tất',
            'rejected' => 'Đã từ chối',
            default => 'Đang xử lý',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning text-dark fw-bold"><i class="fa-solid fa-hourglass-half me-1"></i> Chờ duyệt</span>',
            'approved' => '<span class="badge bg-info text-white fw-bold"><i class="fa-solid fa-box me-1"></i> Đã duyệt / Gửi hàng</span>',
            'received' => '<span class="badge bg-primary text-white fw-bold"><i class="fa-solid fa-boxes-packing me-1"></i> Kho đã nhận</span>',
            'completed' => '<span class="badge bg-success text-white fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Hoàn tất</span>',
            'rejected' => '<span class="badge bg-danger text-white fw-bold"><i class="fa-solid fa-ban me-1"></i> Bị từ chối</span>',
            default => '<span class="badge bg-secondary text-white fw-bold">Đang xử lý</span>',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'return_refund' => 'Trả hàng & Hoàn tiền',
            'exchange' => 'Đổi kích cỡ (Size/Màu)',
            'refund_only' => 'Chỉ hoàn tiền (Không trả hàng)',
            default => 'Yêu cầu đổi trả',
        };
    }
}
