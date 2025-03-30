@extends('layouts.app')

@section('title', 'دنیای کتاب | کتابخانه دیجیتال هوشمند')

@section('content')
    <!-- بخش هدر و جستجو -->
    <div class="book-main-hero">
        <div class="book-hero-bg-shapes"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center" data-aos="fade-up">
                    <h1 class="book-hero-title">دنیای کتاب</h1>
                    <p class="book-hero-subtitle">دسترسی به هزاران کتاب الکترونیکی و صوتی در موضوعات مختلف</p>

                    <div class="book-search-container mt-5">
                        <form action="#" method="GET" class="book-search-form">
                            <div class="book-search-input-wrapper">
                                <input type="text" name="q" class="book-search-input" placeholder="جستجوی کتاب، نویسنده یا موضوع..." autocomplete="off">
                                <button type="submit" class="book-search-button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="book-search-tags mt-4">
                        <a href="#" class="book-search-tag">روانشناسی</a>
                        <a href="#" class="book-search-tag">رمان</a>
                        <a href="#" class="book-search-tag">برنامه نویسی</a>
                        <a href="#" class="book-search-tag">تاریخ</a>
                        <a href="#" class="book-search-tag">فلسفه</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش ویژه‌ها و پیشنهادات -->
    <div class="book-featured-section">
        <div class="container">
            <div class="book-featured-cards">
                <div class="row">
                    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="book-feature-card">
                            <div class="book-feature-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h3 class="book-feature-title">خرید سریع</h3>
                            <p class="book-feature-desc">در کمتر از ۳۰ ثانیه کتاب مورد نظر خود را خریداری کنید</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="book-feature-card">
                            <div class="book-feature-icon">
                                <i class="fas fa-headphones-alt"></i>
                            </div>
                            <h3 class="book-feature-title">کتاب‌های صوتی</h3>
                            <p class="book-feature-desc">دسترسی به بیش از ۱۰۰۰ کتاب صوتی با کیفیت بالا</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="book-feature-card">
                            <div class="book-feature-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h3 class="book-feature-title">اپلیکیشن موبایل</h3>
                            <p class="book-feature-desc">مطالعه در هر زمان و هر مکان با اپلیکیشن اختصاصی ما</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش کتاب‌های پرفروش -->
    <div class="book-bestselling-section">
        <div class="container">
            <div class="book-section-header" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="book-section-title">کتاب‌های پرفروش</h2>
                        <p class="book-section-subtitle">محبوب‌ترین‌های هفته گذشته</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="#" class="btn btn-outline-primary">مشاهده همه <i class="fas fa-arrow-left ms-2"></i></a>
                    </div>
                </div>
            </div>

            <div class="book-slider" data-aos="fade-up">
                <div class="row">
                    @forelse ($bestSellingBooks as $index => $book)
                        <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                            <div class="book-product-card">
                                <div class="book-product-img-container">
                                    <img src="{{ $book->coverurl }}" alt="{{ $book->local_title }}" class="book-product-img">
                                    <div class="book-product-badge">پرفروش</div>
                                    <button type="button" class="book-product-bookmark" aria-label="افزودن به علاقه‌مندی‌ها">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                                <div class="book-product-body">
                                    <h5 class="book-product-title">{{ $book->local_title }}</h5>
                                    <p class="book-product-author">نویسنده: {{ $book->author_text ?: 'نامشخص' }}</p>
                                    <p class="book-product-desc">{{ Str::limit($book->local_description, 120) ?: 'توضیحات کتاب موجود نیست.' }}</p>
                                    <div class="book-product-footer">
                                        <div class="book-product-price">
                                            @if($book->price > 0)
                                                <span class="book-discount">{{ number_format($book->price * 1.2) }}</span>
                                            @endif
                                            {{ $book->formatted_price }}
                                        </div>
                                        <a href="#" class="btn book-btn-add-cart">
                                            <i class="fas fa-shopping-cart me-1"></i> خرید
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="book-empty-state">
                                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                <h4>کتاب پرفروشی یافت نشد</h4>
                                <p class="text-muted">در حال حاضر کتاب پرفروشی در سیستم ثبت نشده است.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- بخش چالش کتابخوانی -->
    <div class="book-challenge-section">
        <div class="container">
            <div class="book-daily-challenge-container" data-aos="fade-up">
                <div class="book-daily-challenge-card">
                    <div class="book-challenge-header">
                        <div class="book-challenge-badge">
                            <span class="book-badge-text">چالش هفتگی</span>
                        </div>
                        <div class="book-challenge-timer">
                            <i class="far fa-clock"></i>
                            <span id="challengeTimer">5 روز و 12:34:56</span>
                        </div>
                    </div>

                    <div class="book-challenge-body">
                        <div class="book-challenge-icon-container">
                            <div class="book-challenge-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                        </div>

                        <div class="book-challenge-content">
                            <h3 class="book-challenge-title">مسابقه کتابخوانی: «تفکر سریع و کند»</h3>
                            <div class="book-challenge-description">
                                <p>در مسابقه کتابخوانی هفته شرکت کنید و با پاسخ به سوالات، برنده جوایز نقدی و اشتراک ویژه شوید!</p>
                            </div>
                            <div class="book-challenge-reward-badge">
                                جایزه: <span>یک میلیون تومان + اشتراک طلایی</span>
                            </div>

                            <div class="book-challenge-participants">
                                <div class="book-participant-avatars">
                                    <div class="book-participant-avatar"><img src="https://i.pravatar.cc/150?img=1" alt="شرکت کننده"></div>
                                    <div class="book-participant-avatar"><img src="https://i.pravatar.cc/150?img=2" alt="شرکت کننده"></div>
                                    <div class="book-participant-avatar"><img src="https://i.pravatar.cc/150?img=3" alt="شرکت کننده"></div>
                                    <div class="book-participant-avatar"><img src="https://i.pravatar.cc/150?img=4" alt="شرکت کننده"></div>
                                    <div class="book-participant-avatar book-more-participants">+۱۷۸</div>
                                </div>
                                <span class="book-participants-count">۱۸۲ نفر تاکنون شرکت کرده‌اند</span>
                            </div>

                            <button class="btn book-btn-challenge">شرکت در مسابقه</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش کتاب‌های جدید -->
    <div class="book-new-books-section">
        <div class="container">
            <div class="book-section-header" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="book-section-title">کتاب‌های جدید</h2>
                        <p class="book-section-subtitle">تازه‌ترین کتاب‌های اضافه شده</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="#" class="btn btn-outline-primary">مشاهده همه <i class="fas fa-arrow-left ms-2"></i></a>
                    </div>
                </div>
            </div>

            <div class="book-featured-books-container">
                <div class="row">
                    @forelse ($newBooks as $index => $book)
                        <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 50 }}">
                            <a href="#" class="book-card-link">
                                <div class="book-card">
                                    <div class="book-cover">
                                        @if(isset($book->coverurl) && $book->coverurl)
                                            <img src="{{ $book->coverurl }}" alt="{{ $book->title ?? $book->local_title }}" onerror="this.src='{{ asset('images/no-cover.png') }}'">
                                        @else
                                            <img src="{{ asset('images/no-cover.png') }}" alt="بدون تصویر">
                                        @endif

                                        <div class="book-format">
                                            <span class="badge {{ isset($book->extension) && $book->extension == 'pdf' ? 'bg-primary' : (isset($book->extension) && $book->extension == 'epub' ? 'bg-info' : 'bg-secondary') }}">
                                                {{ strtoupper($book->extension ?? 'PDF') }}
                                            </span>
                                        </div>

                                        <div class="book-new-badge">
                                            <span>جدید</span>
                                        </div>
                                    </div>

                                    <div class="book-info">
                                        <h3 class="book-title">{{ Str::limit($book->title ?? $book->local_title, 50) }}</h3>

                                        <div class="book-price">
                                            @if(isset($book->price) && $book->price > 0)
                                                <span class="book-current-price">{{ number_format($book->price) }} تومان</span>
                                            @else
                                                <span class="book-current-price">{{ $book->formatted_price ?? 'رایگان' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="book-empty-state">
                                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                <h4>کتاب جدیدی یافت نشد</h4>
                                <p class="text-muted">در حال حاضر کتاب جدیدی در سیستم ثبت نشده است.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- بخش دسته‌بندی‌ها -->
    <div class="book-categories-section">
        <div class="container">
            <div class="book-section-header" data-aos="fade-up">
                <h2 class="book-section-title">دسته‌بندی‌های محبوب</h2>
                <p class="book-section-subtitle">کتاب‌های مورد علاقه خود را در دسته‌بندی‌های مختلف پیدا کنید</p>
            </div>

            <div class="book-categories-container">
                <div class="row">
                    <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <a href="#" class="book-category-card">
                            <div class="book-category-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h3 class="book-category-title">روانشناسی</h3>
                            <p class="book-category-count">۱,۲۳۴ کتاب</p>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <a href="#" class="book-category-card">
                            <div class="book-category-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <h3 class="book-category-title">رمان و داستان</h3>
                            <p class="book-category-count">۲,۵۶۷ کتاب</p>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                        <a href="#" class="book-category-card">
                            <div class="book-category-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="book-category-title">کسب و کار</h3>
                            <p class="book-category-count">۹۸۷ کتاب</p>
                        </a>
                    </div>

                    <div class="col-6 col-md-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                        <a href="#" class="book-category-card">
                            <div class="book-category-icon">
                                <i class="fas fa-code"></i>
                            </div>
                            <h3 class="book-category-title">برنامه‌نویسی</h3>
                            <p class="book-category-count">۷۶۵ کتاب</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش پیشنهاد ویژه -->
    <div class="book-special-offer-section">
        <div class="container">
            <div class="book-special-offer-container" data-aos="fade-up">
                <div class="book-special-offer-card">
                    <div class="book-offer-content">
                        <div class="book-offer-badge">پیشنهاد ویژه</div>
                        <h3 class="book-offer-title">اشتراک یک‌ساله با ۴۰٪ تخفیف</h3>
                        <p class="book-offer-desc">با خرید اشتراک یک‌ساله، علاوه بر ۴۰٪ تخفیف، به مدت ۳ ماه اشتراک صوتی رایگان دریافت کنید.</p>
                        <div class="book-offer-timer">
                            <div class="book-timer-item">
                                <span class="book-timer-value" id="timerDays">۰۲</span>
                                <span class="book-timer-label">روز</span>
                            </div>
                            <div class="book-timer-item">
                                <span class="book-timer-value" id="timerHours">۱۱</span>
                                <span class="book-timer-label">ساعت</span>
                            </div>
                            <div class="book-timer-item">
                                <span class="book-timer-value" id="timerMinutes">۳۵</span>
                                <span class="book-timer-label">دقیقه</span>
                            </div>
                            <div class="book-timer-item">
                                <span class="book-timer-value" id="timerSeconds">۵۴</span>
                                <span class="book-timer-label">ثانیه</span>
                            </div>
                        </div>
                        <button class="btn book-btn-offer">خرید اشتراک ویژه</button>
                    </div>
                    <div class="book-offer-image-container">
                        <img src="{{ asset('images/special-offer.png') }}" alt="پیشنهاد ویژه" class="book-offer-image">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش مزایای عضویت -->
    <div class="book-membership-section">
        <div class="container">
            <div class="book-section-header text-center" data-aos="fade-up">
                <h2 class="book-section-title">مزایای عضویت در دنیای کتاب</h2>
                <p class="book-section-subtitle">با عضویت در دنیای کتاب، از امکانات ویژه بهره‌مند شوید</p>
            </div>

            <div class="book-membership-features" data-aos="fade-up">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="book-membership-feature">
                            <div class="book-feature-number">۱</div>
                            <div class="book-feature-content">
                                <h3 class="book-feature-title">دسترسی نامحدود</h3>
                                <p class="book-feature-desc">دسترسی کامل به بیش از ۵۰,۰۰۰ کتاب الکترونیکی و صوتی</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="book-membership-feature">
                            <div class="book-feature-number">۲</div>
                            <div class="book-feature-content">
                                <h3 class="book-feature-title">بدون تبلیغات</h3>
                                <p class="book-feature-desc">مطالعه و گوش دادن به کتاب‌ها بدون هیچ تبلیغ مزاحمی</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="book-membership-feature">
                            <div class="book-feature-number">۳</div>
                            <div class="book-feature-content">
                                <h3 class="book-feature-title">دانلود نامحدود</h3>
                                <p class="book-feature-desc">امکان دانلود کتاب‌ها برای مطالعه و گوش دادن آفلاین</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4" data-aos="fade-up">
                <a href="#" class="btn btn-primary btn-lg">مشاهده طرح‌های اشتراک</a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* متغیرهای اصلی */
        :root {
            --book-primary-color: #4361ee;
            --book-primary-hover: #3a56d4;
            --book-primary-light: rgba(67, 97, 238, 0.1);
            --book-secondary-color: #ff6b6b;
            --book-success-color: #38b000;
            --book-info-color: #4cc9f0;
            --book-warning-color: #ffbe0b;
            --book-danger-color: #ef233c;
            --book-dark-color: #212529;
            --book-light-color: #f8f9fa;
            --book-border-radius: 10px;
            --book-box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            --book-transition: all 0.3s ease;
        }

        /* استایل‌های عمومی */
        .book-section-title {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
            position: relative;
            padding-bottom: 0.5rem;
            display: inline-block;
        }

        .book-section-title::after {
            content: '';
            position: absolute;
            left: auto;
            right: 0;
            bottom: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, var(--book-primary-color), var(--book-primary-hover));
            border-radius: 3px;
        }

        .book-section-subtitle {
            color: #6c757d;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .book-section-header {
            margin-bottom: 30px;
        }

        .book-empty-state {
            background-color: white;
            border-radius: var(--book-border-radius);
            padding: 40px;
            text-align: center;
            border: 1px dashed #dee2e6;
        }

        /* هدر اصلی */
        .book-main-hero {
            background: linear-gradient(125deg, #7209b7 0%, #560bad 40%, #3a0ca3 100%);
            padding: 100px 0 80px;
            color: white;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .book-hero-bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 10%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 15%),
                radial-gradient(circle at 40% 70%, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 12%),
                radial-gradient(circle at 70% 80%, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 10%);
        }

        .book-hero-bg-shapes::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .book-hero-bg-shapes::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"),
                linear-gradient(to bottom, rgba(114, 9, 183, 0) 0%, rgba(58, 12, 163, 0.6) 100%);
            mix-blend-mode: overlay;
        }

        .book-main-hero .container {
            position: relative;
            z-index: 2;
        }

        .book-hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .book-hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .book-search-container {
            max-width: 700px;
            margin: 0 auto;
            position: relative;
        }

        .book-search-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            background: white;
            border-radius: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .book-search-input {
            flex-grow: 1;
            height: 48px;
            background: transparent;
            border: none;
            padding: 0 20px;
            color: #333;
            font-size: 1rem;
        }

        .book-search-input::placeholder {
            color: #777;
        }

        .book-search-input:focus {
            outline: none;
        }

        .book-search-button {
            background: none;
            border: none;
            height: 48px;
            width: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--book-primary-color);
            font-size: 1.2rem;
            cursor: pointer;
            transition: var(--book-transition);
        }

        .book-search-button:hover {
            background-color: var(--book-primary-light);
        }

        .book-search-tags {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .book-search-tag {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-decoration: none;
            transition: var(--book-transition);
        }

        .book-search-tag:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px);
            color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* بخش ویژه‌ها و پیشنهادات */
        .book-featured-section {
            padding: 50px 0 30px;
            background-color: white;
        }

        .book-featured-cards {
            margin-bottom: 20px;
        }

        .book-feature-card {
            background-color: white;
            border-radius: var(--book-border-radius);
            padding: 30px 20px;
            text-align: center;
            box-shadow: var(--book-box-shadow);
            height: 100%;
            transition: var(--book-transition);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .book-feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, var(--book-primary-color), var(--book-primary-hover));
            z-index: -1;
        }

        .book-feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .book-feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--book-primary-color) 0%, var(--book-primary-hover) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
            transition: var(--book-transition);
        }

        .book-feature-card:hover .book-feature-icon {
            transform: scale(1.1);
        }

        .book-feature-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .book-feature-desc {
            color: #6c757d;
            margin-bottom: 0;
            line-height: 1.6;
        }

        /* بخش کتاب‌های پرفروش */
        .book-bestselling-section {
            padding: 70px 0;
            background-color: #f8f9fa;
            position: relative;
            overflow: hidden;
        }

        .book-bestselling-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234361ee' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .book-product-card {
            border: none;
            border-radius: var(--book-border-radius);
            overflow: hidden;
            transition: all var(--book-transition);
            background-color: white;
            box-shadow: var(--book-box-shadow);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .book-product-card:hover {
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            transform: translateY(-6px);
        }

        .book-product-card:hover .book-product-img {
            transform: scale(1.05);
        }

        .book-product-img-container {
            position: relative;
            overflow: hidden;
            height: 200px;
        }

        .book-product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--book-transition);
        }

        .book-product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(to right, #3a0ca3, #4361ee);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .book-product-bookmark {
            position: absolute;
            top: 10px;
            left: 10px;
            background: white;
            color: #6c757d;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: all var(--book-transition);
            z-index: 1;
            border: none;
        }

        .book-product-bookmark:hover {
            color: var(--book-danger-color);
            transform: scale(1.1);
        }

        .book-product-bookmark.active {
            color: var(--book-danger-color);
        }

        .book-product-body {
            padding: 1.2rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .book-product-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--book-dark-color);
            transition: color var(--book-transition);
        }

        .book-product-card:hover .book-product-title {
            color: var(--book-primary-color);
        }

        .book-product-author {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.8rem;
        }

        .book-product-desc {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 1rem;
            flex-grow: 1;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 0.8rem;
            border-top: 1px solid #f1f1f1;
        }

        .book-product-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--book-primary-color);
        }

        .book-product-price .book-discount {
            font-size: 0.8rem;
            color: #6c757d;
            text-decoration: line-through;
            margin-right: 0.5rem;
        }

        .book-btn-add-cart {
            background: linear-gradient(to right, var(--book-primary-color), var(--book-primary-hover));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1.2rem;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all var(--book-transition);
        }

        .book-btn-add-cart:hover {
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
            transform: translateY(-3px);
            color: white;
        }

        /* بخش چالش کتابخوانی */
        .book-challenge-section {
            padding: 40px 0;
            background-color: white;
        }

        .book-daily-challenge-container {
            margin-bottom: 30px;
        }

        .book-daily-challenge-card {
            background: white;
            border-radius: var(--book-border-radius);
            overflow: hidden;
            box-shadow: var(--book-box-shadow);
            position: relative;
        }

        .book-challenge-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(to right, #FF416C, #FF4B2B);
            color: white;
            padding: 15px 20px;
        }

        .book-challenge-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .book-badge-text {
            display: flex;
            align-items: center;
        }

        .book-badge-text::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: white;
            border-radius: 50%;
            margin-left: 8px;
            animation: pulse-badge 2s infinite;
        }

        @keyframes pulse-badge {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 5px rgba(255, 255, 255, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        .book-challenge-timer {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .book-challenge-timer i {
            font-size: 1.1rem;
        }

        .book-challenge-body {
            padding: 30px;
            display: flex;
            gap: 30px;
        }

        .book-challenge-icon-container {
            flex-shrink: 0;
        }

        .book-challenge-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FF416C, #FF4B2B);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 10px 20px rgba(255, 65, 108, 0.3);
        }

        .book-challenge-content {
            flex-grow: 1;
        }

        .book-challenge-title {
            font-size: 1.4rem;
            margin-bottom: 10px;
        }

        .book-challenge-description {
            color: #6c757d;
            margin-bottom: 15px;
        }

        .book-challenge-reward-badge {
            display: inline-block;
            background: rgba(255, 65, 108, 0.1);
            color: #FF416C;
            padding: 8px 15px;
            border-radius: var(--book-border-radius);
            font-weight: 600;
            margin-bottom: 20px;
        }

        .book-challenge-reward-badge span {
            color: var(--book-success-color);
        }

        .book-challenge-participants {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 20px;
        }

        .book-participant-avatars {
            display: flex;
        }

        .book-participant-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid white;
            margin-right: -10px;
            background-color: #e9ecef;
        }

        .book-participant-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-more-participants {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
            background-color: #e9ecef;
        }

        .book-participants-count {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .book-btn-challenge {
            background: linear-gradient(to right, #FF416C, #FF4B2B);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(255, 65, 108, 0.3);
            transition: var(--book-transition);
        }

        .book-btn-challenge:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 65, 108, 0.4);
            color: white;
        }

        /* بخش کتاب‌های جدید */
        .book-new-books-section {
            padding: 70px 0;
            background-color: #f8f9fa;
        }

        .book-featured-books-container {
            margin-bottom: 20px;
        }

        .book-card-link {
            display: block;
            text-decoration: none;
            color: inherit;
            height: 100%;
        }

        .book-card {
            border-radius: var(--book-border-radius);
            overflow: hidden;
            background-color: white;
            box-shadow: var(--book-box-shadow);
            height: 100%;
            transition: var(--book-transition);
            position: relative;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .book-cover {
            position: relative;
            overflow: hidden;
            padding-top: 150%; /* Aspect ratio 2:3 */
        }

        .book-cover img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .book-card:hover .book-cover img {
            transform: scale(1.05);
        }

        .book-format {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
        }

        .book-new-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: var(--book-info-color);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            z-index: 2;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        .book-info {
            padding: 15px;
        }

        .book-title {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 15px;
            height: 2.8em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            color: #333;
        }

        .book-price {
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .book-price-container {
            display: flex;
            flex-direction: column;
        }

        .book-current-price {
            font-weight: 700;
            color: var(--book-success-color);
            font-size: 1.1rem;
        }

        .book-original-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* بخش دسته‌بندی‌ها */
        .book-categories-section {
            padding: 80px 0;
            background-color: white;
        }

        .book-categories-container {
            margin-bottom: 20px;
        }

        .book-category-card {
            background-color: white;
            border-radius: var(--book-border-radius);
            padding: 25px;
            text-align: center;
            box-shadow: var(--book-box-shadow);
            height: 100%;
            transition: var(--book-transition);
            display: block;
            text-decoration: none;
            color: #333;
        }

        .book-category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            color: #333;
        }

        .book-category-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--book-primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 20px;
            transition: var(--book-transition);
        }

        .book-category-card:hover .book-category-icon {
            background-color: var(--book-primary-color);
            color: white;
            transform: scale(1.1);
        }

        .book-category-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .book-category-count {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        /* بخش پیشنهاد ویژه */
        .book-special-offer-section {
            padding: 70px 0;
            background-color: #f8f9fa;
            background-image:
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234361ee' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .book-special-offer-container {
            margin-bottom: 20px;
        }

        .book-special-offer-card {
            background: white;
            border-radius: var(--book-border-radius);
            overflow: hidden;
            box-shadow: var(--book-box-shadow);
            display: flex;
            position: relative;
        }

        .book-offer-content {
            padding: 40px;
            flex: 1;
        }

        .book-offer-badge {
            display: inline-block;
            background: linear-gradient(to right, #7209b7, #3a0ca3);
            color: white;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .book-offer-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 15px;
            color: #1a1a1a;
        }

        .book-offer-desc {
            color: #555;
            font-size: 1.1rem;
            margin-bottom: 25px;
            line-height: 1.7;
        }

        .book-offer-timer {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .book-timer-item {
            background: linear-gradient(135deg, #3a0ca3, #4361ee);
            color: white;
            border-radius: var(--book-border-radius);
            padding: 15px 10px;
            min-width: 70px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(58, 12, 163, 0.2);
        }

        .book-timer-value {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
            margin-bottom: 5px;
        }

        .book-timer-label {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .book-btn-offer {
            background: linear-gradient(to right, #7209b7, #3a0ca3);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 5px 15px rgba(114, 9, 183, 0.3);
            transition: var(--book-transition);
        }

        .book-btn-offer:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(114, 9, 183, 0.4);
            color: white;
        }

        .book-offer-image-container {
            flex: 0.8;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(135deg, #7209b7, #3a0ca3);
        }

        .book-offer-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.2;
        }

        /* بخش مزایای عضویت */
        .book-membership-section {
            padding: 80px 0;
            background-color: white;
        }

        .book-membership-features {
            margin-bottom: 40px;
        }

        .book-membership-feature {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 20px;
            background: white;
            border-radius: var(--book-border-radius);
            box-shadow: var(--book-box-shadow);
            transition: var(--book-transition);
            height: 100%;
        }

        .book-membership-feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .book-feature-number {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--book-primary-color), var(--book-primary-hover));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .book-feature-content {
            flex-grow: 1;
        }

        /* استایل‌های واکنش‌گرا */
        @media (max-width: 991.98px) {
            .book-hero-title {
                font-size: 3rem;
            }

            .book-offer-content {
                padding: 30px;
            }

            .book-offer-title {
                font-size: 1.6rem;
            }

            .book-offer-desc {
                font-size: 1rem;
            }

            .book-timer-item {
                min-width: 60px;
                padding: 10px 8px;
            }

            .book-timer-value {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 767.98px) {
            .book-hero-title {
                font-size: 2.5rem;
            }

            .book-hero-subtitle {
                font-size: 1.2rem;
            }

            .book-search-input-wrapper {
                height: 45px;
            }

            .book-search-input {
                height: 45px;
                font-size: 1rem;
            }

            .book-search-button {
                height: 45px;
                width: 45px;
            }

            .book-feature-card {
                padding: 20px 15px;
            }

            .book-feature-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .book-challenge-body {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .book-challenge-icon-container {
                margin-bottom: 20px;
            }

            .book-special-offer-card {
                flex-direction: column;
            }

            .book-offer-content {
                order: 2;
                text-align: center;
            }

            .book-offer-image-container {
                order: 1;
                height: 200px;
            }

            .book-timer-item {
                flex: 1;
            }
        }

        @media (max-width: 575.98px) {
            .book-hero-title {
                font-size: 2rem;
            }

            .book-search-input {
                padding: 0 10px;
            }

            .book-offer-timer {
                flex-wrap: wrap;
            }

            .book-timer-item {
                min-width: calc(50% - 5px);
            }

            .book-btn-offer {
                width: 100%;
            }

            .book-product-img-container {
                height: 180px;
            }

            .book-membership-feature {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .book-feature-number {
                margin-bottom: 10px;
            }
        }

        /* انیمیشن‌ها */
        @keyframes shine {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }
            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }

        .book-btn-shine {
            position: relative;
            overflow: hidden;
            border: none;
        }

        .book-btn-shine::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            transform: rotate(45deg);
            animation: shine 3s infinite;
            pointer-events: none;
        }
    </style>
@endpush
