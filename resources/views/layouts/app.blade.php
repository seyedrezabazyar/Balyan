<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="کتابخانه دیجیتال بلیان، یک سامانه معرفی و دانلود کتاب با گستره‌ای از موضوعات متنوع">
    <meta name="keywords" content="کتاب، کتابخانه دیجیتال، دانلود کتاب، مقالات علمی، کتاب الکترونیک">
    <meta name="author" content="بلیان">
    <meta name="theme-color" content="#3b82f6">
    <meta property="og:title" content="کتابخانه دیجیتال بلیان">
    <meta property="og:description" content="کتابخانه دیجیتال بلیان، یک سامانه معرفی و دانلود کتاب با گستره‌ای از موضوعات متنوع">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <!-- تگ CSRF توکن برای درخواست‌های ایجکس -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'کتابخانه دیجیتال بلیان')</title>

    <!-- فونت آیکون -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- بوت استرپ -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.rtl.min.css">
    <!-- استایل سفارشی -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- فیکون -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    @stack('head')

    @stack('styles')
</head>
<body>
@include('partials.header')

<main id="content">
    @yield('content')
</main>

@include('partials.footer')

<!-- اسکریپت‌های بوت استرپ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- اسکریپت‌های اصلی سایت -->
<script src="{{ asset('js/script.js') }}"></script>
<!-- تنظیم CSRF توکن برای درخواست‌های ایجکس -->
<script>
    // تنظیم CSRF توکن برای تمام درخواست‌های FETCH
    document.addEventListener('DOMContentLoaded', function() {
        // در صورتی که اسکریپتی از FETCH استفاده می‌کند، توکن CSRF را به صورت خودکار اضافه می‌کنیم
        const originalFetch = window.fetch;
        window.fetch = function(url, options = {}) {
            if(!options.headers) {
                options.headers = {};
            }

            // اضافه کردن CSRF توکن برای درخواست‌های POST، PUT، DELETE و PATCH
            if(['POST', 'PUT', 'DELETE', 'PATCH'].includes(options.method)) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if(csrfToken) {
                    options.headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
                }
            }

            return originalFetch(url, options);
        };
    });
</script>
@stack('scripts')
</body>
</html>
