<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('book_descriptions');

        // ایجاد جدول توضیحات کتاب در زبان‌های مختلف
        Schema::create('book_descriptions', function (Blueprint $table) {
            $table->id();
            $table->char('book_md5', 32);
            $table->enum('language', ['fa', 'en', 'ar'])->index();

            // عنوان کتاب در زبان مربوطه
            $table->string('title', 500)->nullable();

            // توضیحات اصلی کتاب
            $table->text('description')->nullable();

            // فهرست مطالب
            $table->text('table_of_contents')->nullable();

            // چکیده یا بخشی از کتاب
            $table->text('excerpt')->nullable();

            // اطلاعات تکمیلی
            $table->text('additional_info')->nullable();

            $table->timestamps();

            // ایجاد کلید خارجی به جدول books
            $table->foreign('book_md5')->references('md5')->on('books')->onDelete('cascade');

            // ایجاد کلید ترکیبی منحصر به فرد (هر کتاب فقط یک توضیحات به هر زبان دارد)
            $table->unique(['book_md5', 'language']);

            // ایجاد شاخص متنی برای جستجو در توضیحات
            $table->fullText(['title', 'description', 'table_of_contents']);

            // کامنت جدول
            $table->comment('Multilingual descriptions for books');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_descriptions');
    }
};
