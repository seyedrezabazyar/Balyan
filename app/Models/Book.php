<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mongo_id',
        'md5',
        'identifier',
        'extension',
        'language_code',
        'topic',
        'publisher',
        'author_text',
        'edition',
        'year',
        'year_numeric',
        'pages',
        'pages_numeric',
        'filesize',
        'ipfs_cid',
        'coverurl',
        'price',
        'access_level_id',
        'is_visible',
        'view_count',
        'download_count',
        'is_favorite',
        'reading_progress'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year_numeric' => 'integer',
        'pages_numeric' => 'integer',
        'filesize' => 'integer',
        'price' => 'float',
        'access_level_id' => 'integer',
        'is_visible' => 'boolean',
        'view_count' => 'integer',
        'download_count' => 'integer',
        'is_favorite' => 'boolean',
        'reading_progress' => 'integer'
    ];

    /**
     * ارتباط با کاربر مالک کتاب
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * توضیحات کتاب در زبان‌های مختلف
     */
    public function descriptions()
    {
        return $this->hasMany(BookDescription::class, 'book_md5', 'md5');
    }

    /**
     * دریافت عنوان کتاب از جدول توضیحات با زبان فارسی
     */
    public function getLocalTitleAttribute()
    {
        $description = $this->descriptions->where('language', 'fa')->first();
        return $description ? $description->title : $this->identifier ?? 'بدون عنوان';
    }

    /**
     * دریافت توضیحات کتاب از جدول توضیحات با زبان فارسی
     */
    public function getLocalDescriptionAttribute()
    {
        $description = $this->descriptions->where('language', 'fa')->first();
        return $description ? $description->description : null;
    }

    /**
     * دریافت قیمت با فرمت مناسب
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->price == 0) {
            return 'رایگان';
        }

        return number_format($this->price) . ' تومان';
    }

    /**
     * دریافت آدرس تصویر جلد کتاب
     */
    public function getCoverUrlAttribute($value)
    {
        if (empty($value)) {
            return asset('images/default-book-cover.png');
        }

        return $value;
    }
}
