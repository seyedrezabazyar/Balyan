<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول توضیحات کتاب به زبان‌های مختلف
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_descriptions', function (Blueprint $table) {
            $table->id(); // شناسه توضیحات (کلید اصلی)
            $table->char('book_md5', 32); // MD5 کتاب (ارتباط با جدول books)
            $table->enum('language', ['fa', 'en', 'ar']); // زبان توضیحات (فارسی، انگلیسی، عربی)
            $table->string('title', 500)->nullable(); // عنوان کتاب به زبان مربوطه
            $table->mediumText('description')->nullable(); // توضیحات کتاب
            $table->mediumText('table_of_contents')->nullable(); // فهرست مطالب
            $table->text('excerpt')->nullable(); // خلاصه کتاب
            $table->text('additional_info')->nullable(); // اطلاعات اضافی
            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('book_md5');
            $table->index('language');
            $table->unique(['book_md5', 'language']); // هر کتاب فقط یک توضیح به هر زبان دارد

            // ارتباط با جدول books
            $table->foreign('book_md5')->references('md5')->on('books')->onDelete('cascade');

            // توضیحات جدول
            $table->comment('جدول توضیحات کتاب به زبان‌های فارسی، انگلیسی و عربی');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_descriptions');
    }
};
