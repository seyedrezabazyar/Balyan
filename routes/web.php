<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/topics', function () {
    return view('pages.topics');
})->name('topics');

Route::get('/book-request', function () {
    return view('pages.book-request');
})->name('book.request');

Route::get('/return-policy', function () {
    return view('pages.return-policy');
})->name('return.policy');

Route::get('/article-request', function () {
    return view('pages.article-request');
})->name('article.request');

Route::get('/ebook-guide', function () {
    return view('pages.ebook-guide');
})->name('ebook.guide');

Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

Route::get('/dmca', function () {
    return view('pages.dmca');
})->name('dmca');

Route::get('/tracking', function () {
    return view('pages.tracking');
})->name('tracking');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/search', function () {
    return 'نتایج جستجو: ' . request('q');
})->name('search');

Auth::routes();
Route::get('/login', function () {
    return 'صفحه ورود';
})->name('login');
Route::post('/logout', function () {
    return 'خروج';
})->name('logout');
