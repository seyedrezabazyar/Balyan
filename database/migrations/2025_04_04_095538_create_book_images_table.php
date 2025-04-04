<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول تصاویر کتاب
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_images', function (Blueprint $table) {
            $table->id(); // شناسه تصویر (کلید اصلی)
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade'); // شناسه کتاب
            $table->string('image_path', 500); // مسیر فایل تصویر
            $table->enum('image_type', ['cover', 'sample', 'other'])->default('sample'); // نوع تصویر: جلد، نمونه، سایر
            $table->integer('display_order')->default(0); // ترتیب نمایش
            $table->foreignId('access_level_id')->default(1)->constrained('access_levels'); // سطح دسترسی مورد نیاز
            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('book_id');
            $table->index('access_level_id');
            $table->index(['book_id', 'image_type']);

            // توضیحات جدول
            $table->comment('جدول تصاویر کتاب با سطوح دسترسی مستقل - کاربر ممکن است برای دیدن تصاویر نیاز به ثبت نام داشته باشد');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_images');
    }
};
