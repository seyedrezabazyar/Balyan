<?php

use App\Http\Controllers\Auth\IdentifierController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\profile\ProfileController;
use App\Http\Controllers\profile\AccountInfoController;
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

// مسیر مستقیم برای account-info (جدید - برای رفع مشکل 404)
Route::get('/profile/account-info', [AccountInfoController::class, 'index'])
    ->middleware(['auth'])
    ->name('profile.direct-account-info');

// مسیر نمایش پروفایل عمومی کاربر با نام کاربری - قابل دسترسی برای همه
Route::get('/profile/{username}', [ProfileController::class, 'showPublicProfile'])->name('public.profile');

// Logout route (accessible only to authenticated users)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware(['web', 'auth']);

// Dashboard routes (accessible only to authenticated users)
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    // صفحه اصلی داشبورد
    Route::get('/', [ProfileController::class, 'index'])->name('index');

    // کتاب‌های من
    Route::get('/my-books', [ProfileController::class, 'myBooks'])->name('my-books');

    // سفارشات من
    Route::get('/my-orders', [ProfileController::class, 'myOrders'])->name('my-orders');
    Route::get('/orders/{orderId}', [ProfileController::class, 'orderDetails'])->name('order-details');

    // علاقه‌مندی‌ها
    Route::get('/favorites', [ProfileController::class, 'favorites'])->name('favorites');

    // اطلاعات حساب کاربری - استفاده از کنترلر جدید AccountInfoController
    Route::get('/account-info', [AccountInfoController::class, 'index'])->name('account-info');
    Route::post('/account-info', [AccountInfoController::class, 'update'])->name('update-account-info');

    // تغییر رمز عبور
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('update-password');

    // کیف پول
    Route::get('/wallet', [ProfileController::class, 'wallet'])->name('wallet');

    // پیام‌ها
    Route::get('/messages', [ProfileController::class, 'messages'])->name('messages');
    Route::get('/messages/{messageId}', [ProfileController::class, 'messageDetails'])->name('message-details');

    // خروج (اضافه شده به بخش داشبورد)
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');
});

// General routes (accessible to everyone)

// Home page
Route::get('/', function () {
    return view('home');
})->name('home');

// Static pages (Terms, Privacy, etc.) - Grouped for clarity
Route::prefix('pages')->group(function () { // Added a prefix for better organization
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
});

// Search route
Route::get('/search', function () {
    return 'نتایج جستجو: ' . request('q');
})->name('search');
