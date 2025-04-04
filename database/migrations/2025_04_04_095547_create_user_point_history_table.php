<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول تاریخچه امتیازات کاربران
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_point_history', function (Blueprint $table) {
            $table->id(); // شناسه رکورد (کلید اصلی)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // شناسه کاربر
            $table->string('action_type', 50); // نوع عملیات: خرید، نظر، لاگین متوالی و...
            $table->integer('points'); // تعداد امتیاز (می‌تواند مثبت یا منفی باشد)
            $table->bigInteger('reference_id')->nullable(); // شناسه موجودیت مرتبط
            $table->string('reference_type', 50)->nullable(); // نوع موجودیت مرتبط
            $table->text('description')->nullable(); // توضیحات
            $table->timestamp('created_at')->useCurrent(); // زمان ثبت امتیاز

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('user_id');
            $table->index('action_type');
            $table->index('created_at');
            $table->index(['reference_type', 'reference_id']);

            // توضیحات جدول
            $table->comment('جدول تاریخچه تغییرات امتیازات کاربران برای گیمیفیکیشن - شامل امتیازات خرید، نظر، لاگین متوالی و...');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_point_history');
    }
};
