@extends('layouts.app')

@section('title', 'ورود به حساب کاربری')

@push('head')
    <!-- بارگذاری اسکریپت reCAPTCHA v3 با کلید سایت جدید -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY', '6LfqibojAAAAACO7WykajmOnAjoYtXwfsKNtuHQA') }}"></script>
@endpush

@section('content')
    <div class="bl-login-page">
        <div class="bl-login-background">
            <div class="bl-login-shapes"></div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="bl-login-container">
                        <div class="bl-login-header">
                            <h1 class="bl-login-title">ورود به بَلیان</h1>
                            <p class="bl-login-subtitle">برای ادامه، شماره موبایل یا ایمیل خود را وارد کنید</p>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger bl-custom-alert">
                                <div class="bl-alert-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="bl-alert-content">
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success bl-custom-alert">
                                <div class="bl-alert-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="bl-alert-content">
                                    {{ session('success') }}
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.identify') }}" class="bl-login-form">
                            @csrf

                            <!-- فیلد مخفی reCAPTCHA -->
                            <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">

                            @if(request()->has('redirect'))
                                <input type="hidden" name="redirect_to" value="{{ route('order.book', $book->md5 ?? '') }}">
                            @endif

                            <div class="bl-login-method-toggle mb-4">
                                <div class="bl-toggle-options">
                                    <label class="bl-toggle-option active" data-target="phone-input-section">
                                        <input type="radio" name="login_method" value="phone" checked>
                                        <span class="bl-toggle-icon"><i class="fas fa-mobile-alt"></i></span>
                                        <span class="bl-toggle-text">شماره موبایل</span>
                                    </label>
                                    <label class="bl-toggle-option" data-target="email-input-section">
                                        <input type="radio" name="login_method" value="email">
                                        <span class="bl-toggle-icon"><i class="fas fa-envelope"></i></span>
                                        <span class="bl-toggle-text">ایمیل</span>
                                    </label>
                                </div>
                            </div>

                            <div id="phone-input-section" class="bl-input-section active">
                                <div class="bl-phone-input-container mb-4">
                                    <label for="phone" class="bl-form-label">شماره موبایل</label>
                                    <div class="bl-phone-input-wrapper">
                                        <input type="tel"
                                               class="form-control bl-phone-input @error('phone') is-invalid @enderror"
                                               id="phone"
                                               name="phone"
                                               value="{{ old('phone') }}"
                                               placeholder="09123456789"
                                               autocomplete="tel">
                                        <div class="bl-input-icon">
                                            <i class="fas fa-mobile-alt"></i>
                                        </div>
                                    </div>

                                    @error('phone')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                    <div class="bl-form-text">
                                        <i class="fas fa-info-circle"></i>
                                        در صفحه بعد می‌توانید با رمز عبور یا کد تأیید وارد شوید
                                    </div>
                                </div>
                            </div>

                            <div id="email-input-section" class="bl-input-section">
                                <div class="bl-email-input-container mb-4">
                                    <label for="email" class="bl-form-label">ایمیل</label>
                                    <div class="bl-email-input-wrapper">
                                        <input type="email"
                                               class="form-control bl-email-input @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email') }}"
                                               placeholder="example@email.com"
                                               autocomplete="email">
                                        <div class="bl-input-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                    </div>

                                    @error('email')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                    <div class="bl-form-text">
                                        <i class="fas fa-info-circle"></i>
                                        در صفحه بعد می‌توانید با رمز عبور یا کد تأیید وارد شوید
                                    </div>
                                </div>
                            </div>

                            @error('g-recaptcha-response')
                            <div class="alert alert-danger bl-custom-alert mb-3">
                                <div class="bl-alert-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="bl-alert-content">
                                    {{ $message }}
                                </div>
                            </div>
                            @enderror

                            <div class="bl-login-actions">
                                <button type="submit" class="btn btn-primary bl-btn-login">
                                    <span class="bl-btn-text">ادامه</span>
                                    <div class="bl-btn-icon-container">
                                        <div class="bl-btn-icon-circle">
                                            <i class="fas fa-arrow-left"></i>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </form>

                        <div class="bl-login-features">
                            <div class="bl-feature-item">
                                <div class="bl-feature-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="bl-feature-text">احراز هویت امن و سریع</div>
                            </div>
                            <div class="bl-feature-item">
                                <div class="bl-feature-icon">
                                    <i class="fas fa-book-reader"></i>
                                </div>
                                <div class="bl-feature-text">دسترسی به کتابخانه دیجیتال</div>
                            </div>
                        </div>

                        <div class="bl-login-footer">
                            <p class="bl-privacy-text">ورود شما به معنای پذیرش <a href="{{ route('terms') }}">قوانین و مقررات</a> بَلیان است</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* استایل‌های صفحه ورود - با پیشوند bl- برای جلوگیری از تداخل */
        .bl-login-page {
            --bl-primary-color: #4361ee;
            --bl-primary-hover: #3a56d4;
            --bl-primary-light: rgba(67, 97, 238, 0.1);
            --bl-secondary-color: #ff6b6b;
            --bl-success-color: #38b000;
            --bl-info-color: #4cc9f0;
            --bl-warning-color: #ffbe0b;
            --bl-danger-color: #ef233c;
            --bl-dark-color: #212529;
            --bl-light-color: #f8f9fa;
            --bl-border-radius: 12px;
            --bl-box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            --bl-transition: all 0.3s ease;

            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #eef1f5 100%);
        }

        .bl-login-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .bl-login-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .bl-login-shapes::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(67, 97, 238, 0.05) 0%, rgba(67, 97, 238, 0) 70%);
            border-radius: 50%;
        }

        .bl-login-shapes::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(255, 107, 107, 0.05) 0%, rgba(255, 107, 107, 0) 70%);
            border-radius: 50%;
        }

        .bl-login-container {
            position: relative;
            z-index: 2;
            background-color: white;
            border-radius: var(--bl-border-radius);
            overflow: hidden;
            box-shadow: var(--bl-box-shadow);
            padding: 40px;
        }

        /* هدر لاگین */
        .bl-login-header {
            text-align: center;
            margin-bottom: 30px;
            opacity: 0;
            animation: bl-fadeInUp 0.6s ease forwards 0.1s;
        }

        .bl-login-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--bl-dark-color);
            background: linear-gradient(to right, var(--bl-primary-color), var(--bl-primary-hover));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .bl-login-subtitle {
            color: #6c757d;
            font-size: 1rem;
        }

        /* استایل هشدار */
        .bl-custom-alert {
            display: flex;
            align-items: center;
            border-radius: var(--bl-border-radius);
            padding: 15px;
            margin-bottom: 25px;
            border: none;
            opacity: 0;
            animation: bl-fadeInUp 0.6s ease forwards 0.2s;
        }

        .bl-alert-icon {
            font-size: 1.5rem;
            margin-left: 15px;
        }

        .bl-alert-content {
            font-size: 0.95rem;
        }

        /* استایل فرم ورود */
        .bl-login-form {
            margin-bottom: 30px;
            opacity: 0;
            animation: bl-fadeInUp 0.6s ease forwards 0.3s;
        }

        /* استایل انتخاب روش ورود */
        .bl-login-method-toggle {
            margin-bottom: 20px;
        }

        .bl-toggle-options {
            display: flex;
            background-color: #f8f9fa;
            border-radius: var(--bl-border-radius);
            padding: 5px;
        }

        .bl-toggle-option {
            flex: 1;
            padding: 12px;
            text-align: center;
            border-radius: var(--bl-border-radius);
            cursor: pointer;
            transition: var(--bl-transition);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: #6c757d;
        }

        .bl-toggle-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .bl-toggle-option.active {
            background-color: white;
            color: var(--bl-primary-color);
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        .bl-toggle-icon {
            margin-left: 8px;
            font-size: 1.1rem;
        }

        .bl-toggle-text {
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* استایل بخش‌های ورودی */
        .bl-input-section {
            display: none;
        }

        .bl-input-section.active {
            display: block;
            animation: bl-fadeIn 0.3s ease forwards;
        }

        @keyframes bl-fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* استایل ورودی شماره موبایل و ایمیل */
        .bl-phone-input-container,
        .bl-email-input-container {
            position: relative;
        }

        .bl-form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--bl-dark-color);
            display: flex;
            align-items: center;
        }

        .bl-phone-input-wrapper,
        .bl-email-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .bl-phone-input,
        .bl-email-input {
            height: 55px;
            padding-right: 45px;
            padding-left: 15px;
            border-radius: var(--bl-border-radius);
            font-size: 1rem;
            border: 2px solid #e9ecef;
            background-color: #f8f9fa;
            transition: var(--bl-transition);
            width: 100%;
        }

        .bl-phone-input:focus,
        .bl-email-input:focus {
            border-color: var(--bl-primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
            background-color: white;
            outline: none;
        }

        .bl-input-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #adb5bd;
            transition: var(--bl-transition);
        }

        .bl-phone-input:focus ~ .bl-input-icon,
        .bl-email-input:focus ~ .bl-input-icon {
            color: var(--bl-primary-color);
        }

        .bl-form-text {
            margin-top: 8px;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .bl-form-text i {
            margin-left: 5px;
            color: var(--bl-info-color);
        }

        /* دکمه ورود */
        .bl-login-actions {
            margin-bottom: 30px;
        }

        .bl-btn-login {
            width: 100%;
            padding: 15px 25px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            background: linear-gradient(to right, var(--bl-primary-color), var(--bl-primary-hover));
            border: none;
            position: relative;
            overflow: hidden;
            transition: var(--bl-transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bl-btn-login::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            transform: rotate(45deg);
            animation: bl-shine 3s infinite;
            pointer-events: none;
        }

        @keyframes bl-shine {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }
            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }

        .bl-btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        }

        .bl-btn-text {
            margin-left: 10px;
        }

        .bl-btn-icon-container {
            position: relative;
            width: 24px;
            height: 24px;
        }

        .bl-btn-icon-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: var(--bl-transition);
        }

        .bl-btn-login:hover .bl-btn-icon-circle {
            transform: scale(1.2);
        }

        /* ویژگی‌های بَلیان */
        .bl-login-features {
            background-color: #f8f9fa;
            border-radius: var(--bl-border-radius);
            padding: 20px;
            margin-bottom: 20px;
            opacity: 0;
            animation: bl-fadeInUp 0.6s ease forwards 0.4s;
        }

        .bl-feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .bl-feature-item:last-child {
            margin-bottom: 0;
        }

        .bl-feature-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--bl-primary-light), rgba(67, 97, 238, 0.2));
            color: var(--bl-primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            flex-shrink: 0;
        }

        .bl-feature-text {
            font-size: 0.95rem;
            font-weight: 500;
            color: #495057;
        }

        /* فوتر فرم */
        .bl-login-footer {
            text-align: center;
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
            opacity: 0;
            animation: bl-fadeInUp 0.6s ease forwards 0.5s;
        }

        .bl-privacy-text {
            font-size: 0.8rem !important;
            color: #adb5bd !important;
            margin-bottom: 0;
        }

        .bl-login-footer a {
            color: var(--bl-primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .bl-login-footer a:hover {
            text-decoration: underline;
        }

        /* استایل‌های واکنش‌گرا */
        @media (max-width: 767.98px) {
            .bl-login-container {
                padding: 30px 20px;
            }

            .bl-login-title {
                font-size: 1.5rem;
            }

            .bl-phone-input,
            .bl-email-input {
                height: 50px;
            }
        }

        /* انیمیشن‌های ورودی */
        @keyframes bl-fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* استایل برای کپچای گوگل */
        .bl-recaptcha-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 20px;
            opacity: 0;
            animation: bl-fadeInUp 0.6s ease forwards 0.35s;
        }

        .g-recaptcha {
            transform-origin: center;
            transition: var(--bl-transition);
        }

        .g-recaptcha:hover {
            transform: scale(1.02);
        }

        @media (max-width: 400px) {
            .g-recaptcha {
                transform: scale(0.9);
                margin-bottom: -10px;
            }

            .g-recaptcha:hover {
                transform: scale(0.92);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing form...');

            // متغیرهای مربوط به فرم
            const toggleOptions = document.querySelectorAll('.bl-toggle-option');
            const inputSections = document.querySelectorAll('.bl-input-section');
            const phoneInput = document.getElementById('phone');
            const emailInput = document.getElementById('email');
            const loginForm = document.querySelector('.bl-login-form');
            const recaptchaResponse = document.getElementById('recaptchaResponse');

            // تابع ساده برای دریافت توکن reCAPTCHA - با کلید جدید
            function getRecaptchaToken(action) {
                console.log('Attempting to get reCAPTCHA token for action: ' + action);

                if (typeof grecaptcha !== 'undefined' && grecaptcha) {
                    grecaptcha.ready(function() {
                        try {
                            grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY', '6LfqibojAAAAACO7WykajmOnAjoYtXwfsKNtuHQA') }}', {action: action})
                                .then(function(token) {
                                    if (recaptchaResponse) {
                                        recaptchaResponse.value = token;
                                        console.log('reCAPTCHA token set (first 10 chars): ' + token.substring(0, 10) + '...');
                                    } else {
                                        console.error('recaptchaResponse element not found');
                                    }
                                })
                                .catch(function(error) {
                                    console.error('Error executing reCAPTCHA:', error);
                                });
                        } catch (error) {
                            console.error('Error in grecaptcha.execute:', error);
                        }
                    });
                } else {
                    console.error('grecaptcha not available');
                }
            }

            // تست توکن reCAPTCHA در بارگذاری صفحه
            setTimeout(function() {
                getRecaptchaToken('login_page_load');
            }, 1000);

            // تبدیل اعداد فارسی به انگلیسی
            const persianToEnglish = (str) => {
                const persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

                if (!str) return str;

                for (let i = 0; i < 10; i++) {
                    const regex = new RegExp(persianNumbers[i], 'g');
                    str = str.toString().replace(regex, englishNumbers[i]);
                }

                return str;
            };

            // پاکسازی شماره موبایل
            const normalizePhoneNumber = (phone) => {
                phone = persianToEnglish(phone);
                phone = phone.replace(/\D/g, '');

                if (phone.startsWith('98')) {
                    phone = phone.substring(2);
                }

                if (phone.length > 0 && !phone.startsWith('0')) {
                    phone = '0' + phone;
                }

                return phone;
            };

            // تغییر روش ورود
            toggleOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');

                    // فعال کردن گزینه انتخاب شده
                    toggleOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');

                    // نمایش بخش مربوطه
                    inputSections.forEach(section => section.classList.remove('active'));
                    document.getElementById(target).classList.add('active');

                    // تنظیم فوکوس
                    if (target === 'phone-input-section') {
                        phoneInput.focus();
                        phoneInput.setAttribute('required', 'required');
                        emailInput.removeAttribute('required');
                    } else {
                        emailInput.focus();
                        emailInput.setAttribute('required', 'required');
                        phoneInput.removeAttribute('required');
                    }
                });
            });

            // مدیریت فیلد شماره موبایل
            if (phoneInput) {
                // تنظیم مقدار اولیه
                if (phoneInput.value) {
                    phoneInput.value = normalizePhoneNumber(phoneInput.value);
                }

                // به روزرسانی مقدار هنگام تایپ
                phoneInput.addEventListener('input', function(e) {
                    let value = normalizePhoneNumber(this.value);

                    // محدود کردن به 11 رقم (با احتساب 0 اول)
                    if (value.length > 11) {
                        value = value.substring(0, 11);
                    }

                    this.value = value;
                    this.classList.remove('is-invalid');

                    // حذف پیام خطا
                    const feedback = document.querySelector('.bl-phone-input-container .invalid-feedback');
                    if (feedback) {
                        feedback.remove();
                    }
                });
            }

            // مدیریت فیلد ایمیل
            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    this.classList.remove('is-invalid');

                    // حذف پیام خطا
                    const feedback = document.querySelector('.bl-email-input-container .invalid-feedback');
                    if (feedback) {
                        feedback.remove();
                    }
                });
            }

            // اعتبارسنجی فرم هنگام ارسال
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // مانع از ارسال فرم می‌شویم تا اعتبارسنجی انجام شود
                    e.preventDefault();

                    console.log('Form submit event triggered');

                    // بررسی اعتبارسنجی فیلدها
                    const activeMethod = document.querySelector('.bl-toggle-option.active').getAttribute('data-target');
                    let isValid = true;

                    if (activeMethod === 'phone-input-section') {
                        // اعتبارسنجی شماره موبایل
                        const phoneValue = phoneInput.value.trim();
                        const phonePattern = /^09\d{9}$/;

                        if (!phonePattern.test(phoneValue)) {
                            isValid = false;
                            phoneInput.classList.add('is-invalid');

                            let feedback = document.querySelector('.bl-phone-input-container .invalid-feedback');
                            if (!feedback) {
                                feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback d-block';
                                phoneInput.parentNode.parentNode.appendChild(feedback);
                            }

                            feedback.textContent = 'لطفاً یک شماره موبایل معتبر وارد کنید (مثال: 09123456789)';
                        }
                    } else {
                        // اعتبارسنجی ایمیل
                        const emailValue = emailInput.value.trim();
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                        if (!emailPattern.test(emailValue)) {
                            isValid = false;
                            emailInput.classList.add('is-invalid');

                            let feedback = document.querySelector('.bl-email-input-container .invalid-feedback');
                            if (!feedback) {
                                feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback d-block';
                                emailInput.parentNode.parentNode.appendChild(feedback);
                            }

                            feedback.textContent = 'لطفاً یک آدرس ایمیل معتبر وارد کنید';
                        }
                    }

                    if (isValid) {
                        // نمایش حالت در حال پردازش
                        const submitButton = loginForm.querySelector('button[type="submit"]');
                        if (submitButton) {
                            submitButton.disabled = true;
                            const btnText = submitButton.querySelector('.bl-btn-text');
                            if (btnText) {
                                btnText.textContent = 'در حال پردازش...';
                            }
                        }

                        // تلاش برای گرفتن توکن reCAPTCHA
                        if (typeof grecaptcha !== 'undefined' && grecaptcha) {
                            try {
                                grecaptcha.ready(function() {
                                    grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY', '6LfqibojAAAAACO7WykajmOnAjoYtXwfsKNtuHQA') }}', {action: 'login_submit'})
                                        .then(function(token) {
                                            if (recaptchaResponse) {
                                                recaptchaResponse.value = token;
                                                console.log('Token set for submission (first 10 chars): ' + token.substring(0, 10) + '...');
                                            }
                                            // ارسال فرم بعد از دریافت توکن
                                            console.log('Submitting form after getting token');
                                            loginForm.submit();
                                        })
                                        .catch(function(error) {
                                            console.error('Error getting token:', error);
                                            // ارسال فرم حتی در صورت خطا
                                            console.log('Submitting form despite token error');
                                            loginForm.submit();
                                        });
                                });
                            } catch (error) {
                                console.error('Fatal error in reCAPTCHA execution:', error);
                                // ارسال فرم در صورت خطا
                                console.log('Submitting form despite fatal error');
                                loginForm.submit();
                            }
                        } else {
                            console.log('grecaptcha not available, submitting form directly');
                            loginForm.submit();
                        }

                        // زمان‌سنج امنیتی - اگر بعد از 3 ثانیه هنوز ارسال نشده، آن را ارسال کن
                        setTimeout(function() {
                            if (submitButton && submitButton.disabled) {
                                console.log('3-second timeout reached, forcing form submission');
                                loginForm.submit();
                            }
                        }, 3000);
                    }
                });
            }
        });
    </script>
@endpush
