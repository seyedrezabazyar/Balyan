<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول زبان‌های سایت
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->string('code', 10)->primary(); // کد زبان به عنوان کلید اصلی (مانند 'fa', 'en')
            $table->string('name', 100); // نام زبان به انگلیسی (Persian, English)
            $table->string('native_name', 100); // نام زبان به زبان بومی (فارسی، English)
            $table->boolean('is_active')->default(true); // آیا زبان فعال است
            $table->timestamps(); // ستون‌های created_at و updated_at

            // توضیحات جدول
            $table->comment('جدول زبان‌های قابل پشتیبانی در سایت');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
};
