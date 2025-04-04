<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول رابطه بین کتاب‌ها و نویسندگان
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_author', function (Blueprint $table) {
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade'); // شناسه کتاب
            $table->foreignId('author_id')->constrained('authors')->onDelete('cascade'); // شناسه نویسنده
            $table->string('author_role', 50)->default('author'); // نقش نویسنده: نویسنده، مترجم، ویراستار و...

            // تعریف کلید اصلی ترکیبی
            $table->primary(['book_id', 'author_id', 'author_role']);

            // ایندکس‌ها برای بهبود عملکرد
            $table->index(['book_id', 'author_role']);
            $table->index(['author_id', 'author_role']);

            // توضیحات جدول
            $table->comment('جدول ارتباطی بین کتاب‌ها و نویسندگان با تعیین نقش هر نویسنده');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_author');
    }
};
