<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول سفارش‌ها
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // شناسه سفارش (کلید اصلی)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // شناسه کاربر
            $table->enum('item_type', ['book', 'article']); // نوع آیتم: کتاب یا مقاله
            $table->bigInteger('item_id'); // شناسه آیتم (کتاب یا مقاله)

            // اطلاعات قیمت‌گذاری
            $table->decimal('original_price', 12, 2); // قیمت اصلی
            $table->decimal('discount_amount', 12, 2)->default(0); // مقدار تخفیف
            $table->decimal('coupon_discount', 12, 2)->default(0); // تخفیف کوپن
            $table->decimal('loyalty_discount', 12, 2)->default(0); // تخفیف وفاداری
            $table->boolean('is_repurchase')->default(false); // آیا خرید مجدد است
            $table->decimal('final_price', 12, 2); // قیمت نهایی

            // اطلاعات پرداخت
            $table->string('payment_status', 50)->default('pending'); // وضعیت پرداخت
            $table->string('payment_method', 50)->nullable(); // روش پرداخت
            $table->string('transaction_id', 100)->nullable(); // شناسه تراکنش

            // اطلاعات امتیازدهی و دانلود
            $table->integer('points_earned')->default(0); // امتیاز کسب شده از این خرید
            $table->integer('download_limit')->default(50); // محدودیت دانلود
            $table->integer('downloads_count')->default(0); // تعداد دانلود
            $table->timestamp('expires_at'); // تاریخ انقضای دسترسی (90 روز)
            $table->boolean('is_expired')->default(false); // آیا منقضی شده است
            $table->boolean('is_download_limit_reached')->default(false); // آیا به محدودیت دانلود رسیده است

            $table->text('order_notes')->nullable(); // یادداشت‌های سفارش
            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('user_id');
            $table->index(['item_type', 'item_id']);
            $table->index('created_at');
            $table->index('expires_at');
            $table->index('payment_status');

            // توضیحات جدول
            $table->comment('جدول سفارش‌های کتاب و مقاله با محدودیت 50 بار دانلود و 90 روز دسترسی');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
