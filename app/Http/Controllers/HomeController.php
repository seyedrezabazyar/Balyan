<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * نمایش صفحه اصلی سایت
     */
    public function index()
    {
        // دریافت کتاب‌های پرفروش (بر اساس تعداد دانلود)
        $bestSellingBooks = Book::with(['descriptions' => function($query) {
            $query->where('language', 'fa');
        }])
            ->where('is_visible', true)
            ->orderBy('download_count', 'desc')
            ->take(4)
            ->get();

        // دریافت کتاب‌های جدید
        $newBooks = Book::with(['descriptions' => function($query) {
            $query->where('language', 'fa');
        }])
            ->where('is_visible', true)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('home', compact('bestSellingBooks', 'newBooks'));
    }
}
