<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Cập nhật bảng orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'cancel_reason')) {
                $table->string('cancel_reason')->nullable()->after('admin_notes');
            }
            if (!Schema::hasColumn('orders', 'cancelled_by')) {
                $table->string('cancelled_by')->nullable()->after('cancel_reason'); // customer, admin, system
            }
            if (!Schema::hasColumn('orders', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('cancelled_by');
            }
        });

        // 2. Tạo bảng order_returns
        if (!Schema::hasTable('order_returns')) {
            Schema::create('order_returns', function (Blueprint $table) {
                $table->id();
                $table->string('return_code')->unique(); // RET-20260825-XXXX
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('order_item_id')->nullable()->constrained('order_items')->nullOnDelete();
                
                $table->string('type')->default('return_refund'); // return_refund (Trả hàng & Hoàn tiền), exchange (Đổi size/màu), refund_only (Chỉ hoàn tiền)
                $table->string('reason'); // Lý do: Lỗi vải, Sai mẫu, Không vừa size, Khác...
                $table->text('customer_notes')->nullable();
                $table->json('image_proofs')->nullable(); // Mảng ảnh minh chứng
                
                // Trường hợp đổi size/màu
                $table->string('exchange_size')->nullable();
                $table->string('exchange_color')->nullable();
                
                // Số tiền hoàn & Thông tin nhận tiền
                $table->unsignedBigInteger('refund_amount')->default(0);
                $table->string('refund_method')->default('bank'); // bank (STK Ngân hàng), voucher (Voucher mua sắm)
                $table->string('bank_name')->nullable();
                $table->string('bank_account_number')->nullable();
                $table->string('bank_account_name')->nullable();
                $table->string('bank_branch')->nullable();
                
                // Trạng thái xử lý RMA
                $table->string('status')->default('pending'); // pending (Chờ duyệt), approved (Đã chấp thuận - Chờ gửi hàng), received (Kho đã nhận hàng), completed (Hoàn tất & Hoàn tiền/Đổi size), rejected (Bị từ chối)
                $table->text('admin_notes')->nullable();
                $table->text('rejected_reason')->nullable();
                
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('received_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_returns');

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'cancel_reason')) {
                $table->dropColumn('cancel_reason');
            }
            if (Schema::hasColumn('orders', 'cancelled_by')) {
                $table->dropColumn('cancelled_by');
            }
            if (Schema::hasColumn('orders', 'cancelled_at')) {
                $table->dropColumn('cancelled_at');
            }
        });
    }
};
