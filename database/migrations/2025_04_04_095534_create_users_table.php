<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول کاربران
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // شناسه کاربر (کلید اصلی)
            $table->string('username', 50)->unique(); // نام کاربری منحصر به فرد
            $table->timestamp('username_changed_at')->nullable(); // تاریخ آخرین تغییر نام کاربری
            $table->string('first_name', 100); // نام
            $table->string('last_name', 100); // نام خانوادگی
            $table->string('display_name', 200)->nullable(); // نام نمایشی
            $table->string('email', 255)->nullable()->unique(); // ایمیل - منحصر به فرد
            $table->string('phone', 20)->nullable()->unique(); // شماره تلفن - منحصر به فرد
            $table->string('password', 255)->nullable(); // رمز عبور (هش شده)
            $table->timestamp('email_verified_at')->nullable(); // تاریخ تایید ایمیل
            $table->timestamp('phone_verified_at')->nullable(); // تاریخ تایید شماره تلفن
            $table->string('remember_token', 100)->nullable(); // توکن remember me
            $table->string('access_token_hash', 255)->nullable(); // هش توکن دسترسی
            $table->string('profile_image', 255)->nullable(); // مسیر تصویر پروفایل
            $table->boolean('is_active')->default(true); // آیا کاربر فعال است

            // امتیازات و سطح کاربر
            $table->integer('points')->default(0); // امتیازات کسب شده
            $table->foreignId('access_level_id')->default(1)->constrained('access_levels'); // سطح دسترسی کاربر
            $table->integer('profile_completion_percentage')->default(0); // درصد تکمیل پروفایل

            // اطلاعات ورود
            $table->string('last_login_ip', 45)->nullable(); // آخرین IP ورود
            $table->integer('consecutive_login_days')->default(0); // تعداد روزهای متوالی ورود
            $table->timestamp('last_login_at')->nullable(); // زمان آخرین ورود

            // اطلاعات مالی
            $table->decimal('wallet_balance', 10, 2)->default(0); // موجودی کیف پول

            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('username');
            $table->index('email');
            $table->index('phone');
            $table->index('points');
            $table->index('access_level_id');

            // توضیحات جدول
            $table->comment('جدول کاربران با امکان ثبت نام از طریق ایمیل یا شماره تلفن و با سیستم امتیازدهی');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
