<!-- resources/views/partials/header.blade.php -->
<header class="sticky-top">
    <nav class="navbar navbar-expand-lg navbar-glass shadow-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="کتابخانه دیجیتال" height="40">
            </a>

            <!-- دکمه جستجو در موبایل -->
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="search-toggle-btn d-lg-none" id="searchToggle">
                    <i class="fas fa-search"></i>
                </button>

                <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-hover-effect" href="{{ route('topics') }}">
                            <i class="fas fa-bookmark"></i> موضوعات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover-effect" href="{{ route('about') }}">
                            <i class="fas fa-info-circle"></i> درباره ما
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover-effect" href="{{ route('contact') }}">
                            <i class="fas fa-envelope"></i> تماس با ما
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <!-- جستجو در نسخه دسکتاپ -->
                    <div class="d-none d-lg-block">
                        <form action="{{ route('search') }}" method="GET" class="search-form position-relative">
                            <input type="text" name="q" class="form-control search-input" placeholder="جستجوی کتاب...">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- دکمه ورود/ثبت‌نام - اصلاح شده برای نمایش تمام صفحه در موبایل -->
                    @guest
                        <div class="w-100 d-lg-inline-block">
                            <a href="{{ route('login') }}" class="btn btn-login d-flex align-items-center">
                                <i class="fas fa-sign-in-alt"></i>  ورود
                            </a>
                        </div>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-user d-flex align-items-center dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    <img src="{{ Auth::user()->profile_photo_url ?? asset('images/avatar.png') }}" alt="{{ Auth::user()->name }}">
                                </div>
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end animated fadeIn">
                                <li class="dropdown-header text-center">
                                    <div class="user-avatar-lg mx-auto mb-2">
                                        <img src="{{ Auth::user()->profile_photo_url ?? asset('images/avatar.png') }}" alt="{{ Auth::user()->name }}">
                                    </div>
                                    <strong>{{ Auth::user()->name }}</strong>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2 text-primary"></i> پروفایل</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-book me-2 text-info"></i> کتاب‌های من</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-bookmark me-2 text-warning"></i> نشان‌شده‌ها</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> خروج
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- جستجوی تمام صفحه در موبایل -->
    <div class="mobile-search-overlay" id="mobileSearchOverlay">
        <button type="button" class="mobile-search-close" id="searchClose">
            <i class="fas fa-times"></i>
        </button>

        <form action="{{ route('search') }}" method="GET" class="mobile-search-form-fullscreen">
            <input type="text" name="q" class="mobile-search-input" placeholder="جستجوی کتاب، نویسنده یا موضوع..." autofocus>
            <button type="submit" class="mobile-search-btn">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <div class="mobile-search-hint">جستجوهای پرطرفدار:</div>
        <div class="mobile-search-tags">
            <a href="{{ route('search', ['q' => 'رمان']) }}" class="mobile-search-tag">رمان</a>
            <a href="{{ route('search', ['q' => 'روانشناسی']) }}" class="mobile-search-tag">روانشناسی</a>
            <a href="{{ route('search', ['q' => 'تاریخ']) }}" class="mobile-search-tag">تاریخ</a>
            <a href="{{ route('search', ['q' => 'علمی']) }}" class="mobile-search-tag">علمی</a>
            <a href="{{ route('search', ['q' => 'کودک']) }}" class="mobile-search-tag">کودک</a>
        </div>
    </div>
</header>
