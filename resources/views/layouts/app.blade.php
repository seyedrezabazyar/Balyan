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
@stack('scripts')
</body>
</html>
