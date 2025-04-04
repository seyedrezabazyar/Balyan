<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فایل مایگریشن برای ایجاد جدول نظرات کاربران
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // شناسه نظر (کلید اصلی)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // شناسه کاربر
            $table->enum('item_type', ['book', 'article']); // نوع آیتم: کتاب یا مقاله
            $table->bigInteger('item_id'); // شناسه آیتم (کتاب یا مقاله)
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // شناسه نظر والد (برای پاسخ‌ها)
            $table->smallInteger('rating')->nullable(); // امتیاز 1 تا 5 ستاره
            $table->text('content'); // متن نظر
            $table->boolean('is_approved')->default(false); // آیا نظر تایید شده است
            $table->integer('helpful_votes')->default(0); // تعداد رأی‌های مفید
            $table->integer('unhelpful_votes')->default(0); // تعداد رأی‌های غیرمفید
            $table->boolean('points_awarded')->default(false); // آیا بابت این نظر امتیاز داده شده است
            $table->timestamps(); // ستون‌های created_at و updated_at

            // ایندکس‌ها برای بهبود عملکرد
            $table->index('user_id');
            $table->index(['item_type', 'item_id']);
            $table->index('parent_id');
            $table->index('is_approved');
            $table->index('rating');

            // توضیحات جدول
            $table->comment('جدول نظرات کاربران برای کتاب‌ها و مقالات - کاربران بابت نظرات مفید امتیاز دریافت می‌کنند');
        });
    }

    /**
     * بازگرداندن تغییرات در صورت نیاز به rollback
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
