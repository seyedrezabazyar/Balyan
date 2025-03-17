<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'کتابخانه دیجیتال')</title>

    <!-- فونت‌آوسام -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- بوت‌استرپ -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css">

    <!-- استایل سفارشی -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>
<body>
@include('partials.header')

<main class="py-4">
    @yield('content')
</main>

@include('partials.footer')

<!-- بوت‌استرپ جاوااسکریپت -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- اسکریپت جستجوی تمام صفحه -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchToggle = document.getElementById('searchToggle');
        const searchClose = document.getElementById('searchClose');
        const searchOverlay = document.getElementById('mobileSearchOverlay');

        if (searchToggle && searchClose && searchOverlay) {
            searchToggle.addEventListener('click', function() {
                searchOverlay.classList.add('active');
                setTimeout(() => {
                    searchOverlay.querySelector('input').focus();
                }, 100);
                document.body.style.overflow = 'hidden';
            });

            searchClose.addEventListener('click', function() {
                searchOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
    });
</script>

@stack('scripts')
</body>
</html>
