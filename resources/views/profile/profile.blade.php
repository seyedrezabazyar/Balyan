@extends('layouts.app')

@section('title', 'پروفایل کاربری - کتابخانه دیجیتال بلیان')

@section('content')
    <div class="profile-container">
        <div class="container py-5">
            <!-- نوار منوی پروفایل -->
            <div class="profile-navbar mb-4">
                <div class="row">
                    <div class="col-12">
                        <div class="profile-nav">
                            <a href="{{ route('profile.index') }}" class="profile-nav-item active">
                                <i class="fas fa-home"></i>
                                <span>داشبورد</span>
                            </a>
                            <a href="{{ route('profile.account-info') }}" class="profile-nav-item">
                                <i class="fas fa-user-edit"></i>
                                <span>ویرایش پروفایل</span>
                            </a>
                            <a href="{{ route('profile.my-books') }}" class="profile-nav-item">
                                <i class="fas fa-book"></i>
                                <span>کتاب‌های من</span>
                            </a>
                            <a href="{{ route('profile.favorites') }}" class="profile-nav-item">
                                <i class="fas fa-heart"></i>
                                <span>علاقه‌مندی‌ها</span>
                            </a>
                            <a href="{{ route('logout') }}" class="profile-nav-item logout-btn"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>خروج</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- اعلان تکمیل پروفایل - فقط اگر کاربر هنوز نام کاربری خود را تغییر نداده باشد -->
            @if(Auth::user()->username_changed_at === null)
                <div class="profile-alert mb-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="profile-alert-box">
                                <div class="alert-icon">
                                    <i class="fas fa-gift"></i>
                                </div>
                                <div class="alert-content">
                                    <h5>پروفایل خود را تکمیل کنید و کد تخفیف ۲۰٪ دریافت کنید!</h5>
                                    <p>با تکمیل اطلاعات پروفایل خود، علاوه بر کسب امتیاز ویژه، یک کد تخفیف ۲۰ درصدی برای خرید کتاب‌های بالیان دریافت خواهید کرد.</p>
                                    <a href="{{ route('profile.account-info') }}" class="btn btn-warning btn-sm mt-2">
                                        <i class="fas fa-user-edit me-1"></i> تکمیل پروفایل
                                    </a>
                                </div>
                                <button type="button" class="alert-close-btn" onclick="this.parentElement.style.display='none';">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- اعلان تایید ایمیل/شماره تلفن - فقط اگر کاربر ایمیل یا شماره تلفن تایید نشده داشته باشد -->
            @if(Auth::user()->email && !Auth::user()->hasVerifiedEmail() || Auth::user()->phone && !Auth::user()->hasVerifiedPhone())
                <div class="profile-alert mb-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="profile-alert-box alert-verify">
                                <div class="alert-icon">
                                    <i class="fas fa-envelope-open-text"></i>
                                </div>
                                <div class="alert-content">
                                    <h5>تایید اطلاعات تماس</h5>
                                    <p>
                                        @if(Auth::user()->email && !Auth::user()->hasVerifiedEmail() && Auth::user()->phone && !Auth::user()->hasVerifiedPhone())
                                            برای استفاده از تمامی امکانات سامانه و دریافت کد تخفیف 20%، لطفاً ایمیل و شماره موبایل خود را تایید کنید.
                                        @elseif(Auth::user()->email && !Auth::user()->hasVerifiedEmail())
                                            برای استفاده از تمامی امکانات سامانه و دریافت کد تخفیف 20%، لطفاً ایمیل خود را تایید کنید.
                                        @else
                                            برای استفاده از تمامی امکانات سامانه و دریافت کد تخفیف 20%، لطفاً شماره موبایل خود را تایید کنید.
                                        @endif
                                    </p>
                                    <div class="mt-2">
                                        @if(Auth::user()->email && !Auth::user()->hasVerifiedEmail())
                                            <button class="btn btn-primary btn-sm request-verification" data-type="email" data-identifier="{{ Auth::user()->email }}">
                                                <i class="fas fa-envelope me-1"></i> تایید ایمیل
                                            </button>
                                        @endif

                                        @if(Auth::user()->phone && !Auth::user()->hasVerifiedPhone())
                                            <button class="btn btn-primary btn-sm request-verification ms-2" data-type="phone" data-identifier="{{ Auth::user()->phone }}">
                                                <i class="fas fa-mobile-alt me-1"></i> تایید شماره موبایل
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <button type="button" class="alert-close-btn" onclick="this.parentElement.style.display='none';">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- بخش هدر پروفایل -->
            <div class="profile-header mb-5">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="profile-card text-center">
                            <div class="profile-avatar-container">
                                <div class="profile-level-badge">سطح {{ $userLevel ?? 1 }}</div>
                                <div class="profile-avatar">
                                    @if(Auth::user()->profile_image)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="{{ Auth::user()->first_name }}">
                                    @else
                                        <div class="default-avatar">
                                            {{ substr(Auth::user()->first_name ?? 'کاربر', 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="avatar-edit-btn">
                                        <a href="{{ route('profile.account-info') }}" title="تغییر تصویر">
                                            <i class="fas fa-camera"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <h3 class="profile-name mt-3">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                            <p class="profile-phone" dir="ltr">{{ Auth::user()->phone }}</p>

                            <div class="user-rank">
                                <span class="rank-title">{{ $userRank ?? 'کتاب‌خوان فعال' }}</span>
                            </div>

                            <div class="user-stats mt-3">
                                <div class="stat-item">
                                    <div class="stat-value">{{ $readingScore ?? 72 }}</div>
                                    <div class="stat-label">امتیاز مطالعه</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ $favoriteBooks ?? 0 }}</div>
                                    <div class="stat-label">علاقه‌مندی</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ $daysSinceJoin ?? 0 }}</div>
                                    <div class="stat-label">روز عضویت</div>
                                </div>
                            </div>

                            <a href="{{ route('profile.account-info') }}" class="btn btn-outline-light mt-4">
                                <i class="fas fa-user-edit me-2"></i> ویرایش پروفایل
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="achievement-card">
                            <div class="card-header">
                                <h4><i class="fas fa-trophy me-2"></i> دستاوردهای شما</h4>
                            </div>
                            <div class="card-body">
                                <div class="progress-section mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>پیشرفت به سطح بعدی</span>
                                        <span>{{ $levelProgress ?? 25 }}%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar"
                                             role="progressbar"
                                             style="width: {{ $levelProgress ?? 25 }}%"
                                             aria-valuenow="{{ $levelProgress ?? 25 }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress-info mt-2">
                                        {{ $pointsToNextLevel ?? 75 }} امتیاز تا سطح {{ ($userLevel ?? 1) + 1 }}
                                    </div>
                                </div>

                                <div class="achievements-grid">
                                    <div class="achievement-item {{ ($achievements['reading_starter'] ?? false) ? 'achieved' : '' }}">
                                        <div class="achievement-icon">
                                            <i class="fas fa-book-open"></i>
                                        </div>
                                        <div class="achievement-details">
                                            <h5>شروع مطالعه</h5>
                                            <p>اولین کتاب خود را مطالعه کردید</p>
                                        </div>
                                    </div>

                                    <div class="achievement-item {{ ($achievements['profile_complete'] ?? false) ? 'achieved' : '' }}">
                                        <div class="achievement-icon">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                        <div class="achievement-details">
                                            <h5>پروفایل کامل</h5>
                                            <p>اطلاعات پروفایل را تکمیل کردید</p>
                                        </div>
                                    </div>

                                    <div class="achievement-item {{ ($achievements['five_favorites'] ?? false) ? 'achieved' : '' }}">
                                        <div class="achievement-icon">
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <div class="achievement-details">
                                            <h5>کالکشن کتاب</h5>
                                            <p>۵ کتاب به علاقه‌مندی‌های خود افزودید</p>
                                        </div>
                                    </div>

                                    <div class="achievement-item {{ ($achievements['regular_reader'] ?? false) ? 'achieved' : '' }}">
                                        <div class="achievement-icon">
                                            <i class="fas fa-book-reader"></i>
                                        </div>
                                        <div class="achievement-details">
                                            <h5>مطالعه‌گر منظم</h5>
                                            <p>۳۰ روز متوالی مطالعه داشتید</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- بخش آمار و امتیازات -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="points-card mb-4">
                        <div class="card-header">
                            <h4><i class="fas fa-star me-2"></i> امتیازات شما</h4>
                        </div>
                        <div class="card-body">
                            <div class="total-points">
                                <span class="points-value">{{ $totalPoints ?? 25 }}</span>
                                <span class="points-label">امتیاز کل</span>
                            </div>

                            <hr class="points-divider">

                            <div class="points-breakdown">
                                <div class="points-item">
                                    <div class="points-icon">
                                        <i class="fas fa-book-reader"></i>
                                    </div>
                                    <div class="points-details">
                                        <div class="points-type">مطالعه روزانه</div>
                                        <div class="points-earned">{{ $readingPoints ?? 15 }} امتیاز</div>
                                    </div>
                                </div>

                                <div class="points-item">
                                    <div class="points-icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="points-details">
                                        <div class="points-type">علاقه‌مندی‌ها</div>
                                        <div class="points-earned">{{ $favoritePoints ?? 0 }} امتیاز</div>
                                    </div>
                                </div>

                                <div class="points-item">
                                    <div class="points-icon">
                                        <i class="fas fa-medal"></i>
                                    </div>
                                    <div class="points-details">
                                        <div class="points-type">دستاوردها</div>
                                        <div class="points-earned">{{ $achievementPoints ?? 10 }} امتیاز</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="points-info">
                                <i class="fas fa-info-circle me-2"></i>
                                با مطالعه روزانه و تکمیل چالش‌های هفتگی، امتیازات بیشتری کسب کنید!
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="badges-card">
                        <div class="card-header">
                            <h4><i class="fas fa-certificate me-2"></i> نشان‌های شما</h4>
                        </div>
                        <div class="card-body">
                            <div class="badges-grid">
                                <div class="badge-item active" title="کاربر جدید">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="badge-item {{ ($achievements['profile_complete'] ?? false) ? 'active' : '' }}" title="پروفایل کامل">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="badge-item {{ ($achievements['reading_starter'] ?? false) ? 'active' : '' }}" title="شروع مطالعه">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div class="badge-item {{ ($achievements['five_favorites'] ?? false) ? 'active' : '' }}" title="کالکشن کتاب">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="badge-item {{ ($achievements['regular_reader'] ?? false) ? 'active' : '' }}" title="مطالعه‌گر منظم">
                                    <i class="fas fa-book-reader"></i>
                                </div>
                                <div class="badge-item {{ ($achievements['social_sharer'] ?? false) ? 'active' : '' }}" title="اشتراک‌گذار">
                                    <i class="fas fa-share-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- بخش کتابخانه من و علاقه‌مندی‌ها -->
            <div class="row">
                <!-- بخش کتابخانه من -->
                <div class="col-lg-6 mb-4">
                    <div class="my-library-section">
                        <div class="section-header d-flex justify-content-between align-items-center mb-4">
                            <h3><i class="fas fa-book-reader me-2"></i> کتابخانه من</h3>
                            <a href="{{ route('profile.my-books') }}" class="btn btn-sm btn-outline-primary">مشاهده همه</a>
                        </div>

                        <div class="books-wrapper">
                            @if($recentBooks && $recentBooks->count() > 0)
                                <div class="row">
                                    @foreach($recentBooks->take(4) as $book)
                                        <div class="col-md-6 mb-4">
                                            <div class="book-card">
                                                <div class="book-cover">
                                                    @if($book->cover_image)
                                                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                                    @else
                                                        <img src="{{ asset('images/book-placeholder.png') }}" alt="{{ $book->title }}">
                                                    @endif
                                                    <div class="reading-progress" title="{{ $book->reading_progress ?? 0 }}% خوانده شده">
                                                        <div class="progress-bar" style="width: {{ $book->reading_progress ?? 0 }}%"></div>
                                                    </div>
                                                    <div class="book-actions">
                                                        <a href="#" class="book-action-btn download-btn" title="دانلود">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <a href="#" class="book-action-btn read-btn" title="مطالعه">
                                                            <i class="fas fa-book-open"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="book-info">
                                                    <h5 class="book-title">{{ $book->title }}</h5>
                                                    <p class="book-author">{{ $book->author }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-books text-center py-4">
                                    <div class="empty-icon mb-3">
                                        <i class="fas fa-book-reader"></i>
                                    </div>
                                    <h4>کتابخانه شما خالی است</h4>
                                    <p class="text-muted">با افزودن کتاب به کتابخانه، سفر مطالعه خود را شروع کنید</p>
                                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-search me-2"></i> جستجوی کتاب
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- بخش علاقه‌مندی‌ها -->
                <div class="col-lg-6 mb-4">
                    <div class="favorites-section">
                        <div class="section-header d-flex justify-content-between align-items-center mb-4">
                            <h3><i class="fas fa-heart me-2"></i> علاقه‌مندی‌های من</h3>
                            <a href="{{ route('profile.favorites') }}" class="btn btn-sm btn-outline-primary">مشاهده همه</a>
                        </div>

                        <div class="favorites-wrapper">
                            @if(isset($favoritesList) && $favoritesList->count() > 0)
                                <div class="row">
                                    @foreach($favoritesList->take(4) as $book)
                                        <div class="col-md-6 mb-4">
                                            <div class="book-card">
                                                <div class="book-cover">
                                                    @if($book->cover_image)
                                                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                                    @else
                                                        <img src="{{ asset('images/book-placeholder.png') }}" alt="{{ $book->title }}">
                                                    @endif
                                                    <div class="book-actions">
                                                        <a href="#" class="book-action-btn detail-btn" title="جزئیات">
                                                            <i class="fas fa-info-circle"></i>
                                                        </a>
                                                        <a href="#" class="book-action-btn buy-btn" title="افزودن به کتابخانه">
                                                            <i class="fas fa-plus-circle"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="book-info">
                                                    <h5 class="book-title">{{ $book->title }}</h5>
                                                    <p class="book-author">{{ $book->author }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-books text-center py-4">
                                    <div class="empty-icon mb-3">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <h4>هنوز کتابی به علاقه‌مندی‌ها اضافه نکرده‌اید</h4>
                                    <p class="text-muted">کتاب‌های مورد علاقه خود را با کلیک روی آیکون قلب ذخیره کنید</p>
                                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-search me-2"></i> جستجوی کتاب
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- مودال تایید کد -->
            <div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="verificationModalLabel">تایید هویت</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <div class="verification-icon mb-3">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <h5 class="verification-title">کد تایید ارسال شده را وارد کنید</h5>
                                <p class="verification-subtitle" id="verificationIdentifier"></p>
                            </div>

                            <div class="verification-code-container mb-4">
                                <div class="verification-code-inputs">
                                    <input type="text" class="form-control code-input" maxlength="1" data-index="1" autocomplete="off">
                                    <input type="text" class="form-control code-input" maxlength="1" data-index="2" autocomplete="off">
                                    <input type="text" class="form-control code-input" maxlength="1" data-index="3" autocomplete="off">
                                    <input type="text" class="form-control code-input" maxlength="1" data-index="4" autocomplete="off">
                                    <input type="text" class="form-control code-input" maxlength="1" data-index="5" autocomplete="off">
                                    <input type="text" class="form-control code-input" maxlength="1" data-index="6" autocomplete="off">
                                </div>
                                <div class="verification-code-timer mt-3 text-center">
                                    <span id="countdown-timer">در حال بارگذاری...</span>
                                </div>
                            </div>

                            <div id="verification-message" class="alert d-none"></div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">انصراف</button>
                                <div>
                                    <button type="button" class="btn btn-link btn-resend-code me-2" id="resendCodeBtn" disabled>
                                        <i class="fas fa-redo-alt me-1"></i> ارسال مجدد کد
                                    </button>
                                    <button type="button" class="btn btn-primary" id="verifyCodeBtn">
                                        <i class="fas fa-check me-1"></i> تایید
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تگ مخفی برای ذخیره اطلاعات تایید -->
            <input type="hidden" id="verification_identifier_type">
            <input type="hidden" id="verification_identifier">
            <input type="hidden" id="verification_code">
            <input type="hidden" id="verification_expires_at">
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تابع انیمیشن اعداد
            function animateValue(obj, start, end, duration) {
                let startTimestamp = null;
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    obj.innerHTML = Math.floor(progress * (end - start) + start);
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            }

            // انیمیشن اعداد در آمار کاربر
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(element => {
                const finalValue = parseInt(element.textContent);
                animateValue(element, 0, finalValue, 1000);
            });

            // انیمیشن امتیاز کل
            const pointsValue = document.querySelector('.points-value');
            if (pointsValue) {
                const finalPoints = parseInt(pointsValue.textContent);
                animateValue(pointsValue, 0, finalPoints, 1200);
            }

            // نمایش tooltip برای نشان‌ها
            const badgeItems = document.querySelectorAll('.badge-item');
            badgeItems.forEach(badge => {
                const title = badge.getAttribute('title');
                const tooltip = document.createElement('div');
                tooltip.className = 'badge-tooltip';
                tooltip.textContent = title;
                badge.appendChild(tooltip);
            });

            // ---- کد جدید برای تایید ایمیل و شماره تلفن ----

            // عناصر مودال
            const verificationModal = document.getElementById('verificationModal');
            if (!verificationModal) return; // اگر مودال وجود ندارد از اینجا خارج شو

            const bsVerificationModal = new bootstrap.Modal(verificationModal);
            const modalTitle = document.getElementById('verificationModalLabel');
            const verificationIdentifierElement = document.getElementById('verificationIdentifier');
            const codeInputs = document.querySelectorAll('.code-input');
            const countdownTimer = document.getElementById('countdown-timer');
            const verificationMessage = document.getElementById('verification-message');
            const verifyButton = document.getElementById('verifyCodeBtn');
            const resendButton = document.getElementById('resendCodeBtn');

            // فیلدهای مخفی
            const verificationIdentifierTypeField = document.getElementById('verification_identifier_type');
            const verificationIdentifierField = document.getElementById('verification_identifier');
            const verificationCodeField = document.getElementById('verification_code');
            const verificationExpiresAtField = document.getElementById('verification_expires_at');

            // دکمه‌های درخواست تایید
            const requestButtons = document.querySelectorAll('.request-verification');

            // تایمر مربوط به کد تایید
            let countdownInterval = null;

            // تبدیل اعداد فارسی/عربی به انگلیسی
            function convertPersianToEnglish(str) {
                if (!str) return str;
                const persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                const arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
                const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

                let result = str.toString();

                for (let i = 0; i < 10; i++) {
                    const persianRegex = new RegExp(persianNumbers[i], 'g');
                    const arabicRegex = new RegExp(arabicNumbers[i], 'g');
                    result = result.replace(persianRegex, englishNumbers[i])
                        .replace(arabicRegex, englishNumbers[i]);
                }

                return result;
            }

            // به‌روزرسانی کد تایید در فیلد مخفی
            function updateVerificationCode() {
                let code = '';
                codeInputs.forEach(input => {
                    code += input.value;
                });

                if (verificationCodeField) {
                    verificationCodeField.value = code;
                }
            }

            // شروع تایمر شمارش معکوس
            function startCountdown(expiresAt) {
                clearInterval(countdownInterval);

                countdownInterval = setInterval(() => {
                    const now = new Date().getTime();
                    const timeLeft = expiresAt - now;

                    if (timeLeft <= 0) {
                        clearInterval(countdownInterval);
                        countdownTimer.textContent = 'کد تایید منقضی شده است';
                        countdownTimer.classList.add('text-danger');
                        resendButton.disabled = false;
                        return;
                    }

                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    countdownTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} مانده تا انقضای کد`;
                    countdownTimer.classList.remove('text-danger');
                }, 1000);
            }

            // نمایش پیام در مودال
            function showMessage(message, type = 'danger') {
                verificationMessage.textContent = message;
                verificationMessage.className = `alert alert-${type}`;
                verificationMessage.classList.remove('d-none');

                setTimeout(() => {
                    verificationMessage.classList.add('d-none');
                }, 5000);
            }

            // درخواست کد تایید
            function requestVerificationCode(type, identifier) {
                // ذخیره اطلاعات در فیلدهای مخفی
                verificationIdentifierTypeField.value = type;
                verificationIdentifierField.value = identifier;

                // تنظیم عنوان مودال
                if (type === 'email') {
                    modalTitle.textContent = 'تایید ایمیل';
                    const verificationIcon = document.querySelector('.verification-icon i');
                    if (verificationIcon) {
                        verificationIcon.className = 'fas fa-envelope';
                    }
                } else {
                    modalTitle.textContent = 'تایید شماره موبایل';
                    const verificationIcon = document.querySelector('.verification-icon i');
                    if (verificationIcon) {
                        verificationIcon.className = 'fas fa-mobile-alt';
                    }
                }
                verificationIdentifierElement.textContent = identifier;

                // پاکسازی فیلدهای کد
                codeInputs.forEach(input => {
                    input.value = '';
                });
                updateVerificationCode();

                // پاکسازی پیام‌های قبلی
                verificationMessage.classList.add('d-none');

                // غیرفعال کردن دکمه ارسال مجدد
                resendButton.disabled = true;

                // نمایش مودال
                bsVerificationModal.show();

                // اضافه کردن کلاس لودینگ به دکمه تایید
                const sendButton = document.querySelector('.request-verification[data-type="' + type + '"]');
                if (sendButton) {
                    sendButton.classList.add('btn-loading');
                    sendButton.disabled = true;
                }

                // ارسال درخواست به سرور
                fetch('/auth/request-verification', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        identifier_type: type,
                        identifier: identifier
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        // حذف کلاس لودینگ
                        if (sendButton) {
                            sendButton.classList.remove('btn-loading');
                            sendButton.disabled = false;
                        }

                        if (data.success) {
                            // ذخیره زمان انقضا
                            verificationExpiresAtField.value = data.expires_at;

                            // شروع تایمر
                            startCountdown(data.expires_at);

                            // نمایش پیام موفقیت
                            showMessage(data.message, 'success');

                            // فعال کردن دکمه ارسال مجدد پس از مدتی
                            setTimeout(() => {
                                resendButton.disabled = false;
                            }, 60000); // 60 ثانیه

                            // تمرکز روی اولین فیلد کد
                            codeInputs[0].focus();
                        } else {
                            // نمایش پیام خطا
                            showMessage(data.message || 'خطا در ارسال کد تایید', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // حذف کلاس لودینگ
                        if (sendButton) {
                            sendButton.classList.remove('btn-loading');
                            sendButton.disabled = false;
                        }

                        showMessage('خطا در برقراری ارتباط با سرور', 'danger');
                    });
            }

            // تایید کد
            function verifyCode() {
                const code = verificationCodeField.value;
                const type = verificationIdentifierTypeField.value;
                const identifier = verificationIdentifierField.value;

                if (code.length !== 6) {
                    showMessage('لطفاً کد 6 رقمی را کامل وارد کنید', 'warning');
                    return;
                }

                // اضافه کردن کلاس لودینگ به دکمه تایید
                verifyButton.classList.add('btn-loading');
                verifyButton.disabled = true;

                // ارسال درخواست به سرور
                fetch('/auth/verify-new-identifier', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        verification_code: code
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        // حذف کلاس لودینگ
                        verifyButton.classList.remove('btn-loading');
                        verifyButton.disabled = false;

                        if (data.success) {
                            // نمایش پیام موفقیت
                            showMessage(data.message, 'success');

                            // بستن مودال پس از مدتی
                            setTimeout(() => {
                                bsVerificationModal.hide();

                                // بارگذاری مجدد صفحه
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                } else {
                                    window.location.reload();
                                }
                            }, 2000);
                        } else {
                            // نمایش پیام خطا
                            showMessage(data.message || 'کد تایید نامعتبر است', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // حذف کلاس لودینگ
                        verifyButton.classList.remove('btn-loading');
                        verifyButton.disabled = false;

                        showMessage('خطا در برقراری ارتباط با سرور', 'danger');
                    });
            }

            // ارسال مجدد کد
            function resendVerificationCode() {
                const type = verificationIdentifierTypeField.value;
                const identifier = verificationIdentifierField.value;

                // غیرفعال کردن دکمه
                resendButton.disabled = true;
                resendButton.classList.add('btn-loading');

                // ارسال درخواست به سرور
                fetch('/auth/request-verification', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        identifier_type: type,
                        identifier: identifier
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        // حذف کلاس لودینگ
                        resendButton.classList.remove('btn-loading');

                        if (data.success) {
                            // ذخیره زمان انقضا
                            verificationExpiresAtField.value = data.expires_at;

                            // شروع تایمر
                            startCountdown(data.expires_at);

                            // نمایش پیام موفقیت
                            showMessage(data.message, 'success');

                            // فعال کردن دکمه ارسال مجدد پس از مدتی
                            setTimeout(() => {
                                resendButton.disabled = false;
                            }, 60000); // 60 ثانیه

                            // پاکسازی فیلدهای کد
                            codeInputs.forEach(input => {
                                input.value = '';
                            });
                            updateVerificationCode();

                            // تمرکز روی اولین فیلد کد
                            codeInputs[0].focus();
                        } else {
                            // نمایش پیام خطا
                            showMessage(data.message || 'خطا در ارسال مجدد کد تایید', 'danger');

                            // فعال کردن دکمه ارسال مجدد
                            resendButton.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // حذف کلاس لودینگ
                        resendButton.classList.remove('btn-loading');

                        showMessage('خطا در برقراری ارتباط با سرور', 'danger');

                        // فعال کردن دکمه ارسال مجدد
                        resendButton.disabled = false;
                    });
            }

            // رویدادها برای فیلدهای کد تایید
            if (codeInputs.length > 0) {
                codeInputs.forEach((input, index) => {
                    // انتخاب متن هنگام کلیک
                    input.addEventListener('click', function() {
                        this.select();
                    });

                    // پردازش ورودی
                    input.addEventListener('input', function(e) {
                        let value = convertPersianToEnglish(this.value);

                        if (value !== this.value) {
                            this.value = value;
                        }

                        if (!/^\d*$/.test(value)) {
                            this.value = '';
                            return;
                        }

                        if (value.length === 1 && index < codeInputs.length - 1) {
                            codeInputs[index + 1].focus();
                            codeInputs[index + 1].select();
                        }

                        updateVerificationCode();

                        // اگر تمام فیلدها پر شدند، بررسی خودکار کد
                        let allFilled = true;
                        codeInputs.forEach(inp => {
                            if (!inp.value) allFilled = false;
                        });

                        if (allFilled) {
                            // کمی تاخیر برای اطمینان از به‌روزرسانی کد
                            setTimeout(() => {
                                verifyCode();
                            }, 300);
                        }
                    });

                    // کلیدهای جهت‌دار و Backspace
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'ArrowRight' && index > 0) {
                            codeInputs[index - 1].focus();
                            codeInputs[index - 1].select();
                        } else if (e.key === 'ArrowLeft' && index < codeInputs.length - 1) {
                            codeInputs[index + 1].focus();
                            codeInputs[index + 1].select();
                        } else if (e.key === 'Backspace' && !this.value.length && index > 0) {
                            codeInputs[index - 1].focus();
                            codeInputs[index - 1].select();
                        }
                    });

                    // پیست کردن کد
                    input.addEventListener('paste', function(e) {
                        e.preventDefault();

                        let pasteData = e.clipboardData.getData('text');
                        pasteData = convertPersianToEnglish(pasteData);
                        pasteData = pasteData.replace(/\D/g, '');

                        for (let i = 0; i < Math.min(pasteData.length, codeInputs.length); i++) {
                            codeInputs[i].value = pasteData[i];
                        }

                        const nextIndex = Math.min(index + pasteData.length, codeInputs.length - 1);
                        codeInputs[nextIndex].focus();

                        updateVerificationCode();

                        // اگر تعداد ارقام پیست شده کافی بود، بررسی خودکار کد
                        if (pasteData.length >= 6) {
                            setTimeout(() => {
                                verifyCode();
                            }, 300);
                        }
                    });
                });
            }

            // رویداد دکمه‌های درخواست تایید
            if (requestButtons) {
                requestButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const type = this.getAttribute('data-type');
                        const identifier = this.getAttribute('data-identifier');
                        requestVerificationCode(type, identifier);
                    });
                });
            }

            // رویداد دکمه تایید کد
            if (verifyButton) {
                verifyButton.addEventListener('click', verifyCode);
            }

            // رویداد دکمه ارسال مجدد
            if (resendButton) {
                resendButton.addEventListener('click', resendVerificationCode);
            }

            // بستن مودال به صورت دستی
            verificationModal.addEventListener('hidden.bs.modal', function () {
                // پاکسازی اینتروال تایمر
                clearInterval(countdownInterval);
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        /* استایل‌های اصلی پروفایل */
        .profile-container {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding-top: 30px;
        }

        /* نوار منوی پروفایل */
        .profile-nav {
            display: flex;
            justify-content: space-between;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .profile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px 10px;
            color: #6c757d;
            text-decoration: none;
            flex: 1;
            text-align: center;
            transition: all 0.2s ease;
        }

        .profile-nav-item i {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .profile-nav-item span {
            font-size: 14px;
            font-weight: 500;
        }

        .profile-nav-item.active {
            color: #4270e4;
            background-color: rgba(66, 112, 228, 0.05);
            border-bottom: 3px solid #4270e4;
        }

        .profile-nav-item:hover {
            color: #4270e4;
            background-color: rgba(66, 112, 228, 0.05);
        }

        .profile-nav-item.logout-btn {
            color: #e74c3c;
        }

        .profile-nav-item.logout-btn:hover {
            background-color: rgba(231, 76, 60, 0.05);
        }

        /* اعلان تکمیل پروفایل */
        .profile-alert-box {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            background-color: #fff3cd;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .alert-icon {
            width: 50px;
            height: 50px;
            background-color: #f47e20;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-left: 15px;
            flex-shrink: 0;
        }

        .alert-content {
            flex: 1;
        }

        .alert-content h5 {
            font-size: 16px;
            color: #856404;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .alert-content p {
            font-size: 14px;
            color: #856404;
            margin-bottom: 0;
        }

        .alert-close-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            border: none;
            background: none;
            color: #856404;
            font-size: 16px;
            cursor: pointer;
            padding: 5px;
        }

        /* کارت پروفایل */
        .profile-card {
            background-color: #4270e4;
            border-radius: 12px;
            padding: 25px;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar-container {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto 15px;
        }

        .profile-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid rgba(255, 255, 255, 0.3);
            background-color: #fff;
            position: relative;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .default-avatar {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            font-weight: bold;
            color: #4270e4;
            background-color: #e9ecef;
        }

        .avatar-edit-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #fff;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .avatar-edit-btn a {
            color: #4270e4;
            font-size: 14px;
        }

        .profile-level-badge {
            position: absolute;
            top: 0;
            left: 0;
            background-color: #f47e20;
            color: white;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 12px;
            z-index: 2;
        }

        .profile-name {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .profile-phone {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 10px;
        }

        .user-rank {
            margin: 10px 0;
        }

        .rank-title {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }

        .user-stats {
            display: flex;
            justify-content: space-around;
            margin: 15px 0;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 12px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 22px;
            font-weight: bold;
        }

        .stat-label {
            font-size: 12px;
            opacity: 0.8;
        }

        /* کارت دستاوردها */
        .achievement-card, .my-library-section, .favorites-section {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            height: 100%;
        }

        .card-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }

        .card-header h4 {
            margin: 0;
            color: #333;
            font-size: 18px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        /* پیشرفت سطح */
        .progress-section .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
        }

        .progress-section .progress-bar {
            background-color: #4270e4;
            border-radius: 4px;
        }

        .progress-info {
            font-size: 13px;
            color: #6c757d;
            text-align: center;
        }

        /* گرید دستاوردها */
        .achievements-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .achievement-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
            background-color: #f8f9fa;
            border: 1px solid #eee;
            opacity: 0.7;
        }

        .achievement-item.achieved {
            background-color: #e8f5e9;
            border-color: #c8e6c9;
            opacity: 1;
        }

        .achievement-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-size: 18px;
            color: #6c757d;
        }

        .achievement-item.achieved .achievement-icon {
            background-color: #27ae60;
            color: white;
        }

        /* کارت امتیازات */
        .points-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            height: 100%;
        }

        .card-footer {
            background-color: #f8f9fa;
            padding: 12px 20px;
            border-top: 1px solid #eee;
        }

        .total-points {
            text-align: center;
            margin-bottom: 15px;
        }

        .points-value {
            font-size: 38px;
            font-weight: bold;
            color: #4270e4;
            display: block;
        }

        .points-label {
            font-size: 15px;
            color: #6c757d;
        }

        .points-divider {
            margin: 15px 0;
            border-color: #eee;
        }

        .points-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding: 8px;
            border-radius: 8px;
            transition: background-color 0.2s ease;
        }

        .points-item:hover {
            background-color: #f8f9fa;
        }

        .points-item:last-child {
            margin-bottom: 0;
        }

        .points-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 12px;
            font-size: 15px;
            color: #4270e4;
        }

        .points-details {
            flex: 1;
        }

        .points-type {
            font-weight: 600;
            font-size: 14px;
        }

        .points-earned {
            font-size: 12px;
            color: #6c757d;
        }

        .points-info {
            font-size: 13px;
            color: #6c757d;
        }

        /* نشان‌ها */
        .badges-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            height: 100%;
        }

        .badges-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            padding: 10px;
        }

        .badge-item {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #adb5bd;
            margin: 0 auto;
            position: relative;
            cursor: help;
            transition: transform 0.2s ease;
        }

        .badge-item:hover {
            transform: scale(1.05);
        }

        .badge-item.active {
            background-color: #f47e20;
            color: white;
            box-shadow: 0 3px 8px rgba(244, 126, 32, 0.3);
        }

        /* نمایش tooltip برای نشان‌ها */
        .badge-tooltip {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0,0,0,0.8);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            white-space: nowrap;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }

        .badge-item:hover .badge-tooltip {
            opacity: 1;
            visibility: visible;
        }

        .achievement-details h5 {
            margin: 0 0 5px;
            font-size: 15px;
            font-weight: 600;
        }

        .achievement-details p {
            margin: 0;
            font-size: 12px;
            color: #6c757d;
        }

        /* کارت کتاب‌ها */
        .book-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .book-cover {
            height: 180px;
            position: relative;
            overflow: hidden;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .reading-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background-color: rgba(0, 0, 0, 0.2);
        }

        .reading-progress .progress-bar {
            height: 100%;
            background-color: #27ae60;
        }

        .book-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 10px;
            background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .book-cover:hover .book-actions {
            opacity: 1;
        }

        .book-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4270e4;
        }

        .book-info {
            padding: 12px;
        }

        .book-title {
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .book-author {
            font-size: 13px;
            color: #6c757d;
            margin: 0;
        }

        /* بخش کتاب‌های خالی */
        .empty-books {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .empty-icon {
            font-size: 50px;
            color: #dee2e6;
        }

        /* بخش کتابخانه من و علاقه‌مندی‌ها */
        .my-library-section,
        .favorites-section {
            padding: 20px;
        }

        .section-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        /* استایل برای باکس اعلان تایید */
        .alert-verify {
            background-color: #e8f4fd;
            border-color: #b8daff;
        }

        .alert-verify .alert-icon {
            background-color: #4270e4;
        }

        .alert-verify .alert-content h5 {
            color: #004085;
        }

        .alert-verify .alert-content p {
            color: #004085;
        }

        .alert-verify .alert-close-btn {
            color: #004085;
        }

        /* استایل‌های مودال تایید */
        .verification-icon {
            width: 70px;
            height: 70px;
            background-color: #4270e4;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto;
        }

        .verification-title {
            font-size: 18px;
            font-weight: 600;
            margin-top: 15px;
            color: #2c3e50;
        }

        .verification-subtitle {
            color: #6c757d;
            font-size: 14px;
            margin-top: 5px;
        }

        /* استایل‌های فیلدهای کد تایید */
        .verification-code-container {
            margin-top: 20px;
        }

        .verification-code-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            direction: ltr;
        }

        .verification-code-inputs .code-input {
            width: 45px;
            height: 55px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            border: 1px solid #ced4da;
            border-radius: 8px;
            color: #2c3e50;
        }

        .verification-code-inputs .code-input:focus {
            border-color: #4270e4;
            box-shadow: 0 0 0 0.25rem rgba(66, 112, 228, 0.25);
        }

        .verification-code-timer {
            color: #6c757d;
            font-size: 14px;
            margin-top: 15px;
        }

        .verification-code-timer.text-danger {
            color: #dc3545 !important;
        }

        /* استایل دکمه ارسال مجدد کد */
        .btn-resend-code {
            color: #4270e4;
            font-size: 14px;
            padding: 0;
        }

        .btn-resend-code:hover {
            color: #3560d0;
            text-decoration: underline;
        }

        .btn-resend-code:disabled {
            color: #adb5bd;
            text-decoration: none;
            cursor: not-allowed;
        }

        /* انیمیشن لودینگ برای دکمه‌ها */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading:after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: calc(50% - 8px);
            left: calc(50% - 8px);
            border-radius: 50%;
            border: 2px solid #fff;
            border-top-color: transparent;
            animation: btn-spinner 0.6s linear infinite;
        }

        @keyframes btn-spinner {
            to {
                transform: rotate(360deg);
            }
        }

        /* پاسخگویی برای صفحات موبایل */
        @media (max-width: 991.98px) {
            .achievements-grid {
                grid-template-columns: 1fr;
            }

            .profile-card {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            .profile-container {
                padding-top: 15px;
            }

            .verification-code-inputs .code-input {
                width: 40px;
                height: 50px;
                font-size: 18px;
            }
        }
    </style>
@endpush
