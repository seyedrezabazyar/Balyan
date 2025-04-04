<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول مسابقات
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contests', function (Blueprint $table) {
            $table->id(); // شناسه مسابقه (کلید اصلی)
            $table->string('title', 255); // عنوان مسابقه
            $table->text('description')->nullable(); // توضیحات مسابقه
            $table->timestamp('starts_at'); // تاریخ شروع مسابقه
            $table->timestamp('ends_at'); // تاریخ پایان مسابقه
            $table->integer('points_reward')->default(0); // امتیاز جایزه برنده
            $table->boolean('is_active')->default(true); // آیا مسابقه فعال است
            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('starts_at');
            $table->index('ends_at');
            $table->index('is_active');

            // توضیحات جدول
            $table->comment('جدول مسابقات سایت برای گیمیفیکیشن - برندگان امتیاز دریافت می‌کنند');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contests');
    }
};
