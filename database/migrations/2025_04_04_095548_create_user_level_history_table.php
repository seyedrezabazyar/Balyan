<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول تاریخچه سطوح دسترسی کاربران
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_level_history', function (Blueprint $table) {
            $table->id(); // شناسه رکورد (کلید اصلی)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // شناسه کاربر
            $table->foreignId('old_level_id')->nullable()->constrained('access_levels'); // سطح دسترسی قبلی
            $table->foreignId('new_level_id')->constrained('access_levels'); // سطح دسترسی جدید
            $table->text('reason')->nullable(); // دلیل تغییر سطح
            $table->timestamp('created_at')->useCurrent(); // زمان تغییر سطح

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('user_id');
            $table->index('created_at');

            // توضیحات جدول
            $table->comment('جدول تاریخچه تغییرات سطح دسترسی کاربران بر اساس امتیازات کسب شده');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_level_history');
    }
};
