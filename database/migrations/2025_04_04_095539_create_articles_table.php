<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول مقالات
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id(); // شناسه مقاله (کلید اصلی)
            $table->string('title', 500); // عنوان مقاله
            $table->string('slug', 500)->unique(); // نام مسیر یکتا
            $table->mediumText('content')->nullable(); // محتوای مقاله
            $table->text('summary')->nullable(); // خلاصه مقاله
            $table->string('language_code', 10)->nullable(); // کد زبان
            $table->string('author_text', 1000)->nullable(); // متن نویسندگان (برای جستجو)
            $table->string('year', 14)->nullable(); // سال انتشار (متنی)
            $table->smallInteger('year_numeric')->nullable(); // سال انتشار (عددی برای فیلتر)
            $table->bigInteger('filesize')->nullable(); // حجم فایل به بایت

            // قیمت‌گذاری و دسترسی
            $table->decimal('price', 12, 2)->default(10000); // قیمت ثابت مقاله
            $table->foreignId('access_level_id')->default(1)->constrained('access_levels'); // سطح دسترسی مورد نیاز
            $table->boolean('is_visible')->default(true); // آیا مقاله قابل نمایش است

            // آمار بازدید و خرید
            $table->integer('view_count')->default(0); // تعداد بازدید
            $table->integer('download_count')->default(0); // تعداد دانلود
            $table->integer('purchase_count')->default(0); // تعداد خرید

            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('slug');
            $table->index('language_code');
            $table->index('access_level_id');
            // حذف ایندکس author_text به دلیل طول زیاد
            // $table->index('author_text');
            $table->index('year_numeric');

            // ارتباط با جدول زبان‌ها
            $table->foreign('language_code')->references('code')->on('languages');

            // توضیحات جدول
            $table->comment('جدول مقالات با قیمت ثابت 10000 تومان و جدا از جدول کتاب‌ها');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
