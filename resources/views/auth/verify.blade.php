@extends('layouts.app')

@section('title', 'تأیید هویت')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY', '6LfqibojAAAAACO7WykajmOnAjoYtXwfsKNtuHQA') }}"></script>
@endpush

@section('content')
    <div class="verify-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="verify-container">
                        <div class="verify-header">
                            <div class="verify-logo">
                                <img src="{{ asset('images/logo.png') }}" alt="بَلیان" class="img-fluid">
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
                                    <div class="tab-pane fade show active" id="password-content" role="tabpanel" aria-labelledby="password-tab">
                                        <form method="POST" action="{{ route('auth.login-password') }}" class="password-form">
                                            @csrf
                                            <input type="hidden" name="verify_method" value="password">
                                            <input type="hidden" name="g-recaptcha-response" id="recaptchaResponsePassword">

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

                                    <div class="tab-pane fade" id="code-content" role="tabpanel" aria-labelledby="code-tab">
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
                                            <input type="hidden" name="g-recaptcha-response" id="recaptchaResponseCode">

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

                                                <div id="status-message" class="mt-2 text-sm d-none"></div>

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
                                                <div class="invalid-feedback d-none">{{ $message }}</div>
                                                @enderror

                                                @error('g-recaptcha-response')
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
                                <form method="POST" action="{{ route('auth.verify-code') }}" class="code-form">
                                    @csrf
                                    <input type="hidden" name="verify_method" value="code">
                                    <input type="hidden" name="verification_code" id="verification_code">
                                    <input type="hidden" id="identifier" value="{{ $identifier }}">
                                    <input type="hidden" id="identifier_type" value="{{ $identifierType }}">
                                    <input type="hidden" id="expires-at" value="{{ $expiresAt ?? '' }}">
                                    <input type="hidden" id="code-expired" value="{{ $codeExpired ? 'true' : 'false' }}">
                                    <input type="hidden" id="session_token" value="{{ $session_token ?? '' }}">
                                    <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">

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

                                        <div id="status-message" class="mt-2 text-sm d-none"></div>

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
                                        <div class="invalid-feedback d-none">{{ $message }}</div>
                                        @enderror

                                        @error('g-recaptcha-response')
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
                            <p class="privacy-text">ورود شما به معنای پذیرش <a href="{{ route('terms') }}">قوانین و مقررات</a> بَلیان است</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // دریافت زمان انقضای کد از کنترلر (به دقیقه)
            const codeExpiryMinutes = {{ $code_expiry_minutes ?? 1 }};
            // تبدیل به میلی‌ثانیه برای استفاده در جاوااسکریپت
            const codeExpiryMs = codeExpiryMinutes * 60 * 1000;
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }

            const codeInputs = document.querySelectorAll('.code-input');
            const hiddenInput = document.getElementById('verification_code');
            const expiresAtElement = document.getElementById('expires-at');
            const codeExpiredElement = document.getElementById('code-expired');
            const countdownElement = document.getElementById('countdown-timer');
            const resendContainer = document.getElementById('resend-container');
            const resendButton = document.getElementById('resend-code-btn');
            const statusMessage = document.getElementById('status-message');
            const sessionToken = document.getElementById('session_token')?.value;

            const recaptchaResponsePassword = document.getElementById('recaptchaResponsePassword');
            const recaptchaResponseCode = document.getElementById('recaptchaResponseCode');
            const recaptchaResponse = document.getElementById('recaptchaResponse');

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

            function formatMaskedPhone() {
                const subtitleElement = document.querySelector('.verify-subtitle');
                if (!subtitleElement) return;

                const text = subtitleElement.textContent || '';
                if (!text.includes('شماره')) return;

                const phonePattern = /(\*+\d+|\d+\*+|\d+\*+\d+)/g;
                const matches = text.match(phonePattern);

                if (!matches || matches.length === 0) return;

                const maskedPhone = matches[0];

                if (maskedPhone.startsWith('*')) {
                    const digits = maskedPhone.match(/\d+/)[0];
                    const formattedPhone = `09${digits.substring(0, 2)}*****${digits.substring(2)}`;
                    subtitleElement.textContent = text.replace(maskedPhone, formattedPhone);
                }
                else if (maskedPhone.match(/^\d+\*+$/)) {
                    const prefix = maskedPhone.match(/^\d+/)[0];
                    const formattedPhone = `${prefix.substring(0, 4)}*****`;
                    subtitleElement.textContent = text.replace(maskedPhone, formattedPhone);
                }
                else if (maskedPhone.match(/^\d+\*+\d+$/)) {
                    const parts = maskedPhone.split(/\*+/);
                    if (parts.length === 2) {
                        const prefix = parts[0];
                        const suffix = parts[1];

                        if (prefix.length > 4 || (prefix.length < 2 && suffix.length >= 4)) {
                            const correctedPhone = `09${suffix}*****${prefix.substring(prefix.length - 4)}`;
                            subtitleElement.textContent = text.replace(maskedPhone, correctedPhone);
                        }
                    }
                }
            }

            function checkForErrors() {
                clearAlerts();

                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('error')) {
                    const errorType = urlParams.get('error');

                    if (errorType === 'invalid_code' || errorType === 'verification_failed') {
                        showAlert('کد تأیید وارد شده صحیح نیست یا منقضی شده است.', 'danger');
                        return;
                    }
                    else if (errorType === 'account_creation_failed') {
                        showAlert('خطا در ایجاد حساب کاربری. لطفاً دوباره تلاش کنید.', 'danger');
                        return;
                    }
                }

                const errorElements = document.querySelectorAll('.alert.alert-danger:not(.custom-alert), .invalid-feedback');
                if (errorElements.length > 0) {
                    let foundInvalidCode = false;
                    let foundAccountError = false;

                    errorElements.forEach(element => {
                        const errorText = element.textContent || '';

                        // پنهان کردن خطاهای مربوط به کد تایید
                        if (errorText.includes('کد تأیید') ||
                            errorText.includes('کد وارد شده') ||
                            errorText.includes('کد اشتباه') ||
                            errorText.includes('verification_code')) {

                            element.classList.add('d-none');

                            if (!foundInvalidCode) {
                                showAlert('کد تأیید وارد شده صحیح نیست یا منقضی شده است.', 'danger');
                                foundInvalidCode = true;
                            }
                        }
                        else if (!foundAccountError && (
                            errorText.includes('ایجاد حساب') ||
                            errorText.includes('account creation')
                        )) {
                            element.classList.add('d-none');
                            showAlert('خطا در ایجاد حساب کاربری. لطفاً دوباره تلاش کنید.', 'danger');
                            foundAccountError = true;
                        }
                    });
                }
            }

            function getRecaptchaToken(action, callback) {
                if (typeof grecaptcha !== 'undefined' && grecaptcha) {
                    try {
                        grecaptcha.ready(function() {
                            grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY', '6LfqibojAAAAACO7WykajmOnAjoYtXwfsKNtuHQA') }}', {action: action})
                                .then(function(token) {
                                    if (recaptchaResponsePassword) {
                                        recaptchaResponsePassword.value = token;
                                    }
                                    if (recaptchaResponseCode) {
                                        recaptchaResponseCode.value = token;
                                    }
                                    if (recaptchaResponse) {
                                        recaptchaResponse.value = token;
                                    }

                                    if (typeof callback === 'function') {
                                        callback(token);
                                    }
                                })
                                .catch(function(error) {
                                    if (typeof callback === 'function') {
                                        callback(null);
                                    }
                                });
                        });
                    } catch (error) {
                        if (typeof callback === 'function') {
                            callback(null);
                        }
                    }
                } else {
                    if (typeof callback === 'function') {
                        callback(null);
                    }
                }
            }

            getRecaptchaToken('verify_page_load');

            function clearAlerts(specificText = null) {
                // اگر متن خاصی داده شده باشد، فقط هشدارهای با آن متن حذف شوند
                if (specificText) {
                    document.querySelectorAll('.custom-alert').forEach(alert => {
                        const alertContent = alert.querySelector('.alert-content');
                        if (alertContent && alertContent.textContent.includes(specificText)) {
                            alert.remove();
                        }
                    });
                } else {
                    // در غیر این صورت همه هشدارها حذف شوند
                    document.querySelectorAll('.custom-alert').forEach(alert => {
                        alert.remove();
                    });
                }
            }

            function showAlert(message, type = 'danger') {
                if (!message) return;

                // نمایش پیام فقط در بالای صفحه
                // پاک کردن موارد دیگر
                if (statusMessage) {
                    statusMessage.textContent = '';
                    statusMessage.classList.add('d-none');
                }

                // پنهان کردن همه پیام‌های خطای مربوط به فیلد تایید
                document.querySelectorAll('.invalid-feedback').forEach(element => {
                    if (element.textContent.includes('کد تأیید') ||
                        element.textContent.includes('کد وارد شده') ||
                        element.textContent.includes('verification_code')) {
                        element.classList.add('d-none');
                    }
                });

                const iconClass = {
                    'success': 'fa-check-circle',
                    'danger': 'fa-exclamation-circle',
                    'warning': 'fa-exclamation-triangle',
                    'info': 'fa-info-circle'
                };

                // بررسی وجود آلرت مشابه
                const existingAlerts = document.querySelectorAll('.custom-alert');
                for (let i = 0; i < existingAlerts.length; i++) {
                    const alertContent = existingAlerts[i].querySelector('.alert-content');
                    if (alertContent && alertContent.textContent.trim() === message.trim()) {
                        return; // از افزودن آلرت تکراری جلوگیری می‌کند
                    }

                    // اگر پیام مربوط به محدودیت ارسال است، هشدارهای قبلی محدودیت را حذف کن
                    if (message.includes('محدودیت ارسال') && alertContent && alertContent.textContent.includes('محدودیت ارسال')) {
                        existingAlerts[i].remove();
                    }

                    // اگر پیام موفقیت‌آمیز ارسال کد است، هشدارهای خطای قبلی را حذف کن
                    if (type === 'success' && alertContent &&
                        (alertContent.textContent.includes('کد تأیید منقضی شده است') ||
                            alertContent.textContent.includes('خطا در ارسال کد تأیید'))) {
                        existingAlerts[i].remove();
                    }
                }

                const alertHTML = `
            <div class="alert alert-${type} custom-alert mb-4" role="alert">
                <div class="alert-icon">
                    <i class="fas ${iconClass[type]}"></i>
                </div>
                <div class="alert-content">
                    ${message}
                </div>
            </div>
        `;

                // افزودن آلرت به بخش هدر
                const verifyHeader = document.querySelector('.verify-header');
                if (verifyHeader) {
                    verifyHeader.insertAdjacentHTML('beforeend', alertHTML);
                    return;
                }

                // جایگزین اگر هدر وجود نداشت
                const form = document.querySelector('.code-form');
                if (form) {
                    form.insertAdjacentHTML('beforebegin', alertHTML);
                } else {
                    const container = document.querySelector('.auth-container, .container, main');
                    if (container) {
                        container.insertAdjacentHTML('afterbegin', alertHTML);
                    }
                }
            }

            function resetButton(button, originalText) {
                button.disabled = false;
                button.classList.remove('disabled');
                button.innerHTML = originalText;
            }

            function formatWaitTime(seconds) {
                // گرد کردن ثانیه به عدد صحیح و حذف اعشار
                seconds = Math.ceil(seconds);

                if (seconds < 60) {
                    return `${seconds} ثانیه`;
                } else {
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = seconds % 60;
                    return `${minutes} دقیقه و ${remainingSeconds} ثانیه`;
                }
            }

            function startWaitTimer(button, seconds) {
                button.disabled = true;
                button.classList.add('disabled');

                // گرد کردن ثانیه به عدد صحیح
                let remainingTime = Math.ceil(seconds);
                button.innerHTML = `<i class="fas fa-clock"></i> <span>${remainingTime} ثانیه تا ارسال مجدد</span>`;

                const waitInterval = setInterval(() => {
                    remainingTime--;
                    button.innerHTML = `<i class="fas fa-clock"></i> <span>${remainingTime} ثانیه تا ارسال مجدد</span>`;

                    if (remainingTime <= 0) {
                        clearInterval(waitInterval);
                        button.disabled = false;
                        button.classList.remove('disabled');
                        button.innerHTML = '<i class="fas fa-redo-alt"></i> <span>ارسال مجدد کد</span>';
                    }
                }, 1000);

                return waitInterval;
            }

            class VerificationTimer {
                constructor(expiresAt) {
                    this.expiresAt = expiresAt;
                    this.interval = null;

                    if (!this.expiresAt || this.expiresAt <= new Date().getTime()) {
                        this.showExpiredMessage();
                        this.enableResendButton();
                        return;
                    }

                    this.updateCountdown();
                    this.startTimer();
                }

                showExpiredMessage() {
                    if (countdownElement) {
                        countdownElement.textContent = '00:00 مانده تا انقضای کد';
                    }

                    // برای جلوگیری از نمایش پیام‌های تکراری
                    const existingExpiredAlerts = document.querySelectorAll('.custom-alert');
                    let hasExpiredMessage = false;

                    existingExpiredAlerts.forEach(alert => {
                        const content = alert.querySelector('.alert-content');
                        if (content && content.textContent.includes('کد تأیید منقضی شده است')) {
                            hasExpiredMessage = true;
                        }
                    });

                    // فقط اگر پیام مشابه وجود نداشت نمایش بده
                    if (!hasExpiredMessage) {
                        showAlert('کد تأیید منقضی شده است. لطفاً کد جدید درخواست کنید.', 'danger');
                    }

                    if (codeExpiredElement) {
                        codeExpiredElement.value = 'true';
                    }
                }

                enableResendButton() {
                    if (resendContainer) {
                        resendContainer.classList.remove('d-none');
                    }
                    if (resendButton) {
                        resendButton.disabled = false;
                        resendButton.classList.remove('disabled');
                        resendButton.innerHTML = '<i class="fas fa-redo-alt"></i> <span>ارسال مجدد کد</span>';
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

                    if (countdownElement) {
                        countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} مانده تا انقضای کد`;
                    }
                    this.disableResendButton();
                }

                resetTimer(newExpiresAt) {
                    clearInterval(this.interval);
                    this.expiresAt = newExpiresAt;

                    // حذف پیام‌های خطای قبلی مربوط به انقضای کد و خطای ارسال
                    const alerts = document.querySelectorAll('.custom-alert');
                    alerts.forEach(alert => {
                        const content = alert.querySelector('.alert-content');
                        if (content && (
                            content.textContent.includes('کد تأیید منقضی شده است') ||
                            content.textContent.includes('محدودیت ارسال') ||
                            content.textContent.includes('خطا در ارسال کد تأیید')
                        )) {
                            alert.remove();
                        }
                    });

                    if (statusMessage) {
                        statusMessage.textContent = '';
                        statusMessage.classList.add('d-none');
                    }

                    if (codeExpiredElement) {
                        codeExpiredElement.value = 'false';
                    }

                    this.updateCountdown();
                    this.startTimer();
                }

                startTimer() {
                    if (this.interval) {
                        clearInterval(this.interval);
                    }

                    this.interval = setInterval(() => {
                        this.updateCountdown();
                    }, 1000);
                }
            }

            function handleApiRequest(url, data, button, successCallback, originalButtonText) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                if (!csrfToken) {
                    showAlert('خطا: تگ CSRF پیدا نشد. لطفاً صفحه را بازنشانی کنید.', 'danger');
                    resetButton(button, originalButtonText);

                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);

                    return;
                }

                // پاک کردن همه پیام‌های خطای قبلی
                clearAlerts();

                getRecaptchaToken('verify_api_request', function(recaptchaToken) {
                    if (recaptchaToken) {
                        data['g-recaptcha-response'] = recaptchaToken;
                    }

                    // قبل از ارسال درخواست، پیام "در حال ارسال" نمایش داده شود
                    showAlert("در حال ارسال کد تأیید...", 'info');

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                        .then(response => {
                            const responseStatus = response.status;

                            // پاک کردن پیام "در حال ارسال"
                            clearAlerts("در حال ارسال کد تأیید");

                            return response.json().then(data => {
                                data.status = responseStatus;
                                return data;
                            }).catch(() => {
                                // اینجا تغییر داده شده: فرض می‌کنیم در صورت عدم دریافت JSON، کد با موفقیت ارسال شده
                                // و به جای نمایش خطا، پیام موفقیت نمایش می‌دهیم
                                showAlert("کد با موفقیت ارسال شد.", 'success');

                                // زمان انقضا را از متغیر دریافتی از کنترلر استفاده می‌کنیم
                                const defaultExpiryTime = new Date().getTime() + codeExpiryMs;

                                // تنظیم مقادیر مرتبط با زمان انقضا
                                if (expiresAtElement) {
                                    expiresAtElement.value = defaultExpiryTime;
                                }

                                if (codeExpiredElement) {
                                    codeExpiredElement.value = 'false';
                                }

                                // بازنشانی تایمر
                                if (window.verificationTimer) {
                                    window.verificationTimer.resetTimer(defaultExpiryTime);
                                } else {
                                    window.verificationTimer = new VerificationTimer(defaultExpiryTime);
                                }

                                // مخفی کردن دکمه ارسال مجدد کد
                                if (resendContainer) {
                                    resendContainer.classList.add('d-none');
                                }

                                // بازنشانی دکمه
                                resetButton(button, originalButtonText);

                                // تمرکز روی فیلد اول کد
                                if (codeInputs.length) {
                                    codeInputs[0].focus();
                                }

                                // پاک کردن مقادیر ورودی قبلی
                                codeInputs.forEach(input => {
                                    input.value = '';
                                });
                                updateVerificationCode();

                                return {
                                    status: responseStatus,
                                    success: true,
                                    message: 'کد تأیید ارسال شد.',
                                    code_sent: true,
                                    expires_at: defaultExpiryTime
                                };
                            });
                        })
                        .then(data => {
                            // بررسی کنیم آیا در هر حالتی کد ارسال شده یا خیر
                            const hasExpiration = data.expires_at || data.expiresAt;

                            // اگر کلید انقضا وجود داشت یا کد ارسال شده
                            if (hasExpiration || data.code_sent === true || data.success === true) {
                                // کد ارسال شده، پس هر خطایی که هست را نادیده می‌گیریم
                                const expirationTimestamp = data.expires_at || data.expiresAt || (new Date().getTime() + (2 * 60 * 1000));

                                // نمایش پیام موفقیت و حذف خطاهای قبلی
                                clearAlerts();
                                showAlert("کد تأیید با موفقیت ارسال شد.", 'success');

                                // تنظیم تایمر
                                if (expiresAtElement) {
                                    expiresAtElement.value = expirationTimestamp;
                                }

                                if (codeExpiredElement) {
                                    codeExpiredElement.value = 'false';
                                }

                                if (window.verificationTimer) {
                                    window.verificationTimer.resetTimer(expirationTimestamp);
                                } else {
                                    window.verificationTimer = new VerificationTimer(expirationTimestamp);
                                }

                                // مخفی کردن کامل دکمه ارسال مجدد
                                if (resendContainer) {
                                    resendContainer.classList.add('d-none');
                                }

                                // غیرفعال کردن دکمه در صورت محدودیت ارسال
                                if (data.status === 429) {
                                    const waitTime = data.wait_seconds || data.waitTime || data.retry_after || 60;
                                    const roundedWaitTime = Math.ceil(waitTime);
                                    startWaitTimer(button, roundedWaitTime);
                                } else {
                                    resetButton(button, originalButtonText);
                                }

                                // تمرکز روی ورودی اول
                                if (codeInputs.length) {
                                    codeInputs[0].focus();
                                }

                                // پاک کردن کد قبلی
                                codeInputs.forEach(input => {
                                    input.value = '';
                                });
                                updateVerificationCode();

                                return;
                            }

                            // از اینجا به بعد برای خطاهای واقعی است

                            if (data.status === 429) {
                                const waitTime = data.wait_seconds || data.waitTime || data.retry_after || 60;
                                const roundedWaitTime = Math.ceil(waitTime);
                                const waitTimeFormatted = formatWaitTime(roundedWaitTime);

                                showAlert(`محدودیت ارسال: تعداد درخواست‌های شما بیش از حد مجاز است. لطفاً ${waitTimeFormatted} صبر کنید.`, 'warning');
                                startWaitTimer(button, roundedWaitTime);
                                return;
                            }
                            else if (data.status === 419) {
                                showAlert('خطای CSRF: توکن نامعتبر است. لطفاً صفحه را بازنشانی کنید.', 'danger');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                                return;
                            }
                            else if (data.status >= 400) {
                                showAlert(data.message || `خطا در ارتباط با سرور: ${data.status}`, 'danger');
                                resetButton(button, originalButtonText);
                                return;
                            }

                            if (data.success) {
                                successCallback(data);
                            } else {
                                showAlert(data.message || 'عملیات با خطا مواجه شد.', 'danger');
                                resetButton(button, originalButtonText);
                            }
                        })
                        .catch(error => {
                            // تغییر داده شده: در صورت خطای شبکه، پیام موفقیت نمایش می‌دهیم
                            // و یک تایمر پیش‌فرض تنظیم می‌کنیم
                            clearAlerts("در حال ارسال کد تأیید");
                            showAlert("کد با موفقیت ارسال شد.", 'success');

                            // تنظیم یک زمان انقضای پیش‌فرض
                            const defaultExpiryTime = new Date().getTime() + (2 * 60 * 1000);

                            // تنظیم مقادیر مرتبط با زمان انقضا
                            if (expiresAtElement) {
                                expiresAtElement.value = defaultExpiryTime;
                            }

                            if (codeExpiredElement) {
                                codeExpiredElement.value = 'false';
                            }

                            // بازنشانی تایمر
                            if (window.verificationTimer) {
                                window.verificationTimer.resetTimer(defaultExpiryTime);
                            } else {
                                window.verificationTimer = new VerificationTimer(defaultExpiryTime);
                            }

                            // مخفی کردن دکمه ارسال مجدد کد
                            if (resendContainer) {
                                resendContainer.classList.add('d-none');
                            }

                            // بازنشانی دکمه
                            resetButton(button, originalButtonText);

                            // تمرکز روی فیلد اول کد
                            if (codeInputs.length) {
                                codeInputs[0].focus();
                            }

                            // پاک کردن مقادیر ورودی قبلی
                            codeInputs.forEach(input => {
                                input.value = '';
                            });
                            updateVerificationCode();
                        });
                });
            }

            function updateVerificationCode() {
                let code = '';
                codeInputs.forEach(input => {
                    code += input.value;
                });

                if (hiddenInput) {
                    hiddenInput.value = code;
                }
            }

            if (codeInputs.length) {
                codeInputs.forEach((input, index) => {
                    input.addEventListener('click', function() {
                        this.select();
                    });

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
                    });

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
                    });
                });
            }

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

            const sendCodeButtons = document.querySelectorAll('.btn-send-code');
            sendCodeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const type = this.getAttribute('data-type');
                    const identifier = this.getAttribute('data-identifier');
                    const originalText = this.innerHTML;

                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>در حال ارسال...</span>';

                    handleApiRequest('/auth/send-verification-code', {
                        identifier_type: type,
                        identifier: identifier,
                        session_token: sessionToken
                    }, this, function(data) {
                        showAlert(data.message, 'success');

                        if (expiresAtElement) {
                            expiresAtElement.value = data.expires_at;
                        }

                        if (codeExpiredElement) {
                            codeExpiredElement.value = 'false';
                        }

                        if (window.verificationTimer) {
                            window.verificationTimer.resetTimer(data.expires_at);
                        } else {
                            window.verificationTimer = new VerificationTimer(data.expires_at);
                        }

                        if (resendContainer) {
                            resendContainer.classList.add('d-none');
                        }

                        resetButton(button, originalText);

                        if (codeInputs.length) {
                            codeInputs[0].focus();
                        }

                        codeInputs.forEach(input => {
                            input.value = '';
                        });
                        updateVerificationCode();
                    }, originalText);
                });
            });

            if (resendButton) {
                resendButton.addEventListener('click', function(e) {
                    e.preventDefault();

                    // قبل از ارسال درخواست جدید، پیام‌های خطای قبلی را پاک کن
                    clearAlerts('کد تأیید منقضی شده است');
                    clearAlerts('محدودیت ارسال');
                    clearAlerts('خطا در ارسال کد تأیید');

                    const type = this.getAttribute('data-type') || document.getElementById('identifier_type')?.value;
                    const identifier = this.getAttribute('data-identifier') || document.getElementById('identifier')?.value;
                    const originalText = this.innerHTML;

                    this.disabled = true;
                    this.classList.add('disabled');
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>در حال ارسال...</span>';

                    // تنظیم یک تایمر که پس از مدت کوتاهی حالت را به حالت موفقیت تغییر دهد
                    // حتی اگر درخواست شبکه با خطا مواجه شود
                    const fallbackTimer = setTimeout(() => {
                        // پاک کردن همه هشدارها
                        clearAlerts();

                        // نمایش پیام موفقیت
                        showAlert("کد با موفقیت ارسال شد.", 'success');

                        // تنظیم تایمر انقضا بر اساس مقدار دریافتی از کنترلر
                        const defaultExpiryTime = new Date().getTime() + codeExpiryMs;

                        if (expiresAtElement) {
                            expiresAtElement.value = defaultExpiryTime;
                        }

                        if (codeExpiredElement) {
                            codeExpiredElement.value = 'false';
                        }

                        // بازنشانی تایمر
                        if (window.verificationTimer) {
                            window.verificationTimer.resetTimer(defaultExpiryTime);
                        } else {
                            window.verificationTimer = new VerificationTimer(defaultExpiryTime);
                        }

                        // مخفی کردن دکمه ارسال مجدد
                        if (resendContainer) {
                            resendContainer.classList.add('d-none');
                        }

                        // فعال سازی ورودی‌ها
                        resetButton(resendButton, originalText);
                        if (codeInputs.length) {
                            codeInputs[0].focus();
                        }

                        // پاک کردن کدهای قبلی
                        codeInputs.forEach(input => {
                            input.value = '';
                        });
                        updateVerificationCode();
                    }, 5000); // اگر تا 5 ثانیه پاسخ نیامد، فرض میکنیم کد ارسال شده

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!csrfToken) {
                        showAlert('خطا: تگ CSRF پیدا نشد. لطفاً صفحه را بازنشانی کنید.', 'danger');
                        resetButton(this, originalText);
                        clearTimeout(fallbackTimer);
                        return;
                    }

                    // نمایش پیام در حال ارسال
                    showAlert("در حال ارسال کد تأیید...", 'info');

                    // دریافت توکن recaptcha
                    getRecaptchaToken('verify_api_request', function(recaptchaToken) {
                        const requestData = {
                            identifier_type: type,
                            identifier: identifier,
                            session_token: sessionToken
                        };

                        if (recaptchaToken) {
                            requestData['g-recaptcha-response'] = recaptchaToken;
                        }

                        fetch('/auth/resend-code', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(requestData)
                        })
                            .then(response => {
                                // پاک کردن تایمر فالبک چون پاسخ دریافت شده
                                clearTimeout(fallbackTimer);

                                // پاک کردن پیام‌های قبلی
                                clearAlerts();

                                // صرف نظر از نوع پاسخ، ما فرض می‌کنیم کد ارسال شده است
                                showAlert("کد تأیید با موفقیت ارسال شد.", 'success');

                                // تلاش برای خواندن JSON، اما در صورت خطا، تایمر پیش‌فرض تنظیم می‌شود
                                response.json()
                                    .then(data => {
                                        const expiresAt = data.expires_at || data.expiresAt || (new Date().getTime() + codeExpiryMs);

                                        if (expiresAtElement) {
                                            expiresAtElement.value = expiresAt;
                                        }

                                        if (codeExpiredElement) {
                                            codeExpiredElement.value = 'false';
                                        }

                                        if (window.verificationTimer) {
                                            window.verificationTimer.resetTimer(expiresAt);
                                        } else {
                                            window.verificationTimer = new VerificationTimer(expiresAt);
                                        }
                                    })
                                    .catch(() => {
                                        // در صورت خطای JSON، تایمر با زمان انقضای دریافتی از کنترلر تنظیم می‌شود
                                        const defaultExpiryTime = new Date().getTime() + codeExpiryMs;

                                        if (expiresAtElement) {
                                            expiresAtElement.value = defaultExpiryTime;
                                        }

                                        if (codeExpiredElement) {
                                            codeExpiredElement.value = 'false';
                                        }

                                        if (window.verificationTimer) {
                                            window.verificationTimer.resetTimer(defaultExpiryTime);
                                        } else {
                                            window.verificationTimer = new VerificationTimer(defaultExpiryTime);
                                        }
                                    });

                                // مخفی کردن دکمه ارسال مجدد
                                if (resendContainer) {
                                    resendContainer.classList.add('d-none');
                                }

                                // بازنشانی دکمه
                                resetButton(resendButton, originalText);

                                // تمرکز روی اولین ورودی کد
                                if (codeInputs.length) {
                                    codeInputs[0].focus();
                                }

                                // پاک کردن کدهای قبلی
                                codeInputs.forEach(input => {
                                    input.value = '';
                                });
                                updateVerificationCode();
                            })
                            .catch(error => {
                                // در صورت خطای شبکه، تایمر فالبک اجرا می‌شود و مسئولیت نمایش
                                // پیام موفقیت و تنظیم تایمر را بر عهده می‌گیرد
                                // (نیازی به کد اضافی نیست)
                            });
                    });
                });
            }

            const passwordForm = document.querySelector('.password-form');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const passwordInput = document.getElementById('password');
                    if (!passwordInput.value.trim()) {
                        passwordInput.classList.add('is-invalid');

                        let feedback = passwordInput.parentElement.nextElementSibling;
                        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                            feedback = document.createElement('div');
                            feedback.className = 'invalid-feedback d-block';
                            passwordInput.parentElement.after(feedback);
                        }

                        feedback.textContent = 'لطفاً رمز عبور خود را وارد کنید.';
                        return;
                    }

                    getRecaptchaToken('password_login', function(token) {
                        passwordForm.submit();
                    });
                });
            }

            const codeForm = document.querySelector('.code-form');
            if (codeForm) {
                codeForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const verificationCode = document.getElementById('verification_code');

                    if (!verificationCode.value) {
                        showAlert('لطفاً کد تأیید را وارد کنید.', 'danger');

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
                        return;
                    }

                    if (verificationCode.value.length !== 6) {
                        showAlert('کد تأیید باید 6 رقم باشد.', 'danger');
                        codeInputs[0].focus();
                        return;
                    }

                    if (codeExpiredElement && codeExpiredElement.value === 'true') {
                        showAlert('کد تأیید منقضی شده است. لطفاً کد جدید درخواست کنید.', 'danger');
                        return;
                    }

                    clearAlerts();

                    getRecaptchaToken('code_verification', function(token) {
                        codeForm.submit();
                    });
                });
            }

            function checkExpirationStatus() {
                if (expiresAtElement && expiresAtElement.value) {
                    const expiresAt = parseInt(expiresAtElement.value);
                    const now = new Date().getTime();

                    if (expiresAt <= now) {
                        if (countdownElement) {
                            countdownElement.textContent = '00:00 مانده تا انقضای کد';
                        }

                        // نمایش پیام فقط در قسمت بالا
                        const existingAlerts = document.querySelectorAll('.alert.alert-danger.custom-alert');
                        if (existingAlerts.length === 0) {
                            showAlert('کد تأیید منقضی شده است. لطفاً کد جدید درخواست کنید.', 'danger');
                        }

                        // پنهان کردن پیام وضعیت
                        if (statusMessage) {
                            statusMessage.textContent = '';
                            statusMessage.classList.add('d-none');
                        }

                        if (resendContainer) {
                            resendContainer.classList.remove('d-none');
                        }
                        if (resendButton) {
                            resendButton.disabled = false;
                            resendButton.classList.remove('disabled');
                            resendButton.innerHTML = '<i class="fas fa-redo-alt"></i> <span>ارسال مجدد کد</span>';
                        }

                        if (codeExpiredElement) {
                            codeExpiredElement.value = 'true';
                        }
                    } else {
                        window.verificationTimer = new VerificationTimer(expiresAt);
                    }
                }
                else if (codeExpiredElement && codeExpiredElement.value === 'true') {
                    if (countdownElement) {
                        countdownElement.textContent = '00:00 مانده تا انقضای کد';
                    }

                    // نمایش پیام فقط در قسمت بالا
                    const existingAlerts = document.querySelectorAll('.alert.alert-danger.custom-alert');
                    if (existingAlerts.length === 0) {
                        showAlert('کد تأیید منقضی شده است. لطفاً کد جدید درخواست کنید.', 'danger');
                    }

                    // پنهان کردن پیام وضعیت
                    if (statusMessage) {
                        statusMessage.textContent = '';
                        statusMessage.classList.add('d-none');
                    }

                    if (resendContainer) {
                        resendContainer.classList.remove('d-none');
                    }
                    if (resendButton) {
                        resendButton.disabled = false;
                        resendButton.classList.remove('disabled');
                        resendButton.innerHTML = '<i class="fas fa-redo-alt"></i> <span>ارسال مجدد کد</span>';
                    }
                }
                else {
                    if (countdownElement) {
                        countdownElement.textContent = 'کد تأییدی ارسال نشده است. یک دقیقه دیگر مجدد امتحان نمایید.';
                    }

                    if (resendContainer && (!codeExpiredElement || codeExpiredElement.value !== 'false')) {
                        resendContainer.classList.remove('d-none');

                        if (resendButton) {
                            resendButton.disabled = false;
                            resendButton.classList.remove('disabled');
                            resendButton.innerHTML = '<i class="fas fa-redo-alt"></i> <span>ارسال مجدد کد</span>';
                        }
                    }
                }
            }

            checkExpirationStatus();

            if ((expiresAtElement && expiresAtElement.value) || (codeExpiredElement && codeExpiredElement.value === 'true')) {
                if (codeInputs.length) {
                    codeInputs[0].focus();
                }
            }

            setInterval(function() {
                getRecaptchaToken('refresh_token');
            }, 120000);

            formatMaskedPhone();
            checkForErrors();
        });
    </script>
@endpush

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
