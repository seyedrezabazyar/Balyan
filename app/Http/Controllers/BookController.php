<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function show($id)
    {
        // یافتن کتاب با آیدی مورد نظر
        $book = Book::findOrFail($id);

        // ارسال اطلاعات کتاب به view
        return view('book.show', compact('book'));
    }
}
