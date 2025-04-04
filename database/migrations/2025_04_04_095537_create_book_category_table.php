<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول رابطه بین کتاب‌ها و دسته‌بندی‌ها
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_category', function (Blueprint $table) {
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade'); // شناسه کتاب
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // شناسه دسته‌بندی

            // تعریف کلید اصلی ترکیبی
            $table->primary(['book_id', 'category_id']);

            // توضیحات جدول
            $table->comment('جدول ارتباطی بین کتاب‌ها و دسته‌بندی‌ها - هر کتاب می‌تواند در چندین دسته‌بندی قرار گیرد');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_category');
    }
};
