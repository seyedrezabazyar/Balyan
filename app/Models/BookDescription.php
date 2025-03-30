<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookDescription extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * کتاب مرتبط با این توضیحات
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_md5', 'md5');
    }
}
