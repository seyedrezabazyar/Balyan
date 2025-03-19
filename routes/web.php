<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // مرحله اول: ورود شماره موبایل یا ایمیل
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login/identify', [AuthController::class, 'identify'])->name('login.identify');

    // مرحله دوم: تأیید با رمز عبور یا کد تأیید
    Route::get('/verify', [AuthController::class, 'showVerificationForm'])->name('auth.verify-form');
    Route::post('/verify', [AuthController::class, 'verify'])->name('auth.verify');

    // مسیرهای API برای احراز هویت با AJAX
    Route::post('/login/phone', [AuthController::class, 'loginApi'])->name('login.phone');
    Route::post('/login/verify', [AuthController::class, 'verifyApi'])->name('login.verify');
});

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

Route::get('/request-article', function () {
    return view('pages.request-article');
})->name('request.article');

Route::get('/request-book', function () {
    return view('pages.request-book');
})->name('request.book');

Route::get('/request-translation', function () {
    return view('pages.request-translation');
})->name('request.translation');

Route::get('/rewards', function () {
    return view('pages.rewards');
})->name('rewards');

Route::get('/return-policy', function () {
    return view('pages.return-policy');
})->name('return.policy');


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
