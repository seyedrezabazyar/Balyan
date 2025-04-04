<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول نویسندگان
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id(); // شناسه نویسنده (کلید اصلی)
            $table->string('name', 255); // نام نویسنده
            $table->string('normalized_name', 255); // نام نرمال‌سازی شده برای جستجوی بهتر
            $table->mediumText('bio')->nullable(); // زندگی‌نامه نویسنده
            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایندکس‌ها برای بهبود عملکرد
            $table->unique('normalized_name');
            $table->index('name');

            // توضیحات جدول
            $table->comment('جدول نویسندگان کتاب‌ها و مقالات');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authors');
    }
};
