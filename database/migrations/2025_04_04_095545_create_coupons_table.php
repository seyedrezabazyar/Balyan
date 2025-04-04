<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول کوپن‌های تخفیف
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id(); // شناسه کوپن (کلید اصلی)
            $table->string('code', 50)->unique(); // کد کوپن - منحصر به فرد
            $table->string('type', 20); // نوع کوپن: درصدی یا مبلغ ثابت
            $table->decimal('value', 10, 2); // مقدار تخفیف (درصد یا مبلغ)
            $table->decimal('min_order_amount', 12, 2)->default(0); // حداقل مبلغ سفارش برای استفاده
            $table->decimal('max_discount_amount', 12, 2)->nullable(); // حداکثر مبلغ تخفیف (برای تخفیف‌های درصدی)
            $table->boolean('applies_to_books')->default(true); // آیا برای کتاب‌ها قابل استفاده است
            $table->boolean('applies_to_articles')->default(true); // آیا برای مقالات قابل استفاده است
            $table->timestamp('starts_at')->nullable(); // تاریخ شروع اعتبار
            $table->timestamp('expires_at')->nullable(); // تاریخ پایان اعتبار
            $table->integer('usage_limit')->nullable(); // محدودیت استفاده
            $table->integer('usage_count')->default(0); // تعداد دفعات استفاده
            $table->boolean('is_active')->default(true); // آیا کوپن فعال است
            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('code');
            $table->index('is_active');
            $table->index('expires_at');

            // توضیحات جدول
            $table->comment('جدول کوپن‌های تخفیف با امکان تعیین نوع تخفیف و محدودیت استفاده');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
