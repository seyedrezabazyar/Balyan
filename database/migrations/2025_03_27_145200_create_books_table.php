<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('books');

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->char('mongo_id', 24);
            $table->char('md5', 32)->unique()->index();
            $table->string('identifier', 300)->nullable();
            $table->char('extension', 7);
            $table->string('language_code', 10)->nullable()->index();
            $table->string('topic', 500)->nullable()->index();
            $table->string('publisher', 400)->nullable();
            $table->string('author_text', 1000)->nullable();
            $table->string('edition', 60)->nullable();
            $table->string('year', 14)->nullable();
            $table->smallInteger('year_numeric')->nullable()->index()->comment('Generated from year');
            $table->string('pages', 100)->nullable();
            $table->integer('pages_numeric')->nullable()->comment('Generated from pages');
            $table->bigInteger('filesize')->nullable();
            $table->string('ipfs_cid', 100)->nullable();
            $table->string('coverurl', 200)->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('access_level_id')->default(1)->comment('Minimum level required to access this book');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            $table->integer('view_count')->default(0);
            $table->integer('download_count')->default(0);

            // کامنت جدول
            $table->comment('Main books table partitioned by year_numeric');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->integer('reading_progress')->default(0);
            $table->timestamps();
        });
    }
};
