<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use App\Services\EmailService;
use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * نمایش فرم ورود
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * پردازش مرحله اول ورود (شناسایی کاربر)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function identify(Request $request)
    {
        // اعتبارسنجی ورودی‌ها
        $request->validate([
            'login_method' => 'required|in:phone,email',
            'g-recaptcha-response' => 'required|captcha', // اعتبارسنجی کپچای گوگل
        ], [
            'g-recaptcha-response.required' => 'لطفاً تأیید کنید که ربات نیستید.',
            'g-recaptcha-response.captcha' => 'اعتبارسنجی کپچا با خطا مواجه شد. لطفاً دوباره تلاش کنید.',
        ]);

        $loginMethod = $request->input('login_method');

        if ($loginMethod === 'phone') {
            $request->validate([
                'phone' => 'required|regex:/^09[0-9]{9}$/',
            ], [
                'phone.required' => 'لطفاً شماره موبایل خود را وارد کنید.',
                'phone.regex' => 'شماره موبایل وارد شده معتبر نیست.',
            ]);

            $identifier = $request->phone;
            $identifierType = 'phone';
        } else {
            $request->validate([
                'email' => 'required|email',
            ], [
                'email.required' => 'لطفاً ایمیل خود را وارد کنید.',
                'email.email' => 'ایمیل وارد شده معتبر نیست.',
            ]);

            $identifier = $request->email;
            $identifierType = 'email';
        }

        // بررسی وجود کاربر و رمز عبور
        $user = User::where($identifierType, $identifier)->first();

        // چک کنیم آیا این کاربر با روش دیگری ثبت نام کرده است
        if (!$user && $identifierType === 'email') {
            // جستجو بر اساس شماره تلفن همین ایمیل (اگر قبلاً ثبت شده باشد)
            $existingUser = User::whereNotNull('phone')
                ->whereNull('email')
                ->get()
                ->filter(function($existingUser) use ($identifier) {
                    // ممکن است داده‌های کاربری در سیستم دیگری ذخیره شده باشد
                    return strtolower($existingUser->userProfile->email ?? '') === strtolower($identifier);
                })
                ->first();

            if ($existingUser) {
                // کاربر را به‌روزرسانی می‌کنیم
                $existingUser->email = $identifier;
                $existingUser->save();
                $user = $existingUser;
            }
        } elseif (!$user && $identifierType === 'phone') {
            // جستجو بر اساس ایمیل همین شماره تلفن (اگر قبلاً ثبت شده باشد)
            $existingUser = User::whereNotNull('email')
                ->whereNull('phone')
                ->get()
                ->filter(function($existingUser) use ($identifier) {
                    return $existingUser->userProfile->phone ?? '' === $identifier;
                })
                ->first();

            if ($existingUser) {
                // کاربر را به‌روزرسانی می‌کنیم
                $existingUser->phone = $identifier;
                $existingUser->save();
                $user = $existingUser;
            }
        }

        $userExists = $user ? true : false;
        $hasPassword = $user && !empty($user->password) ? true : false;

        // ذخیره اطلاعات در session با رمزگذاری
        $sessionData = [
            'auth_identifier' => $identifier,
            'auth_identifier_type' => $identifierType,
            'redirect_to' => $request->redirect_to ?? null,
            'user_exists' => $userExists,
            'has_password' => $hasPassword,
            'session_token' => Str::random(40), // توکن امنیتی برای جلوگیری از CSRF
        ];

        // ذخیره اطلاعات در session با امنیت بیشتر
        session()->put('auth_data', encrypt($sessionData));

        // از ذخیره مستقیم اطلاعات جلوگیری می‌کنیم
        Log::info('User identification initiated', [
            'type' => $identifierType,
            'user_exists' => $userExists,
            'has_password' => $hasPassword
        ]);

        // ریدایرکت به صفحه تأیید
        return redirect()->route('auth.verify-form');
    }

    /**
     * دریافت اطلاعات احراز هویت از session
     *
     * @return array|null
     */
    private function getAuthSessionData()
    {
        if (!session()->has('auth_data')) {
            return null;
        }

        try {
            return decrypt(session('auth_data'));
        } catch (\Exception $e) {
            Log::error('Failed to decrypt auth session data: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * نمایش فرم تأیید هویت
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showVerificationForm()
    {
        // بررسی وجود شناسه در سشن
        $authData = $this->getAuthSessionData();

        if (!$authData) {
            return redirect()->route('login')
                ->with('error', 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.');
        }

        $identifier = $authData['auth_identifier'];
        $identifierType = $authData['auth_identifier_type'];
        $userExists = $authData['user_exists'];
        $hasPassword = $authData['has_password'];
        $sessionToken = $authData['session_token'];

        // پیدا کردن آخرین کد تأیید معتبر
        $verificationCode = null;
        $codeExpired = false;
        $lastCode = VerificationCode::where('identifier', $identifier)
            ->where('type', $identifierType)
            ->where('used', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastCode) {
            // بررسی انقضای کد
            if ($lastCode->expires_at->gt(now())) {
                $verificationCode = $lastCode;

                // امنیت: کد تأیید را در سشن ذخیره نمی‌کنیم
                Log::info("Found existing valid verification code", [
                    'identifier' => $identifier,
                    'expires_at' => $lastCode->expires_at->format('Y-m-d H:i:s'),
                    'remaining_seconds' => $lastCode->getRemainingTime()
                ]);
            } else {
                $codeExpired = true;
                Log::info("Found expired verification code", [
                    'identifier' => $identifier,
                    'expired_at' => $lastCode->expires_at->format('Y-m-d H:i:s')
                ]);
            }
        }

        // اگر کاربر جدید است یا رمز عبور ندارد و کد معتبری وجود ندارد، به صورت خودکار کد ارسال کنیم
        if ((!$userExists || !$hasPassword) && !$verificationCode && !$codeExpired) {
            try {
                // بررسی محدودیت ارسال کد
                $canSend = VerificationCode::canSendNew($identifier, $identifierType);
                if ($canSend === true) {
                    // ایجاد کد تأیید
                    $verificationCode = VerificationCode::generateFor($identifier, $identifierType);

                    // واقعاً کد را ارسال می‌کنیم
                    $this->sendCodeToUser($identifier, $identifierType, $verificationCode->code);

                    // پیام موفقیت
                    $message = $identifierType === 'email'
                        ? 'کد تأیید به ایمیل شما ارسال شد.'
                        : 'کد تأیید به شماره موبایل شما ارسال شد.';

                    session()->flash('success', $message);
                } else {
                    // اگر محدودیت زمانی وجود دارد
                    $message = "برای ارسال مجدد کد باید {$canSend} ثانیه صبر کنید.";
                    session()->flash('info', $message);
                }
            } catch (\Exception $e) {
                Log::error("Error sending verification code: " . $e->getMessage());
                session()->flash('error', 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.');
            }
        }

        // اگر کاربر وجود دارد، اطلاعات تماس او را دریافت کنیم
        $contactInfo = null;
        if ($userExists) {
            $user = User::where($identifierType, $identifier)->first();
            $contactInfo = [
                'has_phone' => !empty($user->phone),
                'has_email' => !empty($user->email),
                'phone' => $user->phone ? substr_replace($user->phone, '***', 4, 4) : null, // پنهان کردن بخشی از شماره
                'email' => $user->email ? $this->maskEmail($user->email) : null, // پنهان کردن بخشی از ایمیل
            ];
        }

        return view('auth.verify', [
            'identifier' => $this->maskIdentifier($identifier, $identifierType),
            'identifierType' => $identifierType,
            'userExists' => $userExists,
            'hasPassword' => $hasPassword,
            'contactInfo' => $contactInfo,
            'verificationCode' => $verificationCode, // فقط وجود یا عدم وجود آن
            'codeExpired' => $codeExpired,
            'expiresAt' => $verificationCode ? $verificationCode->expires_at->timestamp * 1000 : null,
            'waitTime' => $verificationCode ? null : VerificationCode::canSendNew($identifier, $identifierType),
            'session_token' => $sessionToken, // برای تأیید CSRF در درخواست‌های AJAX
            // در محیط توسعه، شبیه‌سازی ارسال کد
            'dev_code' => app()->environment('local', 'development') ? $verificationCode->code ?? null : null
        ]);
    }

    /**
     * پنهان کردن بخشی از ایمیل
     *
     * @param string $email
     * @return string
     */
    private function maskEmail($email)
    {
        if (!$email) return null;

        $parts = explode('@', $email);
        if (count($parts) != 2) return $email;

        $name = $parts[0];
        $domain = $parts[1];

        $maskLength = max(2, min(strlen($name) - 2, 5));
        $visibleChars = min(3, strlen($name));

        $maskedName = substr($name, 0, $visibleChars) .
            str_repeat('*', $maskLength) .
            (strlen($name) > $visibleChars + $maskLength ? substr($name, -2) : '');

        return $maskedName . '@' . $domain;
    }

    /**
     * پنهان کردن بخشی از شناسه (ایمیل یا شماره تلفن)
     *
     * @param string $identifier
     * @param string $type
     * @return string
     */
    private function maskIdentifier($identifier, $type)
    {
        if ($type === 'email') {
            return $this->maskEmail($identifier);
        } else {
            // پنهان کردن بخشی از شماره موبایل
            return substr_replace($identifier, '***', 4, 4);
        }
    }

    /**
     * ارسال واقعی کد تأیید به کاربر
     *
     * @param string $identifier
     * @param string $type
     * @param string $code
     * @return bool
     */
    private function sendCodeToUser($identifier, $type, $code)
    {
        try {
            if ($type === 'email') {
                // ارسال کد از طریق ایمیل
                $emailService = new EmailService();
                $result = $emailService->sendVerificationCode($identifier, $code);

                if (!$result) {
                    Log::error("Failed to send verification email to: {$identifier}");
                    return false;
                }

                Log::info("Verification code sent to email: {$identifier}");
            } else {
                // ارسال کد از طریق پیامک
                $smsService = new SMSService();
                $result = $smsService->sendVerificationCode($identifier, $code);

                if (!$result) {
                    Log::error("Failed to send verification SMS to: {$identifier}");
                    return false;
                }

                Log::info("Verification code sent to phone: {$identifier}");
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Error sending verification code: " . $e->getMessage(), [
                'identifier' => $identifier,
                'type' => $type
            ]);
            return false;
        }
    }

    /**
     * ارسال کد تأیید
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerificationCode(Request $request)
    {
        try {
            // دریافت اطلاعات از session
            $authData = $this->getAuthSessionData();

            if (!$authData) {
                return response()->json([
                    'success' => false,
                    'message' => 'اطلاعات نشست معتبر نیست. لطفاً دوباره وارد شوید.'
                ], 400);
            }

            // تأیید توکن جلسه برای جلوگیری از CSRF
            if ($request->session_token !== $authData['session_token']) {
                Log::warning('CSRF attempt detected in sendVerificationCode', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'درخواست نامعتبر است. لطفاً صفحه را بارگذاری مجدد کنید.'
                ], 403);
            }

            $identifier = $authData['auth_identifier'];
            $identifierType = $authData['auth_identifier_type'];

            // اعمال محدودیت برای درخواست‌های متعدد (rate limiting)
            $key = 'send_code_'.$identifierType.'_'.$identifier;

            if (RateLimiter::tooManyAttempts($key, 5)) { // محدودیت 5 درخواست
                $seconds = RateLimiter::availableIn($key);

                return response()->json([
                    'success' => false,
                    'message' => "شما بیش از حد مجاز درخواست داده‌اید. لطفاً {$seconds} ثانیه دیگر مجدداً تلاش کنید.",
                    'wait_seconds' => $seconds
                ], 429);
            }

            RateLimiter::hit($key, 120); // محدودیت 120 ثانیه

            // بررسی محدودیت ارسال کد
            $canSend = VerificationCode::canSendNew($identifier, $identifierType);
            if ($canSend !== true) {
                return response()->json([
                    'success' => false,
                    'message' => "لطفاً {$canSend} ثانیه دیگر مجدداً تلاش کنید.",
                    'wait_seconds' => $canSend
                ], 429);
            }

            // ایجاد کد تأیید جدید
            $verificationCode = VerificationCode::generateFor($identifier, $identifierType);

            // ارسال کد تأیید به کاربر
            $sent = $this->sendCodeToUser($identifier, $identifierType, $verificationCode->code);

            if (!$sent) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'کد تأیید با موفقیت ارسال شد',
                'expires_in' => $verificationCode->getRemainingTime(),
                'expires_at' => $verificationCode->expires_at->timestamp * 1000, // تبدیل به میلی‌ثانیه برای جاوااسکریپت
                'dev_code' => app()->environment('local', 'development') ? $verificationCode->code : null
            ]);
        } catch (\Exception $e) {
            Log::error('Error in sendVerificationCode: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.'
            ], 500);
        }
    }

    /**
     * تأیید هویت کاربر
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        try {
            // دریافت اطلاعات از session
            $authData = $this->getAuthSessionData();

            if (!$authData) {
                Log::warning('Missing session data in verify method');
                return redirect()->route('login')->with('error', 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.');
            }

            $identifier = $authData['auth_identifier'];
            $identifierType = $authData['auth_identifier_type'];
            $userExists = $authData['user_exists'];
            $hasPassword = $authData['has_password'];
            $sessionToken = $authData['session_token'];

            // تأیید توکن CSRF
            if (!$request->has('_token') || !$request->session()->token() || !hash_equals($request->session()->token(), $request->_token)) {
                Log::warning('CSRF token validation failed', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                return redirect()->route('login')->with('error', 'درخواست نامعتبر است. لطفاً دوباره وارد شوید.');
            }

            // بررسی روش تأیید (رمز عبور یا کد تأیید)
            $verifyMethod = $request->verify_method ?? 'code';
            Log::info('Verification method: ' . $verifyMethod);

            // پیدا کردن کاربر
            $user = User::where($identifierType, $identifier)->first();

            if ($verifyMethod === 'password' && $user && $hasPassword) {
                // اعتبارسنجی رمز عبور
                $request->validate([
                    'password' => 'required|string',
                ], [
                    'password.required' => 'لطفاً رمز عبور خود را وارد کنید.',
                ]);

                // اعمال محدودیت تلاش‌های ناموفق
                $key = 'login_attempts_'.$identifierType.'_'.sha1($identifier);

                if (RateLimiter::tooManyAttempts($key, 5)) { // 5 تلاش ناموفق
                    $seconds = RateLimiter::availableIn($key);

                    return back()->withErrors([
                        'password' => "به دلیل تلاش‌های ناموفق زیاد، حساب کاربری شما به مدت {$seconds} ثانیه قفل شده است."
                    ]);
                }

                // بررسی رمز عبور
                if (!Hash::check($request->password, $user->password)) {
                    RateLimiter::hit($key, 300); // 300 ثانیه محدودیت

                    Log::info('Invalid password for user', ['identifier' => $identifier]);
                    return back()->withErrors(['password' => 'رمز عبور اشتباه است.']);
                }

                // پاک کردن محدودیت‌ها در صورت ورود موفق
                RateLimiter::clear($key);

                Log::info('User authenticated with password', ['user_id' => $user->id]);

            } else { // verify_method === 'code' یا کاربر جدید است
                // اعتبارسنجی کد تأیید
                $request->validate([
                    'verification_code' => 'required|string|size:6',
                ], [
                    'verification_code.required' => 'لطفاً کد تأیید را وارد کنید.',
                    'verification_code.size' => 'کد تأیید باید 6 رقم باشد.',
                ]);

                // اعمال محدودیت تلاش‌های ناموفق
                $key = 'verify_code_attempts_'.$identifierType.'_'.sha1($identifier);

                if (RateLimiter::tooManyAttempts($key, 10)) { // 10 تلاش ناموفق
                    $seconds = RateLimiter::availableIn($key);

                    return back()->withErrors([
                        'verification_code' => "به دلیل تلاش‌های ناموفق زیاد، لطفاً {$seconds} ثانیه دیگر مجدداً تلاش کنید."
                    ]);
                }

                // بررسی کد تأیید
                $verificationCode = $request->verification_code;
                Log::info('Checking verification code', [
                    'identifier' => $identifier,
                    'identifier_type' => $identifierType
                ]);

                // بررسی کد در دیتابیس
                $isValid = VerificationCode::validate($identifier, $verificationCode, $identifierType);

                // در محیط توسعه، برای آزمایش کد رمز ثابت 123456 را هم قبول می‌کنیم
                if (!$isValid && app()->environment('local', 'development') && $verificationCode === '123456') {
                    $isValid = true;
                    Log::info('Using development test code');
                }

                if (!$isValid) {
                    RateLimiter::hit($key, 60); // 60 ثانیه محدودیت

                    Log::warning('Invalid verification code attempt', [
                        'identifier' => $identifier,
                        'attempts' => RateLimiter::attempts($key)
                    ]);
                    return back()->withErrors(['verification_code' => 'کد تأیید نامعتبر است یا منقضی شده است.']);
                }

                // پاک کردن محدودیت‌ها در صورت تأیید موفق
                RateLimiter::clear($key);

                // اگر کاربر وجود ندارد، یک کاربر جدید ایجاد کنیم
                if (!$user) {
                    // ایجاد آرایه با مقادیر پیش‌فرض
                    $userData = [
                        'name' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // اضافه کردن فیلد متناسب با نوع شناسه
                    if ($identifierType === 'email') {
                        $userData['email'] = $identifier;
                        $userData['email_verified_at'] = now(); // ایمیل تأیید شده است
                        $userData['phone'] = null;
                    } else { // phone
                        $userData['phone'] = $identifier;
                        $userData['phone_verified_at'] = now(); // شماره موبایل تأیید شده است
                        $userData['email'] = null;
                    }

                    // ایجاد کاربر جدید
                    $user = User::create($userData);

                    Log::info("Created new user", [
                        'user_id' => $user->id,
                        'identifier_type' => $identifierType,
                        'identifier' => $identifier
                    ]);
                } else {
                    // بروزرسانی فیلد تأیید شده
                    if ($identifierType === 'email' && !$user->email_verified_at) {
                        $user->email_verified_at = now();
                        $user->save();
                    } elseif ($identifierType === 'phone' && !$user->phone_verified_at) {
                        $user->phone_verified_at = now();
                        $user->save();
                    }

                    Log::info("Existing user authenticated with code", [
                        'user_id' => $user->id
                    ]);
                }
            }

            // ورود کاربر
            Auth::login($user, $request->has('remember_me'));
            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'remember_me' => $request->has('remember_me')
            ]);

            // پاکسازی session
            session()->forget('auth_data');

            // ریدایرکت به صفحه تنظیم رمز عبور اگر کاربر رمز عبور ندارد
            if (!$hasPassword) {
                return redirect()->route('password.set')->with('success', 'شما با موفقیت وارد شدید. لطفاً یک رمز عبور برای حساب کاربری خود تنظیم کنید.');
            }

            // ریدایرکت به صفحه مورد نظر
            $redirectTo = $authData['redirect_to'] ?? route('home');

            return redirect()->intended($redirectTo)->with('success', 'شما با موفقیت وارد شدید.');

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error in verify method', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'خطا در فرآیند ورود. لطفاً دوباره تلاش کنید.');
        }
    }
    /**
     * API برای تأیید هویت
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyApi(Request $request)
    {
        try {
            // دریافت اطلاعات از session
            $authData = $this->getAuthSessionData();

            if (!$authData) {
                return response()->json([
                    'success' => false,
                    'message' => 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.'
                ], 400);
            }

            $identifier = $authData['auth_identifier'];
            $identifierType = $authData['auth_identifier_type'];
            $userExists = $authData['user_exists'];
            $hasPassword = $authData['has_password'];
            $sessionToken = $authData['session_token'];

            // تأیید توکن جلسه برای API
            if ($request->session_token !== $sessionToken) {
                Log::warning('API CSRF attempt detected', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'درخواست نامعتبر است. لطفاً صفحه را بارگذاری مجدد کنید.'
                ], 403);
            }

            // بررسی روش تأیید (رمز عبور یا کد تأیید)
            $verifyMethod = $request->verify_method ?? 'code';

            // اعمال محدودیت درخواست‌ها (rate limiting)
            $key = 'api_verify_'.$identifierType.'_'.sha1($identifier);

            if (RateLimiter::tooManyAttempts($key, 10)) { // محدودیت 10 درخواست
                $seconds = RateLimiter::availableIn($key);

                return response()->json([
                    'success' => false,
                    'message' => "شما بیش از حد مجاز درخواست داده‌اید. لطفاً {$seconds} ثانیه دیگر مجدداً تلاش کنید.",
                    'wait_seconds' => $seconds
                ], 429);
            }

            RateLimiter::hit($key, 60); // محدودیت 60 ثانیه

            // پیدا کردن کاربر
            $user = User::where($identifierType, $identifier)->first();

            if ($verifyMethod === 'password' && $user && $hasPassword) {
                // اعتبارسنجی رمز عبور
                $request->validate([
                    'password' => 'required|string',
                ], [
                    'password.required' => 'لطفاً رمز عبور خود را وارد کنید.',
                ]);

                // اعمال محدودیت تلاش‌های ناموفق برای رمز عبور
                $pwKey = 'api_password_'.$identifierType.'_'.sha1($identifier);

                if (RateLimiter::tooManyAttempts($pwKey, 5)) { // 5 تلاش ناموفق
                    $seconds = RateLimiter::availableIn($pwKey);

                    return response()->json([
                        'success' => false,
                        'message' => "به دلیل تلاش‌های ناموفق زیاد، حساب کاربری شما به مدت {$seconds} ثانیه قفل شده است.",
                        'wait_seconds' => $seconds
                    ], 429);
                }

                // بررسی رمز عبور
                if (!Hash::check($request->password, $user->password)) {
                    RateLimiter::hit($pwKey, 300); // 300 ثانیه محدودیت

                    return response()->json([
                        'success' => false,
                        'message' => 'رمز عبور اشتباه است.'
                    ], 400);
                }

                // پاک کردن محدودیت‌ها در صورت ورود موفق
                RateLimiter::clear($pwKey);

            } else { // verify_method === 'code' یا کاربر جدید است
                // اعتبارسنجی کد تأیید
                $request->validate([
                    'verification_code' => 'required|string|size:6',
                ], [
                    'verification_code.required' => 'لطفاً کد تأیید را وارد کنید.',
                    'verification_code.size' => 'کد تأیید باید 6 رقم باشد.',
                ]);

                // اعمال محدودیت تلاش‌های ناموفق
                $codeKey = 'api_code_attempts_'.$identifierType.'_'.sha1($identifier);

                if (RateLimiter::tooManyAttempts($codeKey, 10)) { // 10 تلاش ناموفق
                    $seconds = RateLimiter::availableIn($codeKey);

                    return response()->json([
                        'success' => false,
                        'message' => "به دلیل تلاش‌های ناموفق زیاد، لطفاً {$seconds} ثانیه دیگر مجدداً تلاش کنید.",
                        'wait_seconds' => $seconds
                    ], 429);
                }

                // بررسی کد تأیید
                $verificationCode = $request->verification_code;

                // بررسی کد در دیتابیس
                $isValid = VerificationCode::validate($identifier, $verificationCode, $identifierType);

                // در محیط توسعه، برای آزمایش کد رمز ثابت 123456 را هم قبول می‌کنیم
                if (!$isValid && app()->environment('local', 'development') && $verificationCode === '123456') {
                    $isValid = true;
                }

                if (!$isValid) {
                    RateLimiter::hit($codeKey, 60); // 60 ثانیه محدودیت

                    return response()->json([
                        'success' => false,
                        'message' => 'کد تأیید نامعتبر است یا منقضی شده است.'
                    ], 400);
                }

                // پاک کردن محدودیت‌ها در صورت تأیید موفق
                RateLimiter::clear($codeKey);

                // اگر کاربر وجود ندارد، یک کاربر جدید ایجاد کنیم
                if (!$user) {
                    // ایجاد آرایه با مقادیر پیش‌فرض
                    $userData = [
                        'name' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // اضافه کردن فیلد متناسب با نوع شناسه
                    if ($identifierType === 'email') {
                        $userData['email'] = $identifier;
                        $userData['email_verified_at'] = now(); // ایمیل تأیید شده است
                        $userData['phone'] = null;
                    } else { // phone
                        $userData['phone'] = $identifier;
                        $userData['phone_verified_at'] = now(); // شماره موبایل تأیید شده است
                        $userData['email'] = null;
                    }

                    // ایجاد کاربر جدید
                    $user = User::create($userData);
                } else {
                    // بروزرسانی فیلد تأیید شده
                    if ($identifierType === 'email' && !$user->email_verified_at) {
                        $user->email_verified_at = now();
                        $user->save();
                    } elseif ($identifierType === 'phone' && !$user->phone_verified_at) {
                        $user->phone_verified_at = now();
                        $user->save();
                    }
                }
            }

            // ورود کاربر
            Auth::login($user, $request->has('remember_me'));

            // تولید توکن API (برای مصرف در برنامه‌های موبایل)
            $token = null;
            if ($request->has('create_api_token') && $request->create_api_token) {
                // ایجاد توکن API با Sanctum اگر نصب باشد
                if (class_exists('\Laravel\Sanctum\HasApiTokens')) {
                    // حذف توکن‌های قبلی با همین نام
                    $user->tokens()->where('name', 'mobile-app')->delete();

                    // ایجاد توکن جدید
                    $token = $user->createToken('mobile-app', ['*'], now()->addMonth())->plainTextToken;
                }
            }

            // پاکسازی session
            session()->forget('auth_data');

            // ریدایرکت به صفحه مورد نظر
            $redirectTo = $authData['redirect_to'] ?? route('home');

            $responseData = [
                'success' => true,
                'message' => 'شما با موفقیت وارد بَلیان شدید.',
                'redirect_to' => $redirectTo,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'has_password' => !empty($user->password)
                ]
            ];

            // اضافه کردن توکن API به پاسخ اگر درخواست شده باشد
            if ($token) {
                $responseData['api_token'] = $token;
            }

            // افزودن اخطار برای تنظیم رمز عبور
            if (empty($user->password)) {
                $responseData['warnings'] = ['لطفاً برای امنیت بیشتر، یک رمز عبور برای حساب کاربری خود تنظیم کنید.'];
            }

            return response()->json($responseData);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in verifyApi: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطا در فرآیند ورود. لطفاً دوباره تلاش کنید.'
            ], 500);
        }
    }

    /**
     * نمایش فرم تنظیم رمز عبور
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showSetPasswordForm()
    {
        // فقط کاربران وارد شده‌ای که رمز عبور ندارند می‌توانند به این صفحه دسترسی داشته باشند
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'ابتدا باید وارد حساب کاربری خود شوید.');
        }

        $user = Auth::user();

        // اگر کاربر قبلاً رمز عبور دارد
        if (!empty($user->password)) {
            return redirect()->route('password.change')->with('info', 'شما قبلاً رمز عبور دارید. می‌توانید آن را تغییر دهید.');
        }

        return view('auth.set-password');
    }

    /**
     * تنظیم رمز عبور اولیه برای کاربر
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setPassword(Request $request)
    {
        // بررسی ورود کاربر
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'برای تنظیم رمز عبور باید وارد شوید.');
        }

        $user = Auth::user();

        // اگر کاربر قبلاً رمز عبور دارد
        if (!empty($user->password)) {
            return redirect()->route('password.change')->with('info', 'شما قبلاً رمز عبور دارید. می‌توانید آن را تغییر دهید.');
        }

        // اعتبارسنجی ورودی‌ها
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'وارد کردن رمز عبور الزامی است.',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد.',
            'password.confirmed' => 'تأیید رمز عبور با رمز عبور وارد شده مطابقت ندارد.',
        ]);

        // تنظیم رمز عبور
        $user->password = Hash::make($request->password);
        $user->save();

        Log::info('Password set for user', ['user_id' => $user->id]);

        return redirect()->route('home')->with('success', 'رمز عبور شما با موفقیت تنظیم شد.');
    }

    /**
     * نمایش فرم تغییر رمز عبور
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showChangePasswordForm()
    {
        // فقط کاربران وارد شده می‌توانند به این صفحه دسترسی داشته باشند
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'ابتدا باید وارد حساب کاربری خود شوید.');
        }

        return view('auth.change-password');
    }

    /**
     * تغییر رمز عبور کاربر
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        // بررسی ورود کاربر
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'برای تغییر رمز عبور باید وارد شوید.');
        }

        $user = Auth::user();

        // اعتبارسنجی ورودی‌ها
        $rules = [
            'password' => 'required|string|min:8|confirmed',
        ];

        // اگر کاربر قبلاً رمز عبور داشته باشد، رمز عبور فعلی را هم بررسی می‌کنیم
        if (!empty($user->password)) {
            $rules['current_password'] = 'required|string';
        }

        $messages = [
            'current_password.required' => 'وارد کردن رمز عبور فعلی الزامی است.',
            'password.required' => 'وارد کردن رمز عبور جدید الزامی است.',
            'password.min' => 'رمز عبور جدید باید حداقل 8 کاراکتر باشد.',
            'password.confirmed' => 'تأیید رمز عبور جدید با رمز عبور وارد شده مطابقت ندارد.',
        ];

        $request->validate($rules, $messages);

        // بررسی رمز عبور فعلی
        if (!empty($user->password) && !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'رمز عبور فعلی اشتباه است.']);
        }

        // تنظیم رمز عبور جدید
        $user->password = Hash::make($request->password);
        $user->save();

        Log::info('Password changed for user', ['user_id' => $user->id]);

        return redirect()->route('profile')->with('success', 'رمز عبور شما با موفقیت تغییر یافت.');
    }

    /**
     * خروج کاربر
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // ثبت رویداد خروج
        if (Auth::check()) {
            Log::info('User logged out', ['user_id' => Auth::id()]);
        }

        // خروج کاربر
        Auth::logout();

        // نامعتبر کردن نشست
        $request->session()->invalidate();

        // بازسازی توکن نشست
        $request->session()->regenerateToken();

        // تنظیم کوکی‌های امن
        $this->setSecureCookieSettings();

        return redirect()->route('login')->with('success', 'شما با موفقیت از بَلیان خارج شدید.');
    }

    /**
     * تنظیم کوکی‌های امن
     */
    private function setSecureCookieSettings()
    {
        // تنظیم کوکی‌های امن برای جلوگیری از حملات XSS و CSRF
        config([
            'session.secure' => true,
            'session.http_only' => true,
            'session.same_site' => 'lax'
        ]);
    }

    /**
     * احراز هویت دو مرحله‌ای - فعال‌سازی
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable2FA(Request $request)
    {
        // این متد برای پیاده‌سازی آینده احراز هویت دو مرحله‌ای است
        // می‌توانید از کتابخانه‌های Google Authenticator یا پیامک استفاده کنید
        return redirect()->route('profile')->with('info', 'احراز هویت دو مرحله‌ای در حال حاضر در دسترس نیست.');
    }
}
