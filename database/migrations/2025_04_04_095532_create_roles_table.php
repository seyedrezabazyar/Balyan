<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول نقش‌های کاربران
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // شناسه نقش (کلید اصلی)
            $table->string('name', 50)->unique(); // نام نقش - منحصر به فرد
            $table->text('description')->nullable(); // توضیحات نقش
            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایجاد ایندکس برای بهبود عملکرد جستجو
            $table->index('name');

            // توضیحات جدول
            $table->comment('جدول نقش‌های کاربران: مدیر، ویرایشگر، کاربر عادی و غیره');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
