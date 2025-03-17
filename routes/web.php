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
    return 'موضوعات';
})->name('topics');

Route::get('/faq', function () {
    return 'سوالات متداول';
})->name('faq');

Route::get('/dmca', function () {
    return view('pages.dmca');
})->name('dmca');

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
