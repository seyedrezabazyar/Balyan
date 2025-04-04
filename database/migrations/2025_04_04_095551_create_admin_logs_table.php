<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول لاگ‌های مدیریتی
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id(); // شناسه لاگ (کلید اصلی)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // شناسه کاربر مدیر یا ویرایشگر
            $table->string('action', 100); // نوع عملیات انجام شده
            $table->string('entity_type', 50); // نوع موجودیت (کتاب، مقاله، کاربر و...)
            $table->bigInteger('entity_id'); // شناسه موجودیت
            $table->json('old_values')->nullable(); // مقادیر قبلی
            $table->json('new_values')->nullable(); // مقادیر جدید
            $table->string('ip_address', 45); // آدرس IP
            $table->timestamp('created_at')->useCurrent(); // زمان ثبت لاگ

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('user_id');
            $table->index(['entity_type', 'entity_id']);
            $table->index('created_at');
            $table->index('action');

            // توضیحات جدول
            $table->comment('جدول لاگ‌های اقدامات مدیریتی برای ردیابی تغییرات توسط مدیران و ویرایشگران');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_logs');
    }
};
