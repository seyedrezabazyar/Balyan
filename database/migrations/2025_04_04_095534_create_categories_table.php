<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول دسته‌بندی‌ها
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // شناسه دسته‌بندی (کلید اصلی)
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade'); // شناسه دسته‌بندی والد
            $table->string('name', 100); // نام دسته‌بندی
            $table->string('slug', 100)->unique(); // نام مسیر یکتا
            $table->text('description')->nullable(); // توضیحات دسته‌بندی
            $table->foreignId('access_level_id')->default(1)->constrained('access_levels'); // سطح دسترسی مورد نیاز
            $table->boolean('is_for_books')->default(true); // آیا برای کتاب‌ها استفاده می‌شود
            $table->boolean('is_for_articles')->default(false); // آیا برای مقالات استفاده می‌شود
            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('parent_id');
            $table->index('slug');
            $table->index(['is_for_books', 'is_for_articles']);

            // توضیحات جدول
            $table->comment('جدول دسته‌بندی‌های کتاب‌ها و مقالات - بیش از 300 دسته‌بندی با ساختار درختی');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
