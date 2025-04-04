<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول کتاب‌ها
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id(); // شناسه کتاب (کلید اصلی)
            $table->char('mongo_id', 24); // شناسه مونگو دی‌بی
            $table->char('md5', 32)->unique(); // هش MD5 کتاب - منحصر به فرد
            $table->string('identifier', 300)->nullable(); // شناسه (ISBN و غیره)
            $table->char('extension', 7); // پسوند فایل کتاب
            $table->string('language_code', 10)->nullable(); // کد زبان
            $table->string('topic', 500)->nullable(); // موضوع کتاب
            $table->string('publisher', 400)->nullable(); // انتشارات
            $table->string('author_text', 1000)->nullable(); // متن نویسندگان (برای جستجو)
            $table->string('edition', 60)->nullable(); // نسخه کتاب
            $table->string('year', 14)->nullable(); // سال انتشار (متنی)
            $table->smallInteger('year_numeric')->nullable(); // سال انتشار (عددی برای فیلتر)
            $table->string('pages', 100)->nullable(); // تعداد صفحات (متنی)
            $table->integer('pages_numeric')->nullable(); // تعداد صفحات (عددی برای فیلتر)
            $table->bigInteger('filesize')->nullable(); // حجم فایل به بایت
            $table->string('ipfs_cid', 100)->nullable(); // شناسه IPFS

            // قیمت‌گذاری و دسترسی
            $table->decimal('price', 12, 2)->default(30000); // قیمت ثابت کتاب
            $table->foreignId('access_level_id')->default(1)->constrained('access_levels'); // سطح دسترسی مورد نیاز
            $table->boolean('is_visible')->default(true); // آیا کتاب قابل نمایش است

            // لینک‌های فروشگاه‌های خارجی
            $table->string('amazon_link', 500)->nullable(); // لینک آمازون
            $table->string('google_link', 500)->nullable(); // لینک گوگل بوکس
            $table->json('other_store_links')->nullable(); // لینک سایر فروشگاه‌ها

            // آمار بازدید و خرید
            $table->integer('view_count')->default(0); // تعداد بازدید
            $table->integer('download_count')->default(0); // تعداد دانلود
            $table->integer('purchase_count')->default(0); // تعداد خرید

            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایندکس‌ها برای بهبود عملکرد
            $table->unique('md5');
            $table->index('language_code');
            $table->index('topic');
            $table->index('year_numeric');
            $table->index('access_level_id');
//            $table->index('author_text');
            $table->index('publisher');

            // ارتباط با جدول زبان‌ها
            $table->foreign('language_code')->references('code')->on('languages');

            // توضیحات جدول
            $table->comment('جدول اصلی کتاب‌ها با قیمت ثابت 30000 تومان و سطوح دسترسی');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
