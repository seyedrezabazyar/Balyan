<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Routes that are accessible only to guests (users who are not logged in)
Route::middleware('guest')->group(function () {
    // Login: Step 1 - Show login form
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    // Login: Step 1 - Handle identifier (phone/email) submission
    Route::post('/login/identify', [AuthController::class, 'identify'])->name('auth.identify');

    // Login: Step 2 - Show verification form (password or code)
    Route::get('/auth/verify', [AuthController::class, 'showVerificationForm'])->name('auth.verify-form');
    // Login: Step 2 - Handle verification code/password submission
    Route::post('/auth/verify', [AuthController::class, 'verify'])->name('auth.verify');

    // API routes for AJAX-based authentication (optional, if you're using AJAX)
    Route::post('/login/phone', [AuthController::class, 'loginApi'])->name('login.phone'); // You might not need this
    Route::post('/login/verify', [AuthController::class, 'verifyApi'])->name('login.verify'); // You might not need this
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
