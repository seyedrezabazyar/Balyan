<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\IdentifierController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\AccountInfoController;
use Illuminate\Support\Facades\Route;

// مسیر ورود مستقیم - فقط برای کاربران مهمان
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');

// مسیرهای احراز هویت - فقط برای کاربران مهمان
Route::middleware('guest')->prefix('auth')->name('auth.')->group(function () {
    Route::post('/identify', [IdentifierController::class, 'identify'])->name('identify');
    Route::get('/verify', [VerificationController::class, 'showVerificationForm'])->name('verify-form');
    Route::post('/resend-code', [VerificationController::class, 'resendVerificationCode'])->name('resend-code');
    Route::post('/verify-code', [VerificationController::class, 'verifyCode'])->name('verify-code');
    Route::post('/login-password', [LoginController::class, 'loginWithPassword'])->name('login-password');
    Route::post('/send-verification-code', [VerificationController::class, 'sendVerificationCode'])->name('send-verification-code');

    // مسیرهای جدید برای تایید اطلاعات تماس
    Route::post('/request-verification', [VerificationController::class, 'requestVerification'])->name('request-verification');
    Route::post('/verify-new-identifier', [VerificationController::class, 'verifyNewIdentifier'])->name('verify-new-identifier');
});

// مسیر خروج - فقط برای کاربران احراز هویت شده
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// مسیرهای داشبورد - فقط برای کاربران احراز هویت شده
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/my-books', [ProfileController::class, 'myBooks'])->name('my-books');
    Route::get('/my-orders', [ProfileController::class, 'myOrders'])->name('my-orders');
    Route::get('/orders/{orderId}', [ProfileController::class, 'orderDetails'])->name('order-details');
    Route::get('/favorites', [ProfileController::class, 'favorites'])->name('favorites');
    Route::get('/account-info', [AccountInfoController::class, 'index'])->name('account-info');
    Route::post('/account-info', [AccountInfoController::class, 'update'])->name('update-account-info');
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    Route::get('/wallet', [ProfileController::class, 'wallet'])->name('wallet');
    Route::get('/messages', [ProfileController::class, 'messages'])->name('messages');
    Route::get('/messages/{messageId}', [ProfileController::class, 'messageDetails'])->name('message-details');
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');

    // مسیرهای جدید برای تایید اطلاعات تماس
    Route::post('/request-verification', [VerificationController::class, 'requestVerification'])->name('request-verification');
    Route::post('/verify-new-identifier', [VerificationController::class, 'verifyNewIdentifier'])->name('verify-new-identifier');
});

// مسیرهای عمومی - قابل دسترسی برای همه
Route::get('/', [HomeController::class, 'index'])->name('home');

// پروفایل عمومی کاربر - قابل دسترسی برای همه
Route::get('/profile/{username}', [ProfileController::class, 'showPublicProfile'])->name('public.profile');

// صفحات استاتیک
Route::prefix('pages')->group(function () {
    Route::get('/terms', fn() => view('pages.terms'))->name('terms');
    Route::get('/privacy', fn() => view('pages.privacy'))->name('privacy');
    Route::get('/topics', fn() => view('pages.topics'))->name('topics');
    Route::get('/request-article', fn() => view('pages.request-article'))->name('request.article');
    Route::get('/request-book', fn() => view('pages.request-book'))->name('request.book');
    Route::get('/request-translation', fn() => view('pages.request-translation'))->name('request.translation');
    Route::get('/rewards', fn() => view('pages.rewards'))->name('rewards');
    Route::get('/return-policy', fn() => view('pages.return-policy'))->name('return.policy');
    Route::get('/ebook-guide', fn() => view('pages.ebook-guide'))->name('ebook.guide');
    Route::get('/faq', fn() => view('pages.faq'))->name('faq');
    Route::get('/dmca', fn() => view('pages.dmca'))->name('dmca');
    Route::get('/tracking', fn() => view('pages.tracking'))->name('tracking');
    Route::get('/about', fn() => view('pages.about'))->name('about');
    Route::get('/contact', fn() => view('pages.contact'))->name('contact');
});

Route::get('/book/{id}', [BookController::class, 'show'])->name('books.show');

// مسیر جستجو
Route::get('/search', fn() => 'نتایج جستجو: ' . request('q'))->name('search');
