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
            $table->string('delivery_proof_image')->nullable()->after('admin_notes');
            $table->text('delivery_proof_note')->nullable()->after('delivery_proof_image');
            $table->timestamp('delivery_proof_at')->nullable()->after('delivery_proof_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_proof_image', 'delivery_proof_note', 'delivery_proof_at']);
        });
    }
};
