<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول نشست‌های کاربران
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // شناسه نشست (کلید اصلی)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // شناسه کاربر (می‌تواند خالی باشد برای کاربران مهمان)
            $table->string('ip_address', 45)->nullable(); // آدرس IP
            $table->text('user_agent')->nullable(); // اطلاعات مرورگر و سیستم عامل
            $table->longText('payload'); // داده‌های نشست
            $table->integer('last_activity'); // آخرین فعالیت (timestamp)

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('user_id');
            $table->index('last_activity');

            // توضیحات جدول
            $table->comment('جدول نشست‌های کاربران در لاراول - استفاده برای ذخیره‌سازی وضعیت کاربر');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
};
