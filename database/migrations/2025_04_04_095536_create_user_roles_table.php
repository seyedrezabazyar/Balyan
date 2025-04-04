<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول رابطه بین کاربران و نقش‌ها
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // شناسه کاربر
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); // شناسه نقش
            $table->timestamp('created_at')->useCurrent(); // زمان ایجاد

            // تعریف کلید اصلی ترکیبی
            $table->primary(['user_id', 'role_id']);

            // توضیحات جدول
            $table->comment('جدول ارتباط بین کاربران و نقش‌ها - هر کاربر می‌تواند چندین نقش داشته باشد');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
};
