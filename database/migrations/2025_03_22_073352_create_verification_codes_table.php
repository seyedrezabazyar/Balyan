<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->string('identifier', 191); // ایمیل یا شماره موبایل
            $table->string('type', 10); // نوع شناسه: email یا phone
            $table->string('code', 10); // کد تأیید
            $table->timestamp('expires_at'); // زمان انقضاء
            $table->boolean('used')->default(false); // آیا استفاده شده است؟
            $table->integer('attempts')->default(0); // تعداد تلاش‌های ناموفق
            $table->timestamps();

            // ایندکس‌ها برای بهبود عملکرد جستجو
            $table->index(['identifier', 'type']);
            $table->index('expires_at');
            $table->index('used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_codes');
    }
};
