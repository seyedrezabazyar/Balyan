<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول سطوح دسترسی کاربران و کتاب‌ها
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_levels', function (Blueprint $table) {
            $table->id(); // شناسه سطح دسترسی (کلید اصلی)
            $table->string('name', 50)->unique(); // نام سطح دسترسی - منحصر به فرد
            $table->integer('level')->unique(); // عدد نشان دهنده سطح (1-10)
            $table->integer('points_required')->default(0); // حداقل امتیاز مورد نیاز برای این سطح
            $table->text('description')->nullable(); // توضیحات سطح دسترسی
            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایندکس برای بهبود عملکرد جستجو
            $table->index('level');
            $table->index('points_required');

            // توضیحات جدول
            $table->comment('جدول سطوح دسترسی برای کاربران، کتاب‌ها و تصاویر با شرط امتیازات');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_levels');
    }
};
