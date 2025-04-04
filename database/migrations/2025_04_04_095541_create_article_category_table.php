<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول رابطه بین مقالات و دسته‌بندی‌ها
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_category', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade'); // شناسه مقاله
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // شناسه دسته‌بندی

            // تعریف کلید اصلی ترکیبی
            $table->primary(['article_id', 'category_id']);

            // توضیحات جدول
            $table->comment('جدول ارتباطی بین مقالات و دسته‌بندی‌ها - هر مقاله می‌تواند در چندین دسته‌بندی قرار گیرد');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_category');
    }
};
