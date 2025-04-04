<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول علاقه‌مندی‌های کاربران
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wishlist', function (Blueprint $table) {
            $table->id(); // شناسه رکورد (کلید اصلی)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // شناسه کاربر
            $table->enum('item_type', ['book', 'article']); // نوع آیتم: کتاب یا مقاله
            $table->bigInteger('item_id'); // شناسه آیتم (کتاب یا مقاله)
            $table->timestamp('created_at')->useCurrent(); // زمان افزودن به علاقه‌مندی‌ها

            // محدودیت یکتا بودن هر آیتم برای هر کاربر
            $table->unique(['user_id', 'item_type', 'item_id']);

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('user_id');
            $table->index(['item_type', 'item_id']);

            // توضیحات جدول
            $table->comment('جدول علاقه‌مندی‌های کاربران برای ذخیره کتاب‌ها و مقالات مورد علاقه');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wishlist');
    }
};
