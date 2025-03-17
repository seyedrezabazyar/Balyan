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

Route::get('/about', function () {
    return 'درباره ما';
})->name('about');

Route::get('/contact', function () {
    return 'تماس با ما';
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
