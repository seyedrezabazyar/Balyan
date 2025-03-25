<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use App\Traits\AuthSessionTrait;
use App\Traits\SendsVerificationCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    use SendsVerificationCodes, AuthSessionTrait;

    protected const VERIFICATION_CODE_LENGTH = 6;
    protected const VERIFICATION_CODE_EXPIRY_MINUTES = 1;
    protected const RESEND_COOLDOWN_SECONDS = 60;
    protected const MAX_DAILY_CODES_PER_IDENTIFIER = 10;
    protected const SHOULD_MASK_CODES_IN_LOGS = true;
    protected const AUTO_SEND_CODE_FOR_NEW_USERS = true;
    protected const DEV_MODE_SHOW_CODES = true;

    /**
     * نمایش فرم تأیید هویت
     */
    public function showVerificationForm(Request $request)
    {
        Log::info('===== showVerificationForm started =====');

        $authData = $this->getAuthSessionData();

        Log::info('Auth data retrieval result', [
            'has_auth_data' => session()->has('auth_data'),
            'auth_data_retrieved' => $authData ? true : false
        ]);

        if (!$authData) {
            Log::warning('No auth data found in session');
            return redirect()->route('login')
                ->with('error', 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.');
        }

        $identifier = $authData['auth_identifier'];
        $identifierType = $authData['auth_identifier_type'];
        $userExists = $authData['user_exists'];
        $hasPassword = $authData['has_password'];
        $sessionToken = $authData['session_token'];

        Log::info('Auth data details', [
            'identifier_type' => $identifierType,
            'identifier' => $identifier,
            'user_exists' => $userExists,
            'has_password' => $hasPassword
        ]);

        $verificationCode = null;
        $codeExpired = false;

        try {
            $lastCode = VerificationCode::where('identifier', $identifier)
                ->where('type', $identifierType)
                ->where('used', false)
                ->orderBy('created_at', 'desc')
                ->first();

            Log::info('Last verification code search result', [
                'found' => $lastCode ? true : false
            ]);

            if ($lastCode) {
                if ($lastCode->expires_at->gt(now())) {
                    $verificationCode = $lastCode;
                    Log::info("Found existing valid verification code", [
                        'code_id' => $lastCode->id,
                        'expires_at' => $lastCode->expires_at->format('Y-m-d H:i:s'),
                        'remaining_seconds' => $lastCode->getRemainingTime()
                    ]);

                    if (app()->environment('local', 'development') && self::DEV_MODE_SHOW_CODES) {
                        Log::info("Development mode - verification code", [
                            'code' => $lastCode->code
                        ]);
                    }
                } else {
                    $codeExpired = true;
                    Log::info("Found expired verification code", [
                        'code_id' => $lastCode->id,
                        'expired_at' => $lastCode->expires_at->format('Y-m-d H:i:s')
                    ]);
                }
            } else {
                Log::info("No verification code found for this identifier");
            }
        } catch (\Exception $e) {
            Log::error("Error finding verification code", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        $comingFromIdentify = $request->session()->pull('coming_from_identify', false);
        Log::info('Coming from identify page?', ['status' => $comingFromIdentify ? 'yes' : 'no']);

        $shouldSendNewCode = false;

        if (((!$userExists || !$hasPassword) && !$verificationCode && !$codeExpired) && self::AUTO_SEND_CODE_FOR_NEW_USERS) {
            $shouldSendNewCode = true;
            Log::info('Should send code: New user or no password and no code');
        }
        else if ($comingFromIdentify && $codeExpired) {
            $shouldSendNewCode = true;
            Log::info('Should send code: Coming from identify page and code expired');
        }
        else if ($comingFromIdentify && !$verificationCode && !$codeExpired) {
            $shouldSendNewCode = true;
            Log::info('Should send code: Coming from identify page and no code');
        }

        Log::info('Final decision on sending code', [
            'shouldSendNewCode' => $shouldSendNewCode,
            'user_exists' => $userExists,
            'has_password' => $hasPassword,
            'has_valid_code' => $verificationCode ? true : false,
            'code_expired' => $codeExpired,
            'coming_from_identify' => $comingFromIdentify
        ]);

        if ($shouldSendNewCode) {
            Log::info('Attempting to send verification code automatically');

            try {
                $canSend = VerificationCode::canSendNew($identifier, $identifierType, self::RESEND_COOLDOWN_SECONDS);
                Log::info('Can send new code check result', [
                    'can_send' => $canSend === true ? 'yes' : 'wait ' . $canSend . ' seconds'
                ]);

                if ($canSend === true) {
                    Log::info('About to generate verification code');

                    try {
                        DB::beginTransaction();

                        $verificationCode = VerificationCode::generateFor(
                            $identifier,
                            $identifierType,
                            self::VERIFICATION_CODE_LENGTH,
                            self::VERIFICATION_CODE_EXPIRY_MINUTES
                        );

                        if (!self::SHOULD_MASK_CODES_IN_LOGS || (app()->environment('local', 'development') && self::DEV_MODE_SHOW_CODES)) {
                            Log::info('Verification code generated successfully', [
                                'code_id' => $verificationCode->id,
                                'code' => $verificationCode->code,
                                'expires_at' => $verificationCode->expires_at->format('Y-m-d H:i:s')
                            ]);
                        } else {
                            Log::info('Verification code generated successfully', [
                                'code_id' => $verificationCode->id,
                                'code' => '******',
                                'expires_at' => $verificationCode->expires_at->format('Y-m-d H:i:s')
                            ]);
                        }

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Database error generating verification code', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw $e;
                    }

                    $sendResult = $this->sendCodeToUser($identifier, $identifierType, $verificationCode->code);
                    Log::info('Code sending result', ['success' => $sendResult]);

                    $message = $identifierType === 'email'
                        ? 'کد تأیید به ایمیل شما ارسال شد.'
                        : 'کد تأیید به شماره موبایل شما ارسال شد.';

                    session()->flash('success', $message);
                    $codeExpired = false;
                } else {
                    $message = "برای ارسال مجدد کد باید {$canSend} ثانیه صبر کنید.";
                    session()->flash('info', $message);
                }
            } catch (\Exception $e) {
                Log::error("Error sending verification code: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                session()->flash('error', 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.');
            }
        }

        $contactInfo = null;
        if ($userExists) {
            try {
                $user = User::where($identifierType, $identifier)->first();
                $contactInfo = [
                    'has_phone' => !empty($user->phone),
                    'has_email' => !empty($user->email),
                    'phone' => $user->phone ? substr_replace($user->phone, '***', 4, 4) : null,
                    'email' => $user->email ? IdentifierController::maskEmail($user->email) : null,
                ];
            } catch (\Exception $e) {
                Log::error("Error retrieving user contact info: " . $e->getMessage());
            }
        }

        Log::info('Rendering verification form view', [
            'has_verification_code' => $verificationCode ? true : false,
            'code_expired' => $codeExpired
        ]);

        return view('auth.verify', [
            'identifier' => IdentifierController::maskIdentifier($identifier, $identifierType),
            'identifierType' => $identifierType,
            'userExists' => $userExists,
            'hasPassword' => $hasPassword,
            'contactInfo' => $contactInfo,
            'verificationCode' => $verificationCode,
            'codeExpired' => $codeExpired,
            'expiresAt' => $verificationCode ? $verificationCode->expires_at->timestamp * 1000 : null,
            'waitTime' => $verificationCode ? null : VerificationCode::canSendNew($identifier, $identifierType, self::RESEND_COOLDOWN_SECONDS),
            'session_token' => $sessionToken,
            'code_expiry_minutes' => self::VERIFICATION_CODE_EXPIRY_MINUTES,
            'dev_code' => (app()->environment('local', 'development') && self::DEV_MODE_SHOW_CODES) ? $verificationCode->code ?? null : null
        ]);
    }

    /**
     * ارسال کد تأیید
     */
    public function sendVerificationCode(Request $request)
    {
        Log::info('Send verification code request', $request->all());

        $authData = $this->getAuthSessionData();

        if (!$authData) {
            return response()->json([
                'success' => false,
                'message' => 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.'
            ], 401);
        }

        if ($request->filled('identifier_type') && $request->filled('identifier')) {
            $identifierType = $request->input('identifier_type');
            $identifier = $request->input('identifier');
        } else {
            $identifierType = $authData['auth_identifier_type'];
            $identifier = $authData['auth_identifier'];
        }

        $dailyCount = VerificationCode::where('identifier', $identifier)
            ->where('type', $identifierType)
            ->whereDate('created_at', today())
            ->count();

        if ($dailyCount >= self::MAX_DAILY_CODES_PER_IDENTIFIER) {
            return response()->json([
                'success' => false,
                'message' => "شما به حداکثر تعداد مجاز ارسال کد در روز رسیده‌اید. لطفاً فردا دوباره تلاش کنید.",
                'daily_limit_reached' => true,
                'wait_seconds' => 24 * 60 * 60,
                'status' => 429
            ], 429);
        }

        $canSend = VerificationCode::canSendNew($identifier, $identifierType, self::RESEND_COOLDOWN_SECONDS);

        if ($canSend !== true) {
            return response()->json([
                'success' => false,
                'message' => "به دلیل محدودیت ارسال پیامک، لطفاً {$canSend} ثانیه دیگر مجدداً تلاش کنید.",
                'wait_seconds' => $canSend,
                'status' => 429
            ], 429);
        }

        try {
            DB::beginTransaction();

            $verificationCode = VerificationCode::generateFor(
                $identifier,
                $identifierType,
                self::VERIFICATION_CODE_LENGTH,
                self::VERIFICATION_CODE_EXPIRY_MINUTES
            );

            $logData = [
                'code_id' => $verificationCode->id,
                'identifier' => $identifier,
                'identifier_type' => $identifierType,
                'expires_at' => $verificationCode->expires_at->format('Y-m-d H:i:s')
            ];

            if (!self::SHOULD_MASK_CODES_IN_LOGS || (app()->environment('local', 'development') && self::DEV_MODE_SHOW_CODES)) {
                $logData['code'] = $verificationCode->code;
            } else {
                $logData['code'] = '******';
            }

            Log::info('Verification code generated', $logData);

            DB::commit();

            $sendResult = $this->sendCodeToUser($identifier, $identifierType, $verificationCode->code);

            Log::info('Code sending result', ['success' => $sendResult]);

            if (!$sendResult) {
                throw new \Exception('خطا در ارسال کد تأیید');
            }

            return response()->json([
                'success' => true,
                'message' => $identifierType === 'email'
                    ? 'کد تأیید به ایمیل شما ارسال شد.'
                    : 'کد تأیید به شماره موبایل شما ارسال شد.',
                'expires_at' => $verificationCode->expires_at->timestamp * 1000,
                'code_expiry_minutes' => self::VERIFICATION_CODE_EXPIRY_MINUTES,
                'dev_code' => (app()->environment('local', 'development') && self::DEV_MODE_SHOW_CODES) ? $verificationCode->code : null
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error sending verification code: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'identifier' => $identifier,
                'identifier_type' => $identifierType
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.'
            ], 500);
        }
    }

    /**
     * ارسال مجدد کد تأیید
     */
    public function resendVerificationCode(Request $request)
    {
        Log::info('Resend verification code request', $request->except(['session_token']));

        $authData = $this->getAuthSessionData();

        if (!$authData) {
            return response()->json([
                'success' => false,
                'message' => 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.'
            ], 401);
        }

        if ($request->filled('identifier_type') && $request->filled('identifier')) {
            $identifierType = $request->input('identifier_type');
            $identifier = $request->input('identifier');
        } else {
            $identifier = $authData['auth_identifier'];
            $identifierType = $authData['auth_identifier_type'];
        }

        if ($request->session_token !== $authData['session_token']) {
            Log::warning('Invalid session token in resend request', [
                'provided_token' => substr($request->session_token ?? '', 0, 8) . '...',
                'expected_token' => substr($authData['session_token'] ?? '', 0, 8) . '...',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'درخواست نامعتبر است. لطفاً صفحه را بارگذاری مجدد کنید.'
            ], 403);
        }

        $dailyCount = VerificationCode::where('identifier', $identifier)
            ->where('type', $identifierType)
            ->whereDate('created_at', today())
            ->count();

        if ($dailyCount >= self::MAX_DAILY_CODES_PER_IDENTIFIER) {
            return response()->json([
                'success' => false,
                'message' => "شما به حداکثر تعداد مجاز ارسال کد در روز رسیده‌اید. لطفاً فردا دوباره تلاش کنید.",
                'daily_limit_reached' => true,
                'wait_seconds' => 24 * 60 * 60,
                'status' => 429
            ], 429);
        }

        $canSend = VerificationCode::canSendNew($identifier, $identifierType, self::RESEND_COOLDOWN_SECONDS);

        if ($canSend !== true) {
            return response()->json([
                'success' => false,
                'message' => "به دلیل محدودیت ارسال پیامک، لطفاً {$canSend} ثانیه دیگر مجدداً تلاش کنید.",
                'wait_seconds' => $canSend,
                'status' => 429
            ], 429);
        }

        try {
            DB::beginTransaction();

            $verificationCode = VerificationCode::generateFor(
                $identifier,
                $identifierType,
                self::VERIFICATION_CODE_LENGTH,
                self::VERIFICATION_CODE_EXPIRY_MINUTES
            );

            $logData = [
                'code_id' => $verificationCode->id,
                'identifier' => $identifier,
                'identifier_type' => $identifierType,
                'expires_at' => $verificationCode->expires_at->format('Y-m-d H:i:s')
            ];

            if (!self::SHOULD_MASK_CODES_IN_LOGS || (app()->environment('local', 'development') && self::DEV_MODE_SHOW_CODES)) {
                $logData['code'] = $verificationCode->code;
            } else {
                $logData['code'] = '******';
            }

            Log::info('Verification code resent', $logData);

            DB::commit();

            $sendResult = $this->sendCodeToUser($identifier, $identifierType, $verificationCode->code);

            if (!$sendResult) {
                throw new \Exception('خطا در ارسال کد تأیید');
            }

            return response()->json([
                'success' => true,
                'message' => $identifierType === 'email'
                    ? 'کد تأیید به ایمیل شما ارسال شد.'
                    : 'کد تأیید به شماره موبایل شما ارسال شد.',
                'expires_at' => $verificationCode->expires_at->timestamp * 1000,
                'code_expiry_minutes' => self::VERIFICATION_CODE_EXPIRY_MINUTES,
                'dev_code' => (app()->environment('local', 'development') && self::DEV_MODE_SHOW_CODES) ? $verificationCode->code : null
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error resending verification code: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'identifier' => $identifier,
                'identifier_type' => $identifierType
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.'
            ], 500);
        }
    }

    /**
     * بررسی و تأیید کد وارد شده
     */
    public function verifyCode(Request $request)
    {
        Log::info('===== verifyCode started =====', [
            'request_data' => $request->all()
        ]);

        $authData = $this->getAuthSessionData();

        if (!$authData) {
            Log::warning('No auth data found in session');
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.'
                ], 401);
            }

            return redirect()->route('login')
                ->with('error', 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.');
        }

        $identifier = $authData['auth_identifier'];
        $identifierType = $authData['auth_identifier_type'];

        Log::info('Auth data found', [
            'identifier' => $identifier,
            'identifier_type' => $identifierType
        ]);

        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|digits:'.self::VERIFICATION_CODE_LENGTH,
        ], [
            'verification_code.required' => 'لطفاً کد تأیید را وارد کنید.',
            'verification_code.digits' => 'کد تأیید باید '.self::VERIFICATION_CODE_LENGTH.' رقم باشد.',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        $verificationCode = $request->input('verification_code');

        Log::info('Processing verification code', [
            'code' => $verificationCode,
            'length' => strlen($verificationCode)
        ]);

        try {
            $isValid = VerificationCode::validate($identifier, $verificationCode, $identifierType);

            Log::info('Verification result', [
                'is_valid' => $isValid
            ]);

            if (!$isValid) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'کد تأیید وارد شده صحیح نیست یا منقضی شده است.'
                    ], 400);
                }

                return back()->withErrors([
                    'verification_code' => 'کد تأیید وارد شده صحیح نیست یا منقضی شده است.'
                ])->with('error', 'کد تأیید وارد شده صحیح نیست یا منقضی شده است.');
            }

            Log::info('Valid verification code. Processing user authentication');

            $user = User::where($identifierType, $identifier)->first();

            if (!$user) {
                Log::info('User not found. Creating new user');

                try {
                    DB::beginTransaction();

                    $now = new \DateTime('now', new \DateTimeZone('Asia/Tehran'));
                    $year = $now->format('y');
                    $month = $now->format('m');
                    $day = $now->format('d');
                    $hour = $now->format('H');
                    $minute = $now->format('i');
                    $second = $now->format('s');

                    $microseconds = substr(microtime(true) - floor(microtime(true)), 2, 3);

                    $username = $year . $month . $day . $hour . $minute . $second . $microseconds;

                    $user = new User();
                    $user->$identifierType = $identifier;
                    $user->username = $username;
                    $user->password = null;

                    $user->first_name = 'کاربر';
                    $user->last_name = 'جدید';
                    $user->display_name = 'کاربر جدید';
                    $user->is_active = true;

                    if ($identifierType === 'email') {
                        $user->email_verified_at = now();
                    } elseif ($identifierType === 'phone') {
                        $user->phone_verified_at = now();
                    }

                    $user->save();

                    Log::info('New user created successfully', [
                        'user_id' => $user->id,
                        $identifierType => $user->$identifierType,
                        'username' => $user->username
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error creating user', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'code' => $e->getCode(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);

                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'خطا در ایجاد حساب کاربری: ' . $e->getMessage()
                        ], 500);
                    }

                    return back()->with('error', 'خطا در ایجاد حساب کاربری: ' . $e->getMessage());
                }
            } else {
                Log::info('Existing user found', [
                    'user_id' => $user->id
                ]);

                if ($identifierType === 'email' && !$user->email_verified_at) {
                    $user->email_verified_at = now();
                    $user->save();
                    Log::info('Updated email verification status');
                } elseif ($identifierType === 'phone' && !$user->phone_verified_at) {
                    $user->phone_verified_at = now();
                    $user->save();
                    Log::info('Updated phone verification status');
                }
            }

            Auth::login($user, $request->filled('remember_me'));

            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'remember_me' => $request->filled('remember_me')
            ]);

            session()->forget('auth_data');

            $request->session()->regenerate();

            $redirectTo = $authData['redirect_to'] ?? '/dashboard';

            Log::info('Authentication completed. Redirecting', [
                'redirect_to' => $redirectTo
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ورود با موفقیت انجام شد.',
                    'redirect' => $redirectTo
                ]);
            }

            return redirect($redirectTo);
        } catch (\Exception $e) {
            Log::error('Error during verification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطای سیستمی: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'خطای سیستمی: ' . $e->getMessage());
        }
    }

    /**
     * ایجاد یوزرنیم استاندارد براساس نوع و مقدار شناسه
     */
    protected function generateStandardUsername($identifier, $identifierType)
    {
        if ($identifierType === 'email') {
            $username = explode('@', $identifier)[0];
            $username = preg_replace('/[^a-zA-Z0-9]/', '', $username);

            if (strlen($username) < 5) {
                $username .= rand(1000, 9999);
            }
        } else {
            $username = preg_replace('/[^0-9]/', '', $identifier);
            $username = substr($username, -8);
            $username = 'user_' . $username;
        }

        $originalUsername = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * ایجاد نام کاربری منحصر به فرد براساس شناسه کاربر
     */
    protected function generateUniqueUsername($identifier)
    {
        $baseUsername = preg_replace('/[^a-zA-Z0-9]/', '', $identifier);
        $baseUsername = strtolower(substr($baseUsername, 0, 10));

        $username = $baseUsername . '_' . substr(uniqid(), -5);

        $count = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . substr(uniqid(), -5) . $count;
            $count++;
        }

        return $username;
    }

    /**
     * دریافت تنظیمات کنترلر برای استفاده در کلاس‌های دیگر
     */
    public static function getSettings()
    {
        return [
            'code_length' => self::VERIFICATION_CODE_LENGTH,
            'code_expiry_minutes' => self::VERIFICATION_CODE_EXPIRY_MINUTES,
            'resend_cooldown_seconds' => self::RESEND_COOLDOWN_SECONDS,
            'max_daily_codes' => self::MAX_DAILY_CODES_PER_IDENTIFIER,
            'dev_mode_show_codes' => self::DEV_MODE_SHOW_CODES,
        ];
    }
}
