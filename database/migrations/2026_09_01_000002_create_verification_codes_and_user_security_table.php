<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Khởi tạo bảng lưu mã xác thực OTP, bảo mật tài khoản và thay đổi liên hệ 2 bước
     */
    public function up(): void
    {
        // Cập nhật các cột bảo mật cho bảng users nếu chưa có
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'locked_until')) {
                $table->timestamp('locked_until')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'failed_login_attempts')) {
                $table->unsignedTinyInteger('failed_login_attempts')->default(0)->after('locked_until');
            }
        });

        // Bảng lưu mã xác thực OTP đa mục đích (Kích hoạt tài khoản, Đổi mật khẩu, Xác nhận 2 bước...)
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('contact');            // Email hoặc SĐT nhận OTP
            $table->string('type');               // 'register_activation', 'change_email', 'change_phone', 'step_up_auth', 'password_reset'
            $table->string('code');               // Mã OTP 6 chữ số
            $table->string('token', 64)->nullable()->index(); // Token bảo mật phiên xác thực
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->unsignedTinyInteger('max_attempts')->default(5);
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->json('payload')->nullable();  // Dữ liệu tạm thời (ví dụ: email mới, sđt mới)
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['contact', 'type', 'is_used']);
        });

        // Bảng lưu trạng thái tạm thời đổi SĐT/Email (Quy trình 2 bước)
        Schema::create('user_pending_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type');               // 'email' hoặc 'phone'
            $table->string('new_value');          // Giá trị email mới hoặc số điện thoại mới
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Hoàn tác migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_pending_contacts');
        Schema::dropIfExists('verification_codes');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_verified_at', 'locked_until', 'failed_login_attempts']);
        });
    }
};
