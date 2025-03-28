<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'author',
        'description',
        'cover_image',
        'is_favorite',
        'reading_progress'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
