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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique()->comment('شناسه منحصر به فرد برای ورود کاربر');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('display_name', 200)->nullable()->comment('نام نمایشی سفارشی (اختیاری)');
            $table->string('email')->nullable()->unique();
            $table->string('phone', 20)->nullable()->unique();
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->rememberToken();
            $table->string('access_token_hash', 255)->nullable()->comment('برای دسترسی امن به صفحه سفارش');
            $table->integer('access_level_id')->default(1);
            $table->string('profile_image', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->timestamp('last_login_at')->nullable();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
}
