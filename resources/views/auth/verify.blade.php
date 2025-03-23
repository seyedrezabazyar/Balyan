@extends('layouts.app')

@section('title', 'تأیید هویت')

@section('content')
    <div class="verify-page">
        <div class="verify-background">
            <div class="verify-shapes"></div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="verify-container">
                        <div class="verify-header">
                            <div class="verify-logo">
                                <img src="{{ asset('images/logo.png') }}" alt="کتاب‌یار" class="img-fluid">
                            </div>
                            <h1 class="verify-title">تأیید هویت</h1>

                            @if(session('error'))
                                <div class="alert alert-danger custom-alert">
                                    <div class="alert-icon">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="alert-content">
                                        {{ session('error') }}
                                    </div>
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert alert-success custom-alert">
                                    <div class="alert-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="alert-content">
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger custom-alert">
                                    <div class="alert-icon">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="alert-content">
                                        {{ $errors->first() }}
                                    </div>
                                </div>
                            @endif

                            <!-- نمایش کد در محیط توسعه -->
                            @if(isset($dev_code) && $dev_code)
                                <div class="alert alert-info custom-alert">
                                    <div class="alert-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="alert-content">
                                        <strong>کد تأیید برای محیط توسعه:</strong> {{ $dev_code }}
                                    </div>
                                </div>
                            @endif

                            <p class="verify-subtitle">
                                لطفاً هویت خود را برای
                                @if($identifierType === 'phone')
                                    شماره {{ $identifier }}
                                @else
                                    ایمیل {{ $identifier }}
                                @endif
                                تأیید کنید
                            </p>
                        </div>

                        <div class="verify-methods">
                            @if($userExists && $hasPassword)
                                <!-- کاربر موجود با رمز عبور -->
                                <div class="verify-method-tabs">
                                    <div class="nav nav-pills" id="verify-method-tab" role="tablist">
                                        <button class="nav-link active" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-content" type="button" role="tab" aria-controls="password-content" aria-selected="true">
                                            <i class="fas fa-key"></i>
                                            <span>رمز عبور</span>
                                        </button>
                                        <button class="nav-link" id="code-tab" data-bs-toggle="tab" data-bs-target="#code-content" type="button" role="tab" aria-controls="code-content" aria-selected="false">
                                            <i class="fas fa-sms"></i>
                                            <span>کد تأیید</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="tab-content" id="verify-method-content">
                                    <!-- تب رمز عبور -->
                                    <div class="tab-pane fade show active" id="password-content" role="tabpanel" aria-labelledby="password-tab">
                                        <form method="POST" action="{{ route('auth.login-password') }}" class="password-form">
                                            @csrf
                                            <input type="hidden" name="verify_method" value="password">

                                            <div class="form-group mb-4">
                                                <label for="password" class="form-label">رمز عبور</label>
                                                <div class="password-input-wrapper">
                                                    <input type="password"
                                                           class="form-control @error('password') is-invalid @enderror"
                                                           id="password"
                                                           name="password"
                                                           placeholder="رمز عبور خود را وارد کنید">
                                                    <div class="input-icon">
                                                        <i class="fas fa-lock"></i>
                                                    </div>
                                                    <button type="button" class="password-toggle" tabindex="-1">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                                @error('password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-check mb-4">
                                                <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me_password">
                                                <label class="form-check-label" for="remember_me_password">
                                                    مرا به خاطر بسپار
                                                </label>
                                            </div>

                                            <div class="verify-actions">
                                                <button type="submit" class="btn btn-primary btn-verify">
                                                    <span class="btn-text">ورود به حساب کاربری</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- تب کد تأیید -->
                                    <div class="tab-pane fade" id="code-content" role="tabpanel" aria-labelledby="code-tab">
                                        <!-- انتخاب روش ارسال کد -->
                                        @if(!empty($contactInfo) && ($contactInfo['has_phone'] && $contactInfo['has_email']))
                                            <div class="send-code-options mb-4">
                                                <p class="text-center mb-3">کد تأیید را به کدام روش دریافت می‌کنید؟</p>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <button type="button" class="btn btn-outline-primary btn-send-code w-100" data-type="phone" data-identifier="{{ $contactInfo['phone'] }}">
                                                            <i class="fas fa-mobile-alt"></i>
                                                            <span>شماره موبایل</span>
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button type="button" class="btn btn-outline-primary btn-send-code w-100" data-type="email" data-identifier="{{ $contactInfo['email'] }}">
                                                            <i class="fas fa-envelope"></i>
                                                            <span>ایمیل</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="send-code-container mb-4">
                                                <button type="button" class="btn btn-outline-primary btn-send-code w-100"
                                                        data-type="{{ $identifierType }}"
                                                        data-identifier="{{ $identifier }}">
                                                    <i class="fas fa-{{ $identifierType === 'phone' ? 'mobile-alt' : 'envelope' }}"></i>
                                                    <span>ارسال کد تأیید به {{ $identifierType === 'phone' ? 'شماره موبایل' : 'ایمیل' }}</span>
                                                </button>
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ route('auth.verify-code') }}" class="code-form">
                                            @csrf
                                            <input type="hidden" name="verify_method" value="code">
                                            <input type="hidden" name="verification_code" id="verification_code">

                                            <div class="verification-code-container mb-4">
                                                <label class="form-label">کد تأیید ارسال شده را وارد کنید</label>
                                                <div class="verification-code-inputs">
                                                    @for($i = 1; $i <= 6; $i++)
                                                        <input type="text"
                                                               class="form-control code-input"
                                                               maxlength="1"
                                                               data-index="{{ $i }}"
                                                            {{ $i == 1 ? 'autofocus' : '' }}>
                                                    @endfor
                                                </div>

                                                <div class="verification-code-timer mt-2">
                                                    <span id="countdown-timer">در حال بارگذاری...</span>
                                                </div>

                                                <div id="status-message" class="mt-2 text-sm"></div>

                                                <div class="resend-code-container mt-3 text-center{{ $codeExpired ? '' : ' d-none' }}" id="resend-container">
                                                    <button type="button" id="resend-code-btn" class="btn btn-link btn-resend-code"
                                                            data-type="{{ $identifierType }}"
                                                            data-identifier="{{ $identifier }}"
                                                        {{ $codeExpired ? '' : 'disabled' }}>
                                                        <i class="fas fa-redo-alt"></i>
                                                        <span>ارسال مجدد کد</span>
                                                    </button>
                                                </div>

                                                @error('verification_code')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-check mb-4">
                                                <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me_code">
                                                <label class="form-check-label" for="remember_me_code">
                                                    مرا به خاطر بسپار
                                                </label>
                                            </div>

                                            <div class="verify-actions">
                                                <button type="submit" class="btn btn-primary btn-verify">
                                                    <span class="btn-text">ورود به حساب کاربری</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <!-- کاربر جدید یا بدون رمز عبور - فقط کد تأیید -->
                                <form method="POST" action="{{ route('auth.verify-code') }}" class="code-form">
                                    @csrf
                                    <input type="hidden" name="verify_method" value="code">
                                    <input type="hidden" name="verification_code" id="verification_code">
                                    <input type="hidden" id="identifier" value="{{ $identifier }}">
                                    <input type="hidden" id="identifier_type" value="{{ $identifierType }}">
                                    <input type="hidden" id="expires-at" value="{{ $expiresAt ?? '' }}">
                                    <input type="hidden" id="code-expired" value="{{ $codeExpired ? 'true' : 'false' }}">

                                    <div class="verification-code-container mb-4">
                                        <label class="form-label">کد تأیید ارسال شده را وارد کنید</label>
                                        <div class="verification-code-inputs">
                                            @for($i = 1; $i <= 6; $i++)
                                                <input type="text"
                                                       class="form-control code-input"
                                                       maxlength="1"
                                                       data-index="{{ $i }}"
                                                    {{ $i == 1 ? 'autofocus' : '' }}>
                                            @endfor
                                        </div>

                                        <div class="verification-code-timer mt-2">
                                            <span id="countdown-timer">در حال بارگذاری...</span>
                                        </div>

                                        <div id="status-message" class="mt-2 text-sm {{ $codeExpired ? 'text-danger' : '' }}">
                                            @if($codeExpired)
                                                کد تأیید منقضی شده است. لطفاً کد جدید درخواست کنید.
                                            @endif
                                        </div>

                                        <div class="resend-code-container mt-3 text-center{{ $codeExpired ? '' : ' d-none' }}" id="resend-container">
                                            <button type="button" id="resend-code-btn" class="btn btn-link btn-resend-code"
                                                    data-type="{{ $identifierType }}"
                                                    data-identifier="{{ $identifier }}"
                                                {{ $codeExpired ? '' : 'disabled' }}>
                                                <i class="fas fa-redo-alt"></i>
                                                <span>ارسال مجدد کد</span>
                                            </button>
                                        </div>

                                        @error('verification_code')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                                        <label class="form-check-label" for="remember_me">
                                            مرا به خاطر بسپار
                                        </label>
                                    </div>

                                    <div class="verify-actions">
                                        <button type="submit" class="btn btn-primary btn-verify">
                                            <span class="btn-text">ورود به حساب کاربری</span>
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>

                        <div class="verify-footer">
                            <p class="privacy-text">ورود شما به معنای پذیرش <a href="{{ route('terms') }}">قوانین و مقررات</a> کتاب‌یار است</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-hover: #3a56d4;
            --primary-light: rgba(67, 97, 238, 0.1);
            --secondary-color: #ff6b6b;
            --success-color: #38b000;
            --info-color: #4cc9f0;
            --warning-color: #ffbe0b;
            --danger-color: #ef233c;
            --dark-color: #212529;
            --light-color: #f8f9fa;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        /* استایل‌های صفحه تأیید */
        .verify-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #eef1f5 100%);
        }

        .verify-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .verify-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .verify-shapes::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(67, 97, 238, 0.05) 0%, rgba(67, 97, 238, 0) 70%);
            border-radius: 50%;
        }

        .verify-shapes::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(255, 107, 107, 0.05) 0%, rgba(255, 107, 107, 0) 70%);
            border-radius: 50%;
        }

        .verify-container {
            position: relative;
            z-index: 2;
            background-color: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            padding: 40px;
        }

        /* هدر تأیید */
        .verify-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .verify-logo {
            max-width: 120px;
            margin: 0 auto 20px;
        }

        .verify-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark-color);
            background: linear-gradient(to right, var(--primary-color), var(--primary-hover));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .verify-subtitle {
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        /* استایل هشدار */
        .custom-alert {
            display: flex;
            align-items: center;
            border-radius: var(--border-radius);
            padding: 15px;
            margin-bottom: 25px;
            border: none;
        }

        .alert-icon {
            font-size: 1.5rem;
            margin-left: 15px;
        }

        .alert-content {
            font-size: 0.95rem;
        }

        /* استایل تب‌های روش تأیید */
        .verify-method-tabs {
            margin-bottom: 25px;
        }

        .nav-pills {
            display: flex;
            background-color: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 5px;
            margin-bottom: 20px;
        }

        .nav-pills .nav-link {
            flex: 1;
            text-align: center;
            border-radius: var(--border-radius);
            padding: 12px;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .nav-pills .nav-link i {
            margin-left: 8px;
            font-size: 1.1rem;
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.2);
        }

        /* استایل فرم رمز عبور */
        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-input-wrapper .form-control {
            height: 55px;
            padding-right: 45px;
            padding-left: 45px;
            border-radius: var(--border-radius);
            font-size: 1rem;
            border: 2px solid #e9ecef;
            background-color: #f8f9fa;
            transition: var(--transition);
        }

        .password-input-wrapper .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
            background-color: white;
        }

        .password-input-wrapper .input-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #adb5bd;
            transition: var(--transition);
        }

        .password-input-wrapper .form-control:focus ~ .input-icon {
            color: var(--primary-color);
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #adb5bd;
            cursor: pointer;
            transition: var(--transition);
            padding: 0;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        /* استایل کد تأیید */
        .verification-code-container {
            text-align: center;
        }

        .verification-code-inputs {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            direction: ltr;
        }

        .verification-code-inputs .code-input {
            width: 50px;
            height: 60px;
            font-size: 1.5rem;
            text-align: center;
            border: 2px solid #e9ecef;
            border-radius: var(--border-radius);
            background-color: #f8f9fa;
            transition: var(--transition);
            margin: 0 4px;
        }

        .verification-code-inputs .code-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
            background-color: white;
        }

        .verification-code-timer {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .verification-code-timer #countdown-timer {
            font-weight: 600;
            color: var(--primary-color);
        }

        /* استایل دکمه ارسال کد */
        .btn-send-code {
            padding: 12px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .btn-send-code i {
            margin-left: 8px;
        }

        .btn-send-code:hover {
            background-color: var(--primary-light);
            border-color: var(--primary-color);
        }

        /* استایل دکمه ارسال مجدد کد */
        .btn-resend-code {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0;
        }

        .btn-resend-code i {
            margin-left: 5px;
        }

        .btn-resend-code:hover {
            color: var(--primary-hover);
        }

        .btn-resend-code.disabled {
            color: #adb5bd;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* دکمه تأیید */
        .verify-actions {
            margin-bottom: 30px;
        }

        .btn-verify {
            width: 100%;
            padding: 15px 25px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            background: linear-gradient(to right, var(--primary-color), var(--primary-hover));
            border: none;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .btn-verify::before {
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

        @keyframes shine {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }
            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }

        .btn-verify:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        }

        /* فوتر فرم */
        .verify-footer {
            text-align: center;
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
        }

        .privacy-text {
            font-size: 0.8rem !important;
            color: #adb5bd !important;
            margin-bottom: 0;
        }

        .verify-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .verify-footer a:hover {
            text-decoration: underline;
        }

        /* استایل‌های واکنش‌گرا */
        @media (max-width: 767.98px) {
            .verify-container {
                padding: 30px 20px;
            }

            .verify-title {
                font-size: 1.5rem;
            }

            .verification-code-inputs .code-input {
                width: 40px;
                height: 50px;
                font-size: 1.2rem;
                margin: 0 2px;
            }
        }

        /* انیمیشن‌های ورودی */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .verify-header,
        .custom-alert,
        .verify-methods,
        .verify-footer {
            animation: fadeInUp 0.6s ease forwards;
        }

        .verify-header { animation-delay: 0.1s; }
        .custom-alert { animation-delay: 0.2s; }
        .verify-methods { animation-delay: 0.3s; }
        .verify-footer { animation-delay: 0.4s; }

        /* استایل برای پیام وضعیت */
        #status-message {
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .text-danger {
            color: var(--danger-color);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Script initialized'); // برای اشکال‌زدایی

            // مدیریت فیلدهای کد تأیید
            const codeInputs = document.querySelectorAll('.code-input');
            const hiddenInput = document.getElementById('verification_code');
            const expiresAtElement = document.getElementById('expires-at');
            const codeExpiredElement = document.getElementById('code-expired');
            const countdownElement = document.getElementById('countdown-timer');
            const resendContainer = document.getElementById('resend-container');
            const resendButton = document.getElementById('resend-code-btn');
            const statusMessage = document.getElementById('status-message');

            // بررسی وجود المان‌های مورد نیاز
            console.log('Required elements found:', {
                codeInputs: codeInputs.length,
                hiddenInput: !!hiddenInput,
                expiresAtElement: !!expiresAtElement,
                codeExpiredElement: !!codeExpiredElement,
                countdownElement: !!countdownElement,
                resendContainer: !!resendContainer,
                resendButton: !!resendButton,
                statusMessage: !!statusMessage
            });

            // بررسی تنظیمات CSRF
            const metaCsrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!metaCsrfToken) {
                console.error('CSRF token meta tag not found!');
            } else {
                console.log('CSRF token meta tag found');
            }

            let timerInterval;

            /**
             * تایمر کد تأیید
             */
            class VerificationTimer {
                constructor(expiresAt) {
                    this.expiresAt = expiresAt; // زمان انقضا به میلی‌ثانیه (timestamp)
                    this.interval = null;

                    // اگر زمان انقضا تعیین نشده یا منقضی شده، دکمه ارسال مجدد را فعال کنیم
                    if (!this.expiresAt || this.expiresAt <= new Date().getTime()) {
                        this.showExpiredMessage();
                        this.enableResendButton();
                        return;
                    }

                    this.updateCountdown();
                    this.startTimer();
                }

                startTimer() {
                    this.interval = setInterval(() => {
                        this.updateCountdown();
                    }, 1000);
                }

                updateCountdown() {
                    const now = new Date().getTime();
                    const timeLeft = this.expiresAt - now;

                    if (timeLeft <= 0) {
                        clearInterval(this.interval);
                        this.showExpiredMessage();
                        this.enableResendButton();
                        return;
                    }

                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} مانده تا انقضای کد`;
                    this.disableResendButton();
                }

                showExpiredMessage() {
                    countdownElement.textContent = '00:00 مانده تا انقضای کد';
                    if (statusMessage) {
                        statusMessage.textContent = 'کد تأیید منقضی شده است. لطفاً کد جدید درخواست کنید.';
                        statusMessage.className = 'mt-2 text-sm text-danger';
                    }
                }

                enableResendButton() {
                    if (resendContainer) {
                        resendContainer.classList.remove('d-none');
                    }
                    if (resendButton) {
                        resendButton.disabled = false;
                        resendButton.classList.remove('disabled');
                    }
                }

                disableResendButton() {
                    if (resendContainer) {
                        resendContainer.classList.add('d-none');
                    }
                    if (resendButton) {
                        resendButton.disabled = true;
                        resendButton.classList.add('disabled');
                    }
                }

                resetTimer(newExpiresAt) {
                    clearInterval(this.interval);
                    this.expiresAt = newExpiresAt;
                    this.updateCountdown();
                    this.startTimer();

                    // پاک کردن پیام خطا
                    if (statusMessage) {
                        statusMessage.textContent = '';
                        statusMessage.className = 'mt-2 text-sm';
                    }
                }
            }

            // به‌روزرسانی فیلد مخفی با کد تأیید
            function updateVerificationCode() {
                let code = '';
                codeInputs.forEach(input => {
                    code += input.value;
                });

                if (hiddenInput) {
                    hiddenInput.value = code;
                }
            }

            // مدیریت فیلدهای کد تأیید
            if (codeInputs.length) {
                codeInputs.forEach((input, index) => {
                    // هنگام کلیک، کل متن انتخاب شود
                    input.addEventListener('click', function() {
                        this.select();
                    });

                    // هنگام تایپ، به فیلد بعدی برود
                    input.addEventListener('input', function(e) {
                        const value = this.value;

                        // فقط اعداد مجاز هستند
                        if (!/^\d*$/.test(value)) {
                            this.value = '';
                            return;
                        }

                        // اگر عددی وارد شد، به فیلد بعدی برود
                        if (value.length === 1 && index < codeInputs.length - 1) {
                            codeInputs[index + 1].focus();
                            codeInputs[index + 1].select();
                        }

                        // به‌روزرسانی کد تأیید
                        updateVerificationCode();
                    });

                    // مدیریت کلیدهای جهت‌دار و حذف
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
                });
            }

            // مدیریت نمایش/عدم نمایش رمز عبور
            const passwordToggles = document.querySelectorAll('.password-toggle');
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const passwordInput = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

            // مدیریت ارسال کد تأیید
            const sendCodeButtons = document.querySelectorAll('.btn-send-code');
            sendCodeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const type = this.getAttribute('data-type');
                    const identifier = this.getAttribute('data-identifier');
                    const originalText = this.innerHTML;

                    console.log('Send code button clicked', { type, identifier });

                    // غیرفعال کردن دکمه
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>در حال ارسال...</span>';

                    // گرفتن CSRF توکن
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // ارسال درخواست به سرور
                    fetch('/auth/send-verification-code', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            identifier_type: type,
                            identifier: identifier
                        })
                    })
                        .then(response => {
                            console.log('Response status:', response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log('Response data:', data);

                            if (data.success) {
                                // نمایش پیام موفقیت
                                const alertHTML = `
                            <div class="alert alert-success custom-alert mb-4">
                                <div class="alert-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="alert-content">
                                    ${data.message}
                                </div>
                            </div>
                            `;

                                // اضافه کردن پیام به بالای فرم
                                const form = document.querySelector('.code-form');
                                form.insertAdjacentHTML('beforebegin', alertHTML);

                                // در محیط توسعه، کد را نمایش دهید
                                if (data.dev_code) {
                                    const devCodeHTML = `
                                <div class="alert alert-info custom-alert mb-4">
                                    <div class="alert-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="alert-content">
                                        <strong>کد تأیید برای محیط توسعه:</strong> ${data.dev_code}
                                    </div>
                                </div>
                                `;
                                    form.insertAdjacentHTML('beforebegin', devCodeHTML);
                                }

                                // بروزرسانی زمان انقضا
                                if (expiresAtElement) {
                                    expiresAtElement.value = data.expires_at;
                                }

                                // شروع مجدد تایمر
                                if (window.verificationTimer) {
                                    window.verificationTimer.resetTimer(data.expires_at);
                                } else {
                                    window.verificationTimer = new VerificationTimer(data.expires_at);
                                }

                                // فوکوس روی اولین فیلد کد
                                if (codeInputs.length) {
                                    codeInputs[0].focus();
                                }
                            } else {
                                // نمایش پیام خطا
                                const alertHTML = `
                            <div class="alert alert-danger custom-alert mb-4">
                                <div class="alert-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="alert-content">
                                    ${data.message}
                                </div>
                            </div>
                            `;

                                // اضافه کردن پیام به بالای فرم
                                const form = document.querySelector('.code-form');
                                form.insertAdjacentHTML('beforebegin', alertHTML);

                                // اگر محدودیت زمانی وجود دارد
                                if (data.wait_seconds) {
                                    let waitTimer = data.wait_seconds;
                                    const waitInterval = setInterval(() => {
                                        waitTimer--;
                                        button.innerHTML = `<i class="fas fa-clock"></i> <span>${waitTimer} ثانیه تا ارسال مجدد</span>`;

                                        if (waitTimer <= 0) {
                                            clearInterval(waitInterval);
                                            button.disabled = false;
                                            button.innerHTML = originalText;
                                        }
                                    }, 1000);
                                } else {
                                    // فعال کردن مجدد دکمه
                                    setTimeout(() => {
                                        button.disabled = false;
                                        button.innerHTML = originalText;
                                    }, 3000);
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);

                            // نمایش پیام خطا
                            const alertHTML = `
                        <div class="alert alert-danger custom-alert mb-4">
                            <div class="alert-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="alert-content">
                                خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.
                            </div>
                        </div>
                        `;

                            // اضافه کردن پیام به بالای فرم
                            const form = document.querySelector('.code-form');
                            form.insertAdjacentHTML('beforebegin', alertHTML);

                            // فعال کردن مجدد دکمه
                            button.disabled = false;
                            button.innerHTML = originalText;
                        });
                });
            });

            // مدیریت ارسال مجدد کد
            if (resendButton) {
                console.log('Setting up resend button click handler');

                // تست تشخیص کلیک
                resendButton.setAttribute('onclick', "console.log('Inline click handler called')");

                resendButton.addEventListener('click', function(e) {
                    e.preventDefault(); // جلوگیری از اقدامات پیش‌فرض

                    console.log('Resend button clicked');

                    const type = this.getAttribute('data-type') || document.getElementById('identifier_type').value;
                    const identifier = this.getAttribute('data-identifier') || document.getElementById('identifier').value;

                    console.log('Resend code data:', { type, identifier });

                    // غیرفعال کردن دکمه
                    this.disabled = true;
                    this.classList.add('disabled');
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>در حال ارسال...</span>';

                    // گرفتن CSRF توکن به صورت مستقیم
                    const csrf = document.querySelector('meta[name="csrf-token"]');
                    let csrfToken = '';

                    if (csrf) {
                        csrfToken = csrf.getAttribute('content');
                        console.log('CSRF token found:', csrfToken ? 'Yes (length: ' + csrfToken.length + ')' : 'No');
                    } else {
                        console.error('CSRF token meta tag not found!');
                        // نمایش پیام خطا
                        const alertHTML = `
                        <div class="alert alert-danger custom-alert mb-4">
                            <div class="alert-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="alert-content">
                                خطا: تگ CSRF پیدا نشد. لطفاً صفحه را بازنشانی کنید.
                            </div>
                        </div>
                        `;
                        // اضافه کردن پیام به بالای فرم
                        const form = document.querySelector('.code-form');
                        form.insertAdjacentHTML('beforebegin', alertHTML);

                        // بازگرداندن دکمه به حالت اولیه
                        this.disabled = false;
                        this.classList.remove('disabled');
                        this.innerHTML = '<i class="fas fa-redo-alt"></i> <span>ارسال مجدد کد</span>';
                        return;
                    }

                    // ارسال درخواست به سرور
                    const url = '/auth/send-verification-code';
                    console.log('Sending request to:', url);

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            identifier_type: type,
                            identifier: identifier
                        })
                    })
                        .then(response => {
                            console.log('Response received:', response.status, response.statusText);
                            if (!response.ok) {
                                throw new Error('Server returned ' + response.status + ': ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Response data:', data);

                            if (data.success) {
                                // نمایش پیام موفقیت
                                const alertHTML = `
                            <div class="alert alert-success custom-alert mb-4">
                                <div class="alert-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="alert-content">
                                    ${data.message}
                                </div>
                            </div>
                            `;

                                // حذف پیام‌های قبلی
                                document.querySelectorAll('.custom-alert').forEach(alert => {
                                    alert.remove();
                                });

                                // اضافه کردن پیام به بالای فرم
                                const form = document.querySelector('.code-form');
                                form.insertAdjacentHTML('beforebegin', alertHTML);

                                // در محیط توسعه، کد را نمایش دهید
                                if (data.dev_code) {
                                    const devCodeHTML = `
                                <div class="alert alert-info custom-alert mb-4">
                                    <div class="alert-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="alert-content">
                                        <strong>کد تأیید برای محیط توسعه:</strong> ${data.dev_code}
                                    </div>
                                </div>
                                `;
                                    form.insertAdjacentHTML('beforebegin', devCodeHTML);
                                }

                                // بروزرسانی زمان انقضا
                                if (expiresAtElement) {
                                    expiresAtElement.value = data.expires_at;
                                }

                                // بروزرسانی وضعیت انقضا
                                if (codeExpiredElement) {
                                    codeExpiredElement.value = 'false';
                                }

                                // پاک کردن پیام وضعیت
                                if (statusMessage) {
                                    statusMessage.textContent = '';
                                    statusMessage.className = 'mt-2 text-sm';
                                }

                                // شروع مجدد تایمر
                                if (window.verificationTimer) {
                                    window.verificationTimer.resetTimer(data.expires_at);
                                } else {
                                    window.verificationTimer = new VerificationTimer(data.expires_at);
                                }

                                // پنهان کردن دکمه ارسال مجدد
                                resendContainer.classList.add('d-none');

                                // فوکوس روی اولین فیلد کد
                                if (codeInputs.length) {
                                    codeInputs[0].focus();
                                }

                                // پاک کردن فیلدهای کد
                                codeInputs.forEach(input => {
                                    input.value = '';
                                });
                                updateVerificationCode();

                            } else {
                                // نمایش پیام خطا
                                const alertHTML = `
                            <div class="alert alert-danger custom-alert mb-4">
                                <div class="alert-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="alert-content">
                                    ${data.message}
                                </div>
                            </div>
                            `;

                                // حذف پیام‌های قبلی
                                document.querySelectorAll('.custom-alert').forEach(alert => {
                                    alert.remove();
                                });

                                // اضافه کردن پیام به بالای فرم
                                const form = document.querySelector('.code-form');
                                form.insertAdjacentHTML('beforebegin', alertHTML);

                                // اگر محدودیت زمانی وجود دارد
                                if (data.wait_seconds) {
                                    let waitTimer = data.wait_seconds;
                                    const waitInterval = setInterval(() => {
                                        waitTimer--;
                                        this.innerHTML = `<i class="fas fa-clock"></i> <span>${waitTimer} ثانیه تا ارسال مجدد</span>`;

                                        if (waitTimer <= 0) {
                                            clearInterval(waitInterval);
                                            this.disabled = false;
                                            this.classList.remove('disabled');
                                            this.innerHTML = '<i class="fas fa-redo-alt"></i> <span>ارسال مجدد کد</span>';
                                        }
                                    }, 1000);
                                } else {
                                    // بازگرداندن دکمه به حالت اولیه
                                    this.disabled = false;
                                    this.classList.remove('disabled');
                                    this.innerHTML = '<i class="fas fa-redo-alt"></i> <span>ارسال مجدد کد</span>';
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            // نمایش پیام خطا
                            const alertHTML = `
                        <div class="alert alert-danger custom-alert mb-4">
                            <div class="alert-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="alert-content">
                                خطا در ارسال کد تأیید: ${error.message}
                            </div>
                        </div>
                        `;

                            // حذف پیام‌های قبلی
                            document.querySelectorAll('.custom-alert').forEach(alert => {
                                alert.remove();
                            });

                            // اضافه کردن پیام به بالای فرم
                            const form = document.querySelector('.code-form');
                            form.insertAdjacentHTML('beforebegin', alertHTML);

                            // بازگرداندن دکمه به حالت اولیه
                            this.disabled = false;
                            this.classList.remove('disabled');
                            this.innerHTML = '<i class="fas fa-redo-alt"></i> <span>ارسال مجدد کد</span>';
                        });
                });

                // اضافه کردن گوش‌دهنده به صورت مستقیم به دام برای مطمئن شدن از فراخوانی
                const btnEl = document.getElementById('resend-code-btn');
                if (btnEl) {
                    console.log('Adding direct click handler');
                    btnEl.onclick = function() {
                        console.log('Direct click handler called');
                    };
                }
            } else {
                console.error('Resend button not found!');
            }

            // اعتبارسنجی فرم‌ها قبل از ارسال
            const passwordForm = document.querySelector('.password-form');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    const passwordInput = document.getElementById('password');
                    if (!passwordInput.value.trim()) {
                        e.preventDefault();
                        passwordInput.classList.add('is-invalid');

                        // نمایش پیام خطا
                        let feedback = passwordInput.parentElement.nextElementSibling;
                        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                            feedback = document.createElement('div');
                            feedback.className = 'invalid-feedback d-block';
                            passwordInput.parentElement.after(feedback);
                        }

                        feedback.textContent = 'لطفاً رمز عبور خود را وارد کنید.';
                    }
                });
            }

            const codeForm = document.querySelector('.code-form');
            if (codeForm) {
                codeForm.addEventListener('submit', function(e) {
                    const verificationCode = document.getElementById('verification_code');
                    if (!verificationCode.value || verificationCode.value.length !== 6) {
                        e.preventDefault();

                        // نمایش پیام خطا
                        const alertHTML = `
                            <div class="alert alert-danger custom-alert mb-4">
                                <div class="alert-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="alert-content">
                                    لطفاً کد تأیید 6 رقمی را وارد کنید.
                                </div>
                            </div>
                        `;

                        // حذف پیام‌های قبلی
                        document.querySelectorAll('.custom-alert').forEach(alert => {
                            alert.remove();
                        });

                        // اضافه کردن پیام به بالای فرم
                        codeForm.insertAdjacentHTML('beforebegin', alertHTML);

                        // فوکوس روی اولین فیلد خالی
                        let firstEmptyInput = null;
                        for (let i = 0; i < codeInputs.length; i++) {
                            if (!codeInputs[i].value) {
                                firstEmptyInput = codeInputs[i];
                                break;
                            }
                        }

                        if (firstEmptyInput) {
                            firstEmptyInput.focus();
                        } else {
                            codeInputs[0].focus();
                        }
                    }
                });
            }

            // راه‌اندازی تایمر در زمان بارگذاری صفحه
            if (expiresAtElement && expiresAtElement.value) {
                const expiresAt = parseInt(expiresAtElement.value);
                if (expiresAt) {
                    window.verificationTimer = new VerificationTimer(expiresAt);
                }
            } else if (codeExpiredElement && codeExpiredElement.value === 'true') {
                // اگر کد منقضی شده است
                countdownElement.textContent = '00:00 مانده تا انقضای کد';
                if (statusMessage) {
                    statusMessage.textContent = 'کد تأیید منقضی شده است. لطفاً کد جدید درخواست کنید.';
                    statusMessage.className = 'mt-2 text-sm text-danger';
                }

                if (resendContainer) {
                    resendContainer.classList.remove('d-none');
                }
                if (resendButton) {
                    resendButton.disabled = false;
                    resendButton.classList.remove('disabled');
                }
            } else {
                // اگر هیچ کدی ارسال نشده است
                countdownElement.textContent = 'کد تأییدی ارسال نشده است';
            }

            // اگر کد تأیید قبلاً ارسال شده، فوکوس روی اولین فیلد
            if ((expiresAtElement && expiresAtElement.value) || (codeExpiredElement && codeExpiredElement.value === 'true')) {
                if (codeInputs.length) {
                    codeInputs[0].focus();
                }
            }

            // بررسی نهایی - تزریق یک دکمه تست برای مطمئن شدن از عملکرد JavaScript
            console.log('Injecting test button');
            const testBtn = document.createElement('button');
            testBtn.type = 'button';
            testBtn.className = 'btn btn-sm btn-warning mt-2 d-none'; // d-none برای مخفی کردن در محیط تولید
            testBtn.textContent = 'دکمه تست';
            testBtn.onclick = function() {
                alert('JavaScript کار می‌کند!');
            };

            if (resendContainer) {
                resendContainer.appendChild(testBtn);
            }

            console.log('Script initialization complete');
        });
    </script>
@endpush
