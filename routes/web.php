<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\IdentifierController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Authentication routes accessible only to guests
Route::prefix('auth')->middleware(['web', 'guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/identify', [IdentifierController::class, 'identify'])->name('auth.identify');
    Route::get('/verify', [VerificationController::class, 'showVerificationForm'])->name('auth.verify-form');
    Route::post('/resend-code', [VerificationController::class, 'resendVerificationCode'])->name('auth.resend-code');
    Route::post('/verify-code', [VerificationController::class, 'verifyCode'])->name('auth.verify-code');
    Route::post('/login-password', [LoginController::class, 'loginWithPassword'])->name('auth.login-password');

    // اضافه کردن مسیر جدید برای ارسال کد تأیید
    Route::post('/send-verification-code', [VerificationController::class, 'sendVerificationCode'])->name('auth.send-verification-code');
});

// Logout route (accessible only to authenticated users)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware(['web', 'auth']);

// Dashboard routes (accessible only to authenticated users)
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    // صفحه اصلی داشبورد
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // کتاب‌های من
    Route::get('/my-books', [DashboardController::class, 'myBooks'])->name('my-books');

    // سفارشات من
    Route::get('/my-orders', [DashboardController::class, 'myOrders'])->name('my-orders');
    Route::get('/orders/{orderId}', [DashboardController::class, 'orderDetails'])->name('order-details');

    // علاقه‌مندی‌ها
    Route::get('/favorites', [DashboardController::class, 'favorites'])->name('favorites');

    // اطلاعات حساب کاربری
    Route::get('/account-info', [DashboardController::class, 'accountInfo'])->name('account-info');
    Route::post('/account-info', [DashboardController::class, 'updateAccountInfo'])->name('update-account-info');

    // تغییر رمز عبور
    Route::get('/change-password', [DashboardController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [DashboardController::class, 'updatePassword'])->name('update-password');

    // کیف پول
    Route::get('/wallet', [DashboardController::class, 'wallet'])->name('wallet');

    // پیام‌ها
    Route::get('/messages', [DashboardController::class, 'messages'])->name('messages');
    Route::get('/messages/{messageId}', [DashboardController::class, 'messageDetails'])->name('message-details');

    // خروج (اضافه شده به بخش داشبورد)
    Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');
});

// General routes (accessible to everyone)

// Home page
Route::get('/', function () {
    return view('home');
})->name('home');

// Static pages (Terms, Privacy, etc.) - Grouped for clarity
Route::prefix('pages')->group(function () { // Added a prefix for better organization
    Route::get('/terms', function () {
        return view('pages.terms');    })->name('terms');

    Route::get('/privacy', function () {
        return view('pages.privacy');
    })->name('privacy');

    Route::get('/topics', function () {
        return view('pages.topics');
    })->name('topics');    Route::get('/request-article', function () {
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
});


// Search route
Route::get('/search', function () {
    return 'نتایج جستجو: ' . request('q');
})->name('search');
