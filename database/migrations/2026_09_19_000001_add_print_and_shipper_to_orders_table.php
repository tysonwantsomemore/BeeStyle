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
            if (!Schema::hasColumn('orders', 'printed_at')) {
                $table->timestamp('printed_at')->nullable()->after('status_step');
            }
            if (!Schema::hasColumn('orders', 'print_count')) {
                $table->unsignedInteger('print_count')->default(0)->after('printed_at');
            }
            if (!Schema::hasColumn('orders', 'shipper_id')) {
                $table->foreignId('shipper_id')->nullable()->after('tracking_code')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'handover_image')) {
                $table->string('handover_image')->nullable()->after('delivery_proof_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipper_id')) {
                $table->dropForeign(['shipper_id']);
                $table->dropColumn('shipper_id');
            }
            $columns = ['printed_at', 'print_count', 'handover_image'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
