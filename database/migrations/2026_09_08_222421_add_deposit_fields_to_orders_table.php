<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'is_deposit_required')) {
                $table->boolean('is_deposit_required')->default(false)->after('total_amount')->comment('Đơn hàng có bắt buộc đặt cọc 50% không');
            }
            if (!Schema::hasColumn('orders', 'deposit_amount')) {
                $table->unsignedBigInteger('deposit_amount')->default(0)->after('is_deposit_required')->comment('Số tiền cọc 50%');
            }
            if (!Schema::hasColumn('orders', 'remaining_amount')) {
                $table->unsignedBigInteger('remaining_amount')->default(0)->after('deposit_amount')->comment('Số tiền còn lại thu COD khi giao hàng');
            }
            if (!Schema::hasColumn('orders', 'deposit_status')) {
                $table->string('deposit_status', 50)->default('unpaid')->after('remaining_amount')->comment('Trạng thái cọc: unpaid, paid, refunded');
            }
            if (!Schema::hasColumn('orders', 'deposit_paid_at')) {
                $table->timestamp('deposit_paid_at')->nullable()->after('deposit_status')->comment('Thời gian thanh toán tiền cọc');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = ['is_deposit_required', 'deposit_amount', 'remaining_amount', 'deposit_status', 'deposit_paid_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
