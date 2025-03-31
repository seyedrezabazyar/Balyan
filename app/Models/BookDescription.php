<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookDescription extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'book_descriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'book_md5',
        'language',
        'title',
        'description',
        'toc',
        'subject',
        'publisher',
        'created_at',
        'updated_at'
    ];

    /**
     * کتاب مرتبط با این توضیحات
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_md5', 'md5');
    }
}
