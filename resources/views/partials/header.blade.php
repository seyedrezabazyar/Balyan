<!-- resources/views/partials/header.blade.php -->
<header class="sticky-top">
    <nav class="navbar navbar-expand-lg navbar-glass shadow-sm" aria-label="منوی اصلی سایت">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}" title="کتابخانه دیجیتال بلیان">
                <img src="{{ asset('images/logo.png') }}" alt="کتابخانه دیجیتال بلیان" height="40" width="auto" style="max-height: 40px;">
            </a>

            <!-- دکمه جستجو در موبایل -->
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="search-toggle-btn d-lg-none" id="searchToggle" aria-label="جستجو">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </button>

                <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="نمایش منو">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-hover-effect" href="{{ route('topics') }}" title="موضوعات کتاب">
                            <i class="fas fa-bookmark" aria-hidden="true"></i> موضوعات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover-effect" href="#" title="پیگیری سفارش">
                            <i class="fas fa-shipping-fast" aria-hidden="true"></i> پیگیری سفارش
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover-effect" href="{{ route('about') }}" title="درباره ما">
                            <i class="fas fa-info-circle" aria-hidden="true"></i> درباره ما
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover-effect" href="{{ route('contact') }}" title="تماس با ما">
                            <i class="fas fa-envelope" aria-hidden="true"></i> تماس با ما
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <!-- جستجو در نسخه دسکتاپ -->
                    <div class="d-none d-lg-block">
                        <form action="{{ route('search') }}" method="GET" class="search-form position-relative">
                            <input type="text" name="q" class="form-control search-input" placeholder="جستجوی کتاب..." aria-label="جستجوی کتاب">
                            <button type="submit" class="search-btn" aria-label="جستجو">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>

                    <!-- دکمه ورود/ثبت‌نام یا منوی کاربر -->
                    @guest
                        <div class="w-100 d-lg-inline-block">
                            <a href="{{ route('login') }}" class="btn btn-login d-flex align-items-center">
                                <i class="fas fa-sign-in-alt" aria-hidden="true"></i> ورود
                            </a>
                        </div>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-user d-flex align-items-center dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar me-2">
                                    <img src="{{ Auth::user()->profile_photo_url ?? asset('images/avatar.png') }}" alt="{{ Auth::user()->name }}">
                                </div>
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end animated fadeIn" aria-labelledby="userDropdown">
                                <li class="dropdown-header text-center">
                                    <div class="user-avatar-lg mx-auto mb-2">
                                        <img src="{{ Auth::user()->profile_photo_url ?? asset('images/avatar.png') }}" alt="{{ Auth::user()->name }}">
                                    </div>
                                    <strong>{{ Auth::user()->name }}</strong>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.index') }}"><i class="fas fa-user me-2 text-primary" aria-hidden="true"></i> پروفایل</a></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.my-books') }}"><i class="fas fa-book me-2 text-info" aria-hidden="true"></i> کتاب‌های من</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2" aria-hidden="true"></i> خروج
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
        <button type="button" class="mobile-search-close" id="searchClose" aria-label="بستن جستجو">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>

        <form action="{{ route('search') }}" method="GET" class="mobile-search-form-fullscreen">
            <input type="text" name="q" class="mobile-search-input" placeholder="جستجوی کتاب، نویسنده یا موضوع..." aria-label="جستجوی کتاب" autofocus>
            <button type="submit" class="mobile-search-btn" aria-label="جستجو">
                <i class="fas fa-search" aria-hidden="true"></i>
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
