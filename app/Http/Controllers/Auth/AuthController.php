<?php
//
//namespace App\Http\Controllers\Auth;
//
//use App\Http\Controllers\Controller;
//use App\Models\User;
//use App\Models\VerificationCode;
//use App\Traits\SendsVerificationCodes;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\RateLimiter;
//use Illuminate\Validation\ValidationException;
//use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Http;
//use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\DB;
//
//class AuthController extends Controller
//{
//    use SendsVerificationCodes;
//
//    /**
//     * نمایش فرم ورود
//     */
//    public function showLoginForm()
//    {
//        return view('auth.login');
//    }
//
//    /**
//     * پردازش مرحله اول ورود (شناسایی کاربر)
//     */
//    public function identify(Request $request)
//    {
//        Log::info('Login identify request', [
//            'has_recaptcha' => $request->has('g-recaptcha-response'),
//            'login_method' => $request->input('login_method'),
//            'phone' => $request->input('phone'),
//            'email' => $request->input('email'),
//        ]);
//
//        // اعتبارسنجی اولیه براساس روش انتخاب شده
//        $validator = Validator::make($request->all(), [
//            'login_method' => 'required|in:phone,email',
//            'email' => 'required_if:login_method,email|email|nullable',
//            'phone' => 'required_if:login_method,phone|regex:/^09\d{9}$/|nullable',
//            'g-recaptcha-response' => 'nullable', // reCAPTCHA اختیاری شد
//        ], [
//            'email.required_if' => 'لطفاً ایمیل خود را وارد کنید.',
//            'email.email' => 'ایمیل وارد شده معتبر نیست.',
//            'phone.required_if' => 'لطفاً شماره موبایل خود را وارد کنید.',
//            'phone.regex' => 'شماره موبایل وارد شده معتبر نیست.',
//        ]);
//
//        if ($validator->fails()) {
//            Log::warning('Validation failed', [
//                'errors' => $validator->errors()->toArray()
//            ]);
//            return redirect()->back()
//                ->withErrors($validator)
//                ->withInput();
//        }
//
//        // بررسی reCAPTCHA به صورت ساده‌تر
//        $recaptchaValid = true;
//
//        if ($request->has('g-recaptcha-response') && !empty($request->input('g-recaptcha-response'))) {
//            try {
//                $response = Http::timeout(3)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
//                    'secret' => env('RECAPTCHA_SECRET_KEY'),
//                    'response' => $request->input('g-recaptcha-response'),
//                    'remoteip' => $request->ip(),
//                ]);
//
//                $responseData = $response->json();
//                Log::info('reCAPTCHA response', [
//                    'success' => $responseData['success'] ?? false,
//                    'score' => $responseData['score'] ?? null
//                ]);
//
//                // فقط در صورت امتیاز بسیار پایین، جلوگیری می‌کنیم
//                if (isset($responseData['success']) && $responseData['success'] === true) {
//                    if (isset($responseData['score']) && $responseData['score'] < 0.3) {
//                        $recaptchaValid = false;
//                    }
//                }
//            } catch (\Exception $e) {
//                Log::error('reCAPTCHA error', ['error' => $e->getMessage()]);
//                // حتی در صورت خطا، اجازه ادامه می‌دهیم
//            }
//        }
//
//        if (!$recaptchaValid) {
//            return back()->with('error', 'تأیید reCAPTCHA ناموفق بود. لطفاً دوباره تلاش کنید.');
//        }
//
//        // تعیین نوع شناسه و مقدار آن براساس روش انتخاب شده
//        $loginMethod = $request->input('login_method');
//
//        if ($loginMethod === 'phone') {
//            $identifier = $request->phone;
//            $identifierType = 'phone';
//        } else {
//            $identifier = $request->email;
//            $identifierType = 'email';
//        }
//
//        // بررسی وجود کاربر
//        $user = User::where($identifierType, $identifier)->first();
//
//        $sessionData = [
//            'auth_identifier' => $identifier,
//            'auth_identifier_type' => $identifierType,
//            'redirect_to' => $request->redirect_to ?? null,
//            'user_exists' => $user ? true : false,
//            'has_password' => $user && !empty($user->password) ? true : false,
//            'session_token' => Str::random(40),
//            'created_at' => now(),
//        ];
//
//        // ذخیره اطلاعات در جلسه
//        try {
//            session()->put('auth_data', encrypt($sessionData));
//            Log::info('Session data stored successfully', [
//                'identifier' => $identifier,
//                'type' => $identifierType,
//                'user_exists' => $sessionData['user_exists'],
//                'has_password' => $sessionData['has_password']
//            ]);
//        } catch (\Exception $e) {
//            Log::error('Session store error', ['error' => $e->getMessage()]);
//            return redirect()->back()->with('error', 'خطایی رخ داد. لطفاً دوباره تلاش کنید.');
//        }
//
//        return redirect()->route('auth.verify-form');
//    }
//
//    /**
//     * دریافت اطلاعات احراز هویت از session
//     *
//     * @return array|null
//     */
//    private function getAuthSessionData()
//    {
//        if (!session()->has('auth_data')) {
//            return null;
//        }
//
//        try {
//            return decrypt(session('auth_data'));
//        } catch (\Exception $e) {
//            Log::error('Failed to decrypt auth session data: ' . $e->getMessage());
//            return null;
//        }
//    }
//
//    /**
//     * نمایش فرم تأیید هویت
//     *
//     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
//     */
//    public function showVerificationForm()
//    {
//        Log::info('===== showVerificationForm started =====');
//
//        // بررسی وجود شناسه در سشن
//        $authData = $this->getAuthSessionData();
//
//        Log::info('Auth data retrieval result', [
//            'has_auth_data' => session()->has('auth_data'),
//            'auth_data_retrieved' => $authData ? true : false
//        ]);
//
//        if (!$authData) {
//            Log::warning('No auth data found in session');
//            return redirect()->route('login')
//                ->with('error', 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.');
//        }
//
//        $identifier = $authData['auth_identifier'];
//        $identifierType = $authData['auth_identifier_type'];
//        $userExists = $authData['user_exists'];
//        $hasPassword = $authData['has_password'];
//        $sessionToken = $authData['session_token'];
//
//        Log::info('Auth data details', [
//            'identifier_type' => $identifierType,
//            'identifier' => $identifier,
//            'user_exists' => $userExists,
//            'has_password' => $hasPassword
//        ]);
//
//        // پیدا کردن آخرین کد تأیید معتبر
//        $verificationCode = null;
//        $codeExpired = false;
//
//        try {
//            $lastCode = VerificationCode::where('identifier', $identifier)
//                ->where('type', $identifierType)
//                ->where('used', false)
//                ->orderBy('created_at', 'desc')
//                ->first();
//
//            Log::info('Last verification code search result', [
//                'found' => $lastCode ? true : false
//            ]);
//
//            if ($lastCode) {
//                // بررسی انقضای کد
//                if ($lastCode->expires_at->gt(now())) {
//                    $verificationCode = $lastCode;
//                    Log::info("Found existing valid verification code", [
//                        'code_id' => $lastCode->id,
//                        'expires_at' => $lastCode->expires_at->format('Y-m-d H:i:s'),
//                        'remaining_seconds' => $lastCode->getRemainingTime()
//                    ]);
//                } else {
//                    $codeExpired = true;
//                    Log::info("Found expired verification code", [
//                        'code_id' => $lastCode->id,
//                        'expired_at' => $lastCode->expires_at->format('Y-m-d H:i:s')
//                    ]);
//                }
//            } else {
//                Log::info("No verification code found for this identifier");
//            }
//        } catch (\Exception $e) {
//            Log::error("Error finding verification code", [
//                'error' => $e->getMessage(),
//                'trace' => $e->getTraceAsString()
//            ]);
//        }
//
//        // اگر کاربر جدید است یا رمز عبور ندارد و کد معتبری وجود ندارد، به صورت خودکار کد ارسال کنیم
//        Log::info('Checking if code should be sent automatically', [
//            'user_exists' => $userExists,
//            'has_password' => $hasPassword,
//            'has_valid_code' => $verificationCode ? true : false,
//            'code_expired' => $codeExpired
//        ]);
//
//        if ((!$userExists || !$hasPassword) && !$verificationCode && !$codeExpired) {
//            Log::info('Attempting to send verification code automatically');
//
//            try {
//                // بررسی محدودیت ارسال کد
//                $canSend = VerificationCode::canSendNew($identifier, $identifierType);
//                Log::info('Can send new code check result', [
//                    'can_send' => $canSend === true ? 'yes' : 'wait ' . $canSend . ' seconds'
//                ]);
//
//                if ($canSend === true) {
//                    // ایجاد کد تأیید - این خط کلیدی است
//                    Log::info('About to generate verification code');
//
//                    try {
//                        // ثبت دیتابیس را به طور مستقیم آزمایش کنیم
//                        DB::beginTransaction();
//
//                        $verificationCode = VerificationCode::generateFor($identifier, $identifierType);
//
//                        Log::info('Verification code generated successfully', [
//                            'code_id' => $verificationCode->id,
//                            'code' => $verificationCode->code, // در محیط واقعی حذف کنید
//                            'expires_at' => $verificationCode->expires_at->format('Y-m-d H:i:s')
//                        ]);
//
//                        DB::commit();
//                    } catch (\Exception $e) {
//                        DB::rollBack();
//                        Log::error('Database error generating verification code', [
//                            'error' => $e->getMessage(),
//                            'trace' => $e->getTraceAsString()
//                        ]);
//                        throw $e; // دوباره پرتاب می‌کنیم تا توسط catch بیرونی مدیریت شود
//                    }
//
//                    // واقعاً کد را ارسال می‌کنیم
//                    $sendResult = $this->sendCodeToUser($identifier, $identifierType, $verificationCode->code);
//                    Log::info('Code sending result', ['success' => $sendResult]);
//
//                    // پیام موفقیت
//                    $message = $identifierType === 'email'
//                        ? 'کد تأیید به ایمیل شما ارسال شد.'
//                        : 'کد تأیید به شماره موبایل شما ارسال شد.';
//
//                    session()->flash('success', $message);
//                } else {
//                    // اگر محدودیت زمانی وجود دارد
//                    $message = "برای ارسال مجدد کد باید {$canSend} ثانیه صبر کنید.";
//                    session()->flash('info', $message);
//                }
//            } catch (\Exception $e) {
//                Log::error("Error sending verification code: " . $e->getMessage(), [
//                    'trace' => $e->getTraceAsString()
//                ]);
//                session()->flash('error', 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.');
//            }
//        }
//
//        // اگر کاربر وجود دارد، اطلاعات تماس او را دریافت کنیم
//        $contactInfo = null;
//        if ($userExists) {
//            try {
//                $user = User::where($identifierType, $identifier)->first();
//                $contactInfo = [
//                    'has_phone' => !empty($user->phone),
//                    'has_email' => !empty($user->email),
//                    'phone' => $user->phone ? substr_replace($user->phone, '***', 4, 4) : null,
//                    'email' => $user->email ? $this->maskEmail($user->email) : null,
//                ];
//            } catch (\Exception $e) {
//                Log::error("Error retrieving user contact info: " . $e->getMessage());
//            }
//        }
//
//        Log::info('Rendering verification form view', [
//            'has_verification_code' => $verificationCode ? true : false,
//            'code_expired' => $codeExpired
//        ]);
//
//        return view('auth.verify', [
//            'identifier' => $this->maskIdentifier($identifier, $identifierType),
//            'identifierType' => $identifierType,
//            'userExists' => $userExists,
//            'hasPassword' => $hasPassword,
//            'contactInfo' => $contactInfo,
//            'verificationCode' => $verificationCode, // فقط وجود یا عدم وجود آن
//            'codeExpired' => $codeExpired,
//            'expiresAt' => $verificationCode ? $verificationCode->expires_at->timestamp * 1000 : null,
//            'waitTime' => $verificationCode ? null : VerificationCode::canSendNew($identifier, $identifierType),
//            'session_token' => $sessionToken, // برای تأیید CSRF در درخواست‌های AJAX
//            // در محیط توسعه، شبیه‌سازی ارسال کد
//            'dev_code' => app()->environment('local', 'development') ? $verificationCode->code ?? null : null
//        ]);
//    }
//
//    /**
//     * عدم نمایش بخشی از ایمیل
//     *
//     * @param string $email
//     * @return string
//     */
//    private function maskEmail($email)
//    {
//        if (!$email) return null;
//
//        $parts = explode('@', $email);
//        if (count($parts) != 2) return $email;
//
//        $name = $parts[0];
//        $domain = $parts[1];
//
//        $maskLength = max(2, min(strlen($name) - 2, 5));
//        $visibleChars = min(3, strlen($name));
//
//        $maskedName = substr($name, 0, $visibleChars) .
//            str_repeat('*', $maskLength) .
//            (strlen($name) > $visibleChars + $maskLength ? substr($name, -2) : '');
//
//        return $maskedName . '@' . $domain;
//    }
//
//    /**
//     * عدم نمایش بخشی از شناسه (ایمیل یا شماره تلفن)
//     *
//     * @param string $identifier
//     * @param string $type
//     * @return string
//     */
//    private function maskIdentifier($identifier, $type)
//    {
//        if ($type === 'email') {
//            return $this->maskEmail($identifier);
//        } else {
//            // پنهان کردن بخشی از شماره موبایل
//            return substr_replace($identifier, '***', 4, 4);
//        }
//    }
//
////    /**
////     * پنهان کردن بخشی از ایمیل
////     *
////     * @param string $email
////     * @return string
////     */
////    private function maskEmail($email)
////    {
////        if (!$email) return null;
////
////        $parts = explode('@', $email);
////        if (count($parts) != 2) return $email;
////
////        $name = $parts[0];
////        $domain = $parts[1];
////
////        $maskLength = max(2, min(strlen($name) - 2, 5));
////        $visibleChars = min(3, strlen($name));
////
////        $maskedName = substr($name, 0, $visibleChars) .
////            str_repeat('*', $maskLength) .
////            (strlen($name) > $visibleChars + $maskLength ? substr($name, -2) : '');
////
////        return $maskedName . '@' . $domain;
////    }
////
////    /**
////     * پنهان کردن بخشی از شناسه (ایمیل یا شماره تلفن)
////     *
////     * @param string $identifier
////     * @param string $type
////     * @return string
////     */
////    private function maskIdentifier($identifier, $type)
////    {
////        if ($type === 'email') {
////            return $this->maskEmail($identifier);
////        } else {
////            // پنهان کردن بخشی از شماره موبایل
////            return substr_replace($identifier, '***', 4, 4);
////        }
////    }
////
////    /**
////     * ارسال واقعی کد تأیید به کاربر
////     *
////     * @param string $identifier
////     * @param string $type
////     * @param string $code
////     * @return bool
////     */
////    private function sendCodeToUser($identifier, $type, $code)
////    {
////        try {
////            if ($type === 'email') {
////                // ارسال کد از طریق ایمیل
////                $emailService = new EmailService();
////                $result = $emailService->sendVerificationCode($identifier, $code);
////
////                if (!$result) {
////                    Log::error("Failed to send verification email to: {$identifier}");
////                    return false;
////                }
////
////                Log::info("Verification code sent to email: {$identifier}");
////            } else {
////                // ارسال کد از طریق پیامک
////                $smsService = new SMSService();
////                $result = $smsService->sendVerificationCode($identifier, $code);
////
////                if (!$result) {
////                    Log::error("Failed to send verification SMS to: {$identifier}");
////                    return false;
////                }
////
////                Log::info("Verification code sent to phone: {$identifier}");
////            }
////
////            return true;
////        } catch (\Exception $e) {
////            Log::error("Error sending verification code: " . $e->getMessage(), [
////                'identifier' => $identifier,
////                'type' => $type
////            ]);
////            return false;
////        }
////    }
////
////    /**
////     * ارسال کد تأیید
////     *
////     * @param Request $request
////     * @return \Illuminate\Http\JsonResponse
////     */
////    public function sendVerificationCode(Request $request)
////    {
////        try {
////            // دریافت اطلاعات از session
////            $authData = $this->getAuthSessionData();
////
////            if (!$authData) {
////                return response()->json([
////                    'success' => false,
////                    'message' => 'اطلاعات نشست معتبر نیست. لطفاً دوباره وارد شوید.'
////                ], 400);
////            }
////
////            // تأیید توکن جلسه برای جلوگیری از CSRF
////            if ($request->session_token !== $authData['session_token']) {
////                Log::warning('CSRF attempt detected in sendVerificationCode', [
////                    'ip' => $request->ip(),
////                    'user_agent' => $request->userAgent()
////                ]);
////
////                return response()->json([
////                    'success' => false,
////                    'message' => 'درخواست نامعتبر است. لطفاً صفحه را بارگذاری مجدد کنید.'
////                ], 403);
////            }
////
////            $identifier = $authData['auth_identifier'];
////            $identifierType = $authData['auth_identifier_type'];
////
////            // اعمال محدودیت برای درخواست‌های متعدد (rate limiting)
////            $key = 'send_code_'.$identifierType.'_'.$identifier;
////
////            if (RateLimiter::tooManyAttempts($key, 5)) { // محدودیت 5 درخواست
////                $seconds = RateLimiter::availableIn($key);
////
////                return response()->json([
////                    'success' => false,
////                    'message' => "شما بیش از حد مجاز درخواست داده‌اید. لطفاً {$seconds} ثانیه دیگر مجدداً تلاش کنید.",
////                    'wait_seconds' => $seconds
////                ], 429);
////            }
////
////            RateLimiter::hit($key, 120); // محدودیت 120 ثانیه
////
////            // بررسی محدودیت ارسال کد
////            $canSend = VerificationCode::canSendNew($identifier, $identifierType);
////            if ($canSend !== true) {
////                return response()->json([
////                    'success' => false,
////                    'message' => "لطفاً {$canSend} ثانیه دیگر مجدداً تلاش کنید.",
////                    'wait_seconds' => $canSend
////                ], 429);
////            }
////
////            // ایجاد کد تأیید جدید
////            $verificationCode = VerificationCode::generateFor($identifier, $identifierType);
////
////            // ارسال کد تأیید به کاربر
////            $sent = $this->sendCodeToUser($identifier, $identifierType, $verificationCode->code);
////
////            if (!$sent) {
////                return response()->json([
////                    'success' => false,
////                    'message' => 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.'
////                ], 500);
////            }
////
////            return response()->json([
////                'success' => true,
////                'message' => 'کد تأیید با موفقیت ارسال شد',
////                'expires_in' => $verificationCode->getRemainingTime(),
////                'expires_at' => $verificationCode->expires_at->timestamp * 1000, // تبدیل به میلی‌ثانیه برای جاوااسکریپت
////                'dev_code' => app()->environment('local', 'development') ? $verificationCode->code : null
////            ]);
////        } catch (\Exception $e) {
////            Log::error('Error in sendVerificationCode: ' . $e->getMessage(), [
////                'exception' => $e
////            ]);
////
////            return response()->json([
////                'success' => false,
////                'message' => 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.'
////            ], 500);
////        }
////    }
////
////    /**
////     * تأیید هویت کاربر
////     *
////     * @param Request $request
////     * @return \Illuminate\Http\RedirectResponse
////     */
////    public function verify(Request $request)
////    {
////        try {
////            // دریافت اطلاعات از session
////            $authData = $this->getAuthSessionData();
////
////            if (!$authData) {
////                Log::warning('Missing session data in verify method');
////                return redirect()->route('login')->with('error', 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.');
////            }
////
////            $identifier = $authData['auth_identifier'];
////            $identifierType = $authData['auth_identifier_type'];
////            $userExists = $authData['user_exists'];
////            $hasPassword = $authData['has_password'];
////            $sessionToken = $authData['session_token'];
////            $redirectTo = $authData['redirect_to'] ?? route('home');
////
////            // تأیید توکن CSRF
////            if (!$request->has('_token') || !$request->session()->token() || !hash_equals($request->session()->token(), $request->_token)) {
////                Log::warning('CSRF token validation failed', [
////                    'ip' => $request->ip(),
////                    'user_agent' => $request->userAgent()
////                ]);
////
////                return redirect()->route('login')->with('error', 'درخواست نامعتبر است. لطفاً دوباره وارد شوید.');
////            }
////
////            // بررسی روش تأیید (رمز عبور یا کد تأیید)
////            $verifyMethod = $request->verify_method ?? 'code';
////            Log::info('Verification method: ' . $verifyMethod);
////
////            // پیدا کردن کاربر
////            $user = User::where($identifierType, $identifier)->first();
////
////            if ($verifyMethod === 'password' && $user && $hasPassword) {
////                // اعتبارسنجی رمز عبور
////                $request->validate([
////                    'password' => 'required|string',
////                ], [
////                    'password.required' => 'لطفاً رمز عبور خود را وارد کنید.',
////                ]);
////
////                // اعمال محدودیت تلاش‌های ناموفق
////                $key = 'login_attempts_'.$identifierType.'_'.sha1($identifier);
////
////                if (RateLimiter::tooManyAttempts($key, 5)) { // 5 تلاش ناموفق
////                    $seconds = RateLimiter::availableIn($key);
////
////                    return back()->withErrors([
////                        'password' => "به دلیل تلاش‌های ناموفق زیاد، حساب کاربری شما به مدت {$seconds} ثانیه قفل شده است."
////                    ]);
////                }
////
////                // بررسی رمز عبور
////                if (!Hash::check($request->password, $user->password)) {
////                    RateLimiter::hit($key, 300); // 300 ثانیه محدودیت
////
////                    Log::info('Invalid password for user', ['identifier' => $identifier]);
////                    return back()->withErrors(['password' => 'رمز عبور اشتباه است.']);
////                }
////
////                // پاک کردن محدودیت‌ها در صورت ورود موفق
////                RateLimiter::clear($key);
////
////                Log::info('User authenticated with password', ['user_id' => $user->id]);
////
////            } else { // verify_method === 'code' یا کاربر جدید است
////                // اعتبارسنجی کد تأیید
////                $request->validate([
////                    'verification_code' => 'required|string|size:6',
////                ], [
////                    'verification_code.required' => 'لطفاً کد تأیید را وارد کنید.',
////                    'verification_code.size' => 'کد تأیید باید 6 رقم باشد.',
////                ]);
////
////                // اعمال محدودیت تلاش‌های ناموفق
////                $key = 'verify_code_attempts_'.$identifierType.'_'.sha1($identifier);
////
////                if (RateLimiter::tooManyAttempts($key, 10)) { // 10 تلاش ناموفق
////                    $seconds = RateLimiter::availableIn($key);
////
////                    return back()->withErrors([
////                        'verification_code' => "به دلیل تلاش‌های ناموفق زیاد، لطفاً {$seconds} ثانیه دیگر مجدداً تلاش کنید."
////                    ]);
////                }
////
////                // بررسی کد تأیید
////                $verificationCode = $request->verification_code;
////                Log::info('Checking verification code', [
////                    'identifier' => $identifier,
////                    'identifier_type' => $identifierType
////                ]);
////
////                // بررسی کد در دیتابیس
////                $isValid = VerificationCode::validate($identifier, $verificationCode, $identifierType);
////
////                // حذف کد تست ثابت در همه محیط‌ها - امنیت بیشتر
////
////                if (!$isValid) {
////                    // افزایش زمان محدودیت با هر تلاش ناموفق
////                    $attempts = RateLimiter::attempts($key) + 1;
////                    $lockSeconds = min(60 * $attempts, 3600); // از 1 دقیقه تا 1 ساعت
////
////                    RateLimiter::hit($key, $lockSeconds);
////
////                    Log::warning('Invalid verification code attempt', [
////                        'identifier' => $identifier,
////                        'attempts' => RateLimiter::attempts($key),
////                        'lock_seconds' => $lockSeconds
////                    ]);
////
////                    return back()->withErrors(['verification_code' => 'کد تأیید نامعتبر است یا منقضی شده است.']);
////                }
////
////                // پاک کردن محدودیت‌ها در صورت تأیید موفق
////                RateLimiter::clear($key);
////
////                // اگر کاربر وجود ندارد، یک کاربر جدید ایجاد کنیم
////                if (!$user) {
////                    // ایجاد آرایه با مقادیر پیش‌فرض
////                    $userData = [
////                        'name' => null,
////                        'created_at' => now(),
////                        'updated_at' => now(),
////                    ];
////
////                    // اضافه کردن فیلد متناسب با نوع شناسه
////                    if ($identifierType === 'email') {
////                        $userData['email'] = $identifier;
////                        $userData['email_verified_at'] = now(); // ایمیل تأیید شده است
////                        $userData['phone'] = null;
////                    } else { // phone
////                        $userData['phone'] = $identifier;
////                        $userData['phone_verified_at'] = now(); // شماره موبایل تأیید شده است
////                        $userData['email'] = null;
////                    }
////
////                    try {
////                        // استفاده از تراکنش برای اطمینان از ایجاد کامل کاربر
////                        DB::beginTransaction();
////
////                        // ایجاد کاربر جدید
////                        $user = User::create($userData);
////
////                        DB::commit();
////
////                        Log::info("Created new user", [
////                            'user_id' => $user->id,
////                            'identifier_type' => $identifierType,
////                            'identifier' => $identifier
////                        ]);
////                    } catch (\Exception $e) {
////                        DB::rollBack();
////                        Log::error("Error creating new user: " . $e->getMessage(), [
////                            'identifier' => $identifier,
////                            'identifier_type' => $identifierType
////                        ]);
////
////                        return back()->with('error', 'خطا در ایجاد حساب کاربری. لطفاً دوباره تلاش کنید.');
////                    }
////                } else {
////                    // بروزرسانی فیلد تأیید شده
////                    if ($identifierType === 'email' && !$user->email_verified_at) {
////                        $user->email_verified_at = now();
////                        $user->save();
////                    } elseif ($identifierType === 'phone' && !$user->phone_verified_at) {
////                        $user->phone_verified_at = now();
////                        $user->save();
////                    }
////
////                    Log::info("Existing user authenticated with code", [
////                        'user_id' => $user->id
////                    ]);
////                }
////            }
////
////            // ورود کاربر با محدودیت زمانی برای نشست
////            $remember = $request->has('remember_me');
////            Auth::login($user, $remember);
////
////            // تنظیم طول عمر نشست برای امنیت بیشتر
////            if (!$remember) {
////                // اگر گزینه "مرا به خاطر بسپار" انتخاب نشده، نشست را محدود کنیم
////                $request->session()->put('auth.password_confirmed_at', time());
////
////                // نشست به مدت 2 ساعت معتبر باشد
////                config(['session.lifetime' => 120]);
////            }
////
////            Log::info('User logged in successfully', [
////                'user_id' => $user->id,
////                'remember_me' => $remember,
////                'ip' => $request->ip()
////            ]);
////
////            // پاکسازی session
////            session()->forget('auth_data');
////
////            // ریدایرکت به صفحه تنظیم رمز عبور اگر کاربر رمز عبور ندارد
////            if (!$hasPassword) {
////                return redirect()->route('password.set')->with('success', 'شما با موفقیت وارد شدید. لطفاً یک رمز عبور برای حساب کاربری خود تنظیم کنید.');
////            }
////
////            // بررسی اینکه آدرس redirect_to معتبر است و به دامنه دیگری ارجاع نمی‌دهد
////            $redirectUrl = url($redirectTo);
////            $currentHost = parse_url(url('/'), PHP_URL_HOST);
////            $redirectHost = parse_url($redirectUrl, PHP_URL_HOST);
////
////            if ($redirectHost !== $currentHost) {
////                Log::warning('Invalid redirect attempt', [
////                    'user_id' => $user->id,
////                    'redirect_url' => $redirectUrl
////                ]);
////                $redirectTo = route('home');
////            }
////
////            return redirect()->intended($redirectTo)->with('success', 'شما با موفقیت وارد شدید.');
////
////        } catch (ValidationException $e) {
////            throw $e;
////        } catch (\Exception $e) {
////            Log::error('Error in verify method', [
////                'message' => $e->getMessage(),
////                'trace' => $e->getTraceAsString(),
////                'request_data' => $request->all()
////            ]);
////
////            return back()->with('error', 'خطا در فرآیند ورود. لطفاً دوباره تلاش کنید.');
////        }
////    }
////
////    /**
////     * API برای تأیید هویت
////     *
////     * @param Request $request
////     * @return \Illuminate\Http\JsonResponse
////     */
////    public function verifyApi(Request $request)
////    {
////        try {
////            // دریافت اطلاعات از session
////            $authData = $this->getAuthSessionData();
////
////            if (!$authData) {
////                return response()->json([
////                    'success' => false,
////                    'message' => 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.'
////                ], 400);
////            }
////
////            $identifier = $authData['auth_identifier'];
////            $identifierType = $authData['auth_identifier_type'];
////            $userExists = $authData['user_exists'];
////            $hasPassword = $authData['has_password'];
////            $sessionToken = $authData['session_token'];
////            $redirectTo = $authData['redirect_to'] ?? route('home');
////
////            // تأیید توکن جلسه برای API
////            if ($request->session_token !== $sessionToken) {
////                Log::warning('API CSRF attempt detected', [
////                    'ip' => $request->ip(),
////                    'user_agent' => $request->userAgent()
////                ]);
////
////                return response()->json([
////                    'success' => false,
////                    'message' => 'درخواست نامعتبر است. لطفاً صفحه را بارگذاری مجدد کنید.'
////                ], 403);
////            }
////
////            // بررسی روش تأیید (رمز عبور یا کد تأیید)
////            $verifyMethod = $request->verify_method ?? 'code';
////
////            // اعمال محدودیت درخواست‌ها (rate limiting) - محدودیت جامع‌تر
////            $ipKey = 'api_verify_ip_'.$request->ip();
////
////            // محدودیت درخواست‌ها براساس IP (جلوگیری از حملات گسترده)
////            if (RateLimiter::tooManyAttempts($ipKey, 30)) { // 30 درخواست در هر ساعت
////                $seconds = RateLimiter::availableIn($ipKey);
////
////                return response()->json([
////                    'success' => false,
////                    'message' => "تعداد درخواست‌ها بیش از حد مجاز است. لطفاً {$seconds} ثانیه دیگر مجدداً تلاش کنید.",
////                    'wait_seconds' => $seconds
////                ], 429);
////            }
////
////            // افزایش شمارنده محدودیت درخواست با توجه به IP
////            RateLimiter::hit($ipKey, 3600); // 1 ساعت محدودیت
////
////            // محدودیت اختصاصی براساس شناسه کاربر
////            $key = 'api_verify_'.$identifierType.'_'.sha1($identifier);
////
////            if (RateLimiter::tooManyAttempts($key, 10)) { // محدودیت 10 درخواست
////                $seconds = RateLimiter::availableIn($key);
////
////                return response()->json([
////                    'success' => false,
////                    'message' => "شما بیش از حد مجاز درخواست داده‌اید. لطفاً {$seconds} ثانیه دیگر مجدداً تلاش کنید.",
////                    'wait_seconds' => $seconds
////                ], 429);
////            }
////
////            // افزایش زمان محدودیت با هر تلاش
////            $attempts = RateLimiter::attempts($key) + 1;
////            $lockSeconds = min(60 * $attempts, 1800); // از 1 دقیقه تا 30 دقیقه
////            RateLimiter::hit($key, $lockSeconds);
////
////            // پیدا کردن کاربر
////            $user = User::where($identifierType, $identifier)->first();
////
////            if ($verifyMethod === 'password' && $user && $hasPassword) {
////                // اعتبارسنجی رمز عبور
////                $request->validate([
////                    'password' => 'required|string',
////                ], [
////                    'password.required' => 'لطفاً رمز عبور خود را وارد کنید.',
////                ]);
////
////                // اعمال محدودیت تلاش‌های ناموفق برای رمز عبور
////                $pwKey = 'api_password_'.$identifierType.'_'.sha1($identifier);
////
////                if (RateLimiter::tooManyAttempts($pwKey, 5)) { // 5 تلاش ناموفق
////                    $seconds = RateLimiter::availableIn($pwKey);
////
////                    return response()->json([
////                        'success' => false,
////                        'message' => "به دلیل تلاش‌های ناموفق زیاد، حساب کاربری شما به مدت {$seconds} ثانیه قفل شده است.",
////                        'wait_seconds' => $seconds
////                    ], 429);
////                }
////
////                // بررسی رمز عبور
////                if (!Hash::check($request->password, $user->password)) {
////                    // افزایش زمان محدودیت با هر تلاش ناموفق
////                    $attempts = RateLimiter::attempts($pwKey) + 1;
////                    $lockSeconds = min(300 * $attempts, 7200); // از 5 دقیقه تا 2 ساعت
////
////                    RateLimiter::hit($pwKey, $lockSeconds);
////
////                    return response()->json([
////                        'success' => false,
////                        'message' => 'رمز عبور اشتباه است.'
////                    ], 400);
////                }
////
////                // پاک کردن محدودیت‌ها در صورت ورود موفق
////                RateLimiter::clear($pwKey);
////
////            } else { // verify_method === 'code' یا کاربر جدید است
////                // اعتبارسنجی کد تأیید
////                $request->validate([
////                    'verification_code' => 'required|string|size:6',
////                ], [
////                    'verification_code.required' => 'لطفاً کد تأیید را وارد کنید.',
////                    'verification_code.size' => 'کد تأیید باید 6 رقم باشد.',
////                ]);
////
////                // اعمال محدودیت تلاش‌های ناموفق
////                $codeKey = 'api_code_attempts_'.$identifierType.'_'.sha1($identifier);
////
////                if (RateLimiter::tooManyAttempts($codeKey, 10)) { // 10 تلاش ناموفق
////                    $seconds = RateLimiter::availableIn($codeKey);
////
////                    return response()->json([
////                        'success' => false,
////                        'message' => "به دلیل تلاش‌های ناموفق زیاد، لطفاً {$seconds} ثانیه دیگر مجدداً تلاش کنید.",
////                        'wait_seconds' => $seconds
////                    ], 429);
////                }
////
////                // بررسی کد تأیید
////                $verificationCode = $request->verification_code;
////
////                // بررسی کد در دیتابیس
////                $isValid = VerificationCode::validate($identifier, $verificationCode, $identifierType);
////
////                // حذف کد تست ثابت در همه محیط‌ها - امنیت بیشتر
////
////                if (!$isValid) {
////                    // افزایش زمان محدودیت با هر تلاش ناموفق
////                    $attempts = RateLimiter::attempts($codeKey) + 1;
////                    $lockSeconds = min(60 * $attempts, 3600); // از 1 دقیقه تا 1 ساعت
////
////                    RateLimiter::hit($codeKey, $lockSeconds);
////
////                    return response()->json([
////                        'success' => false,
////                        'message' => 'کد تأیید نامعتبر است یا منقضی شده است.'
////                    ], 400);
////                }
////
////                // پاک کردن محدودیت‌ها در صورت تأیید موفق
////                RateLimiter::clear($codeKey);
////
////                // اگر کاربر وجود ندارد، یک کاربر جدید ایجاد کنیم
////                if (!$user) {
////                    // ایجاد آرایه با مقادیر پیش‌فرض
////                    $userData = [
////                        'name' => null,
////                        'created_at' => now(),
////                        'updated_at' => now(),
////                    ];
////
////                    // اضافه کردن فیلد متناسب با نوع شناسه
////                    if ($identifierType === 'email') {
////                        $userData['email'] = $identifier;
////                        $userData['email_verified_at'] = now(); // ایمیل تأیید شده است
////                        $userData['phone'] = null;
////                    } else { // phone
////                        $userData['phone'] = $identifier;
////                        $userData['phone_verified_at'] = now(); // شماره موبایل تأیید شده است
////                        $userData['email'] = null;
////                    }
////
////                    try {
////                        // استفاده از تراکنش برای اطمینان از ایجاد کامل کاربر
////                        DB::beginTransaction();
////
////                        // ایجاد کاربر جدید
////                        $user = User::create($userData);
////
////                        DB::commit();
////                    } catch (\Exception $e) {
////                        DB::rollBack();
////                        Log::error("Error creating new user in API: " . $e->getMessage(), [
////                            'identifier' => $identifier,
////                            'identifier_type' => $identifierType
////                        ]);
////
////                        return response()->json([
////                            'success' => false,
////                            'message' => 'خطا در ایجاد حساب کاربری. لطفاً دوباره تلاش کنید.'
////                        ], 500);
////                    }
////                } else {
////                    // بروزرسانی فیلد تأیید شده
////                    if ($identifierType === 'email' && !$user->email_verified_at) {
////                        $user->email_verified_at = now();
////                        $user->save();
////                    } elseif ($identifierType === 'phone' && !$user->phone_verified_at) {
////                        $user->phone_verified_at = now();
////                        $user->save();
////                    }
////                }
////            }
////
////            // ورود کاربر
////            $remember = $request->has('remember_me');
////            Auth::login($user, $remember);
////
////            // تولید توکن API (برای مصرف در برنامه‌های موبایل)
////            $token = null;
////            if ($request->has('create_api_token') && $request->create_api_token) {
////                // ایجاد توکن API با Sanctum اگر نصب باشد
////                if (class_exists('\Laravel\Sanctum\HasApiTokens')) {
////                    // حذف توکن‌های قبلی با همین نام
////                    $user->tokens()->where('name', 'mobile-app')->delete();
////
////                    // ایجاد توکن جدید با دسترسی محدود و مدت اعتبار محدود
////                    $permissions = [
////                        'read-profile',
////                        'update-profile'
////                        // فقط دسترسی‌های ضروری
////                    ];
////
////                    // محدود کردن زمان توکن به یک هفته بجای یک ماه
////                    $token = $user->createToken('mobile-app', $permissions, now()->addWeek())->plainTextToken;
////
////                    Log::info('New API token created', [
////                        'user_id' => $user->id,
////                        'token_type' => 'mobile-app',
////                        'expires_at' => now()->addWeek()->toDateTimeString()
////                    ]);
////                }
////            }
////
////            // پاکسازی session
////            session()->forget('auth_data');
////
////            // بررسی اینکه آدرس redirect_to معتبر است و به دامنه دیگری ارجاع نمی‌دهد
////            $redirectUrl = url($redirectTo);
////            $currentHost = parse_url(url('/'), PHP_URL_HOST);
////            $redirectHost = parse_url($redirectUrl, PHP_URL_HOST);
////
////            if ($redirectHost !== $currentHost) {
////                Log::warning('Invalid redirect attempt in API', [
////                    'user_id' => $user->id,
////                    'redirect_url' => $redirectUrl
////                ]);
////                $redirectTo = route('home');
////            }
////
////            $responseData = [
////                'success' => true,
////                'message' => 'شما با موفقیت وارد شدید.',
////                'redirect_to' => $redirectTo,
////                'user' => [
////                    'id' => $user->id,
////                    'name' => $user->name,
////                    'email' => $user->email,
////                    'phone' => $user->phone,
////                    'has_password' => !empty($user->password)
////                ]
////            ];
////
////            // اضافه کردن توکن API به پاسخ اگر درخواست شده باشد
////            if ($token) {
////                $responseData['api_token'] = $token;
////
////                // افزودن زمان انقضای توکن
////                $responseData['token_expires_at'] = now()->addWeek()->timestamp;
////            }
////
////            // افزودن اخطار برای تنظیم رمز عبور
////            if (empty($user->password)) {
////                $responseData['warnings'] = ['لطفاً برای امنیت بیشتر، یک رمز عبور برای حساب کاربری خود تنظیم کنید.'];
////            }
////
////            return response()->json($responseData);
////
////        } catch (ValidationException $e) {
////            return response()->json([
////                'success' => false,
////                'message' => $e->validator->errors()->first(),
////                'errors' => $e->validator->errors()
////            ], 422);
////        } catch (\Exception $e) {
////            Log::error('Error in verifyApi: ' . $e->getMessage(), [
////                'exception' => $e
////            ]);
////
////            return response()->json([
////                'success' => false,
////                'message' => 'خطا در فرآیند ورود. لطفاً دوباره تلاش کنید.'
////            ], 500);
////        }
////    }
////
////    /**
////     * نمایش فرم تنظیم رمز عبور
////     *
////     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
////     */
////    public function showSetPasswordForm()
////    {
////        // فقط کاربران وارد شده‌ای که رمز عبور ندارند می‌توانند به این صفحه دسترسی داشته باشند
////        if (!Auth::check()) {
////            return redirect()->route('login')->with('info', 'ابتدا باید وارد حساب کاربری خود شوید.');
////        }
////
////        $user = Auth::user();
////
////        // اگر کاربر قبلاً رمز عبور دارد
////        if (!empty($user->password)) {
////            return redirect()->route('password.change')->with('info', 'شما قبلاً رمز عبور دارید. می‌توانید آن را تغییر دهید.');
////        }
////
////        return view('auth.set-password');
////    }
////
////    /**
////     * تنظیم رمز عبور اولیه برای کاربر
////     *
////     * @param Request $request
////     * @return \Illuminate\Http\RedirectResponse
////     */
////    public function setPassword(Request $request)
////    {
////        // بررسی ورود کاربر
////        if (!Auth::check()) {
////            return redirect()->route('login')->with('error', 'برای تنظیم رمز عبور باید وارد شوید.');
////        }
////
////        $user = Auth::user();
////
////        // اگر کاربر قبلاً رمز عبور دارد
////        if (!empty($user->password)) {
////            return redirect()->route('password.change')->with('info', 'شما قبلاً رمز عبور دارید. می‌توانید آن را تغییر دهید.');
////        }
////
////        // اعتبارسنجی ورودی‌ها
////        $request->validate([
////            'password' => 'required|string|min:8|confirmed',
////        ], [
////            'password.required' => 'وارد کردن رمز عبور الزامی است.',
////            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد.',
////            'password.confirmed' => 'تأیید رمز عبور با رمز عبور وارد شده مطابقت ندارد.',
////        ]);
////
////        // تنظیم رمز عبور
////        $user->password = Hash::make($request->password);
////        $user->save();
////
////        Log::info('Password set for user', ['user_id' => $user->id]);
////
////        return redirect()->route('home')->with('success', 'رمز عبور شما با موفقیت تنظیم شد.');
////    }
////
////    /**
////     * نمایش فرم تغییر رمز عبور
////     *
////     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
////     */
////    public function showChangePasswordForm()
////    {
////        // فقط کاربران وارد شده می‌توانند به این صفحه دسترسی داشته باشند
////        if (!Auth::check()) {
////            return redirect()->route('login')->with('info', 'ابتدا باید وارد حساب کاربری خود شوید.');
////        }
////
////        return view('auth.change-password');
////    }
////
////    /**
////     * تغییر رمز عبور کاربر
////     *
////     * @param Request $request
////     * @return \Illuminate\Http\RedirectResponse
////     */
////    public function changePassword(Request $request)
////    {
////        // بررسی ورود کاربر
////        if (!Auth::check()) {
////            return redirect()->route('login')->with('error', 'برای تغییر رمز عبور باید وارد شوید.');
////        }
////
////        $user = Auth::user();
////
////        // اعتبارسنجی ورودی‌ها
////        $rules = [
////            'password' => 'required|string|min:8|confirmed',
////        ];
////
////        // اگر کاربر قبلاً رمز عبور داشته باشد، رمز عبور فعلی را هم بررسی می‌کنیم
////        if (!empty($user->password)) {
////            $rules['current_password'] = 'required|string';
////        }
////
////        $messages = [
////            'current_password.required' => 'وارد کردن رمز عبور فعلی الزامی است.',
////            'password.required' => 'وارد کردن رمز عبور جدید الزامی است.',
////            'password.min' => 'رمز عبور جدید باید حداقل 8 کاراکتر باشد.',
////            'password.confirmed' => 'تأیید رمز عبور جدید با رمز عبور وارد شده مطابقت ندارد.',
////        ];
////
////        $request->validate($rules, $messages);
////
////        // بررسی رمز عبور فعلی
////        if (!empty($user->password) && !Hash::check($request->current_password, $user->password)) {
////            return back()->withErrors(['current_password' => 'رمز عبور فعلی اشتباه است.']);
////        }
////
////        // تنظیم رمز عبور جدید
////        $user->password = Hash::make($request->password);
////        $user->save();
////
////        Log::info('Password changed for user', ['user_id' => $user->id]);
////
////        return redirect()->route('profile')->with('success', 'رمز عبور شما با موفقیت تغییر یافت.');
////    }
////
////    /**
////     * خروج کاربر
////     *
////     * @param Request $request
////     * @return \Illuminate\Http\RedirectResponse
////     */
////    public function logout(Request $request)
////    {
////        // ثبت رویداد خروج
////        if (Auth::check()) {
////            Log::info('User logged out', ['user_id' => Auth::id()]);
////        }
////
////        // خروج کاربر
////        Auth::logout();
////
////        // نامعتبر کردن نشست
////        $request->session()->invalidate();
////
////        // بازسازی توکن نشست
////        $request->session()->regenerateToken();
////
////        // تنظیم کوکی‌های امن
////        $this->setSecureCookieSettings();
////
////        return redirect()->route('login')->with('success', 'شما با موفقیت از بَلیان خارج شدید.');
////    }
////
////    /**
////     * تنظیم کوکی‌های امن
////     */
////    private function setSecureCookieSettings()
////    {
////        // تنظیم کوکی‌های امن برای جلوگیری از حملات XSS و CSRF
////        config([
////            'session.secure' => true,
////            'session.http_only' => true,
////            'session.same_site' => 'lax'
////        ]);
////    }
////
////    /**
////     * احراز هویت دو مرحله‌ای - فعال‌سازی
////     *
////     * @param Request $request
////     * @return \Illuminate\Http\RedirectResponse
////     */
////    public function enable2FA(Request $request)
////    {
////        // این متد برای پیاده‌سازی آینده احراز هویت دو مرحله‌ای است
////        // می‌توانید از کتابخانه‌های Google Authenticator یا پیامک استفاده کنید
////        return redirect()->route('profile')->with('info', 'احراز هویت دو مرحله‌ای در حال حاضر در دسترس نیست.');
////    }
//}
