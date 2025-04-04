<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول شرکت‌کنندگان مسابقات
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_participants', function (Blueprint $table) {
            $table->id(); // شناسه شرکت کننده (کلید اصلی)
            $table->foreignId('contest_id')->constrained('contests')->onDelete('cascade'); // شناسه مسابقه
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // شناسه کاربر
            $table->text('entry_data')->nullable(); // اطلاعات ورودی کاربر
            $table->boolean('is_winner')->default(false); // آیا برنده شده است
            $table->boolean('points_awarded')->default(false); // آیا امتیازات پرداخت شده است
            $table->timestamps(); // ستون‌های created_at و updated_at

            // هر کاربر فقط یک بار می‌تواند در یک مسابقه شرکت کند
            $table->unique(['contest_id', 'user_id']);

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('contest_id');
            $table->index('user_id');
            $table->index('is_winner');

            // توضیحات جدول
            $table->comment('جدول شرکت‌کنندگان در مسابقات - برندگان امتیاز دریافت می‌کنند');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contest_participants');
    }
};
