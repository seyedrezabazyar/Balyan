<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول کدهای تأیید
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id(); // شناسه کد تایید (کلید اصلی)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // شناسه کاربر (اختیاری)
            $table->string('email', 255)->nullable(); // ایمیل (اگر کاربر هنوز ثبت نام نکرده باشد)
            $table->string('phone', 20)->nullable(); // شماره تلفن (اگر کاربر هنوز ثبت نام نکرده باشد)
            $table->string('code', 10); // کد تایید
            $table->string('type', 20); // نوع کد: login, registration, password_reset
            $table->timestamp('expires_at'); // تاریخ انقضا
            $table->boolean('is_used')->default(false); // آیا استفاده شده است
            $table->timestamp('created_at')->useCurrent(); // زمان ایجاد

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('user_id');
            $table->index('code');
            $table->index('expires_at');
            $table->index(['email', 'code', 'type']);
            $table->index(['phone', 'code', 'type']);

            // توضیحات جدول
            $table->comment('جدول کدهای تأیید برای ورود، ثبت‌نام و بازیابی رمز عبور');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification_codes');
    }
};
