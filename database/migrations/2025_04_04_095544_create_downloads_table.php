<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول دانلودها
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id(); // شناسه دانلود (کلید اصلی)
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // شناسه سفارش
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // شناسه کاربر
            $table->enum('item_type', ['book', 'article']); // نوع آیتم: کتاب یا مقاله
            $table->bigInteger('item_id'); // شناسه آیتم (کتاب یا مقاله)
            $table->string('ip_address', 45); // آدرس IP دانلود کننده
            $table->text('user_agent')->nullable(); // اطلاعات مرورگر و سیستم عامل
            $table->timestamp('downloaded_at')->useCurrent(); // زمان دانلود

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('order_id');
            $table->index('user_id');
            $table->index(['item_type', 'item_id']);
            $table->index('downloaded_at');

            // توضیحات جدول
            $table->comment('جدول سوابق دانلودهای کاربران - پارتیشن‌بندی بر اساس تاریخ دانلود');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('downloads');
    }
};
