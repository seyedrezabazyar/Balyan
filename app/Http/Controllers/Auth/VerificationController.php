<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use App\Services\UsernameService;
use App\Services\ValidationMessagesProvider;
use App\Traits\AuthUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Services\AuthService;


class VerificationController extends Controller
{
    use AuthUtils;

    // تنظیمات پایه برای مدیریت کدهای تأیید
    public const VERIFICATION_CODE_LENGTH = 6;
    public const VERIFICATION_CODE_EXPIRY_MINUTES = 5;
    public const RESEND_COOLDOWN_SECONDS = 60;
    public const MAX_DAILY_CODES_PER_IDENTIFIER = 10;
    public const DEV_MODE_SHOW_CODES = true;

    /**
     * سرویس تولید نام کاربری
     */
    protected $usernameService;
    protected $authService;

    /**
     * سازنده کلاس
     */
    public function __construct(UsernameService $usernameService, AuthService $authService)
    {
        $this->usernameService = $usernameService;
        $this->authService = $authService;
    }

    /**
     * نمایش فرم تأیید هویت
     */
    public function showVerificationForm(Request $request)
    {
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

        $verificationCode = VerificationCode::where('identifier', $identifier)
            ->where('type', $identifierType)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        $codeExpired = VerificationCode::where('identifier', $identifier)
            ->where('type', $identifierType)
            ->where('used', false)
            ->where('expires_at', '<=', now())
            ->exists();

        $comingFromIdentify = $request->session()->pull('coming_from_identify', false);

        // بررسی نیاز به ارسال کد جدید
        $shouldSendNewCode = false;

        // کاربر جدید یا بدون رمز عبور
        if ((!$userExists || !$hasPassword) && !$verificationCode && !$codeExpired) {
            $shouldSendNewCode = true;
        }

        // کاربر از صفحه شناسایی آمده و کد ندارد یا کد منقضی دارد
        if ($comingFromIdentify && ($codeExpired || !$verificationCode)) {
            $shouldSendNewCode = true;
        }

        if ($shouldSendNewCode) {
            $canSend = VerificationCode::canSendNew($identifier, $identifierType, self::RESEND_COOLDOWN_SECONDS);

            if ($canSend === true) {
                DB::beginTransaction();

                try {
                    $verificationCode = VerificationCode::generateFor(
                        $identifier,
                        $identifierType,
                        self::VERIFICATION_CODE_LENGTH,
                        self::VERIFICATION_CODE_EXPIRY_MINUTES
                    );

                    $sendResult = VerificationCode::sendCode($identifier, $identifierType, $verificationCode->code);

                    DB::commit();

                    $codeExpired = false;

                    if ($sendResult) {
                        $message = $identifierType === 'email'
                            ? 'کد تأیید به ایمیل شما ارسال شد.'
                            : 'کد تأیید به شماره موبایل شما ارسال شد.';

                        session()->flash('success', $message);
                    } else {
                        session()->flash('error', 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.');
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("خطا در ارسال کد تأیید: " . $e->getMessage());
                    session()->flash('error', 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.');
                }
            } else {
                // تبدیل مقدار عددی یا آرایه به پیام مناسب
                if (is_array($canSend) && isset($canSend['message'])) {
                    $message = $canSend['message'];
                } else if (is_numeric($canSend)) {
                    $message = "برای ارسال مجدد کد باید {$canSend} ثانیه صبر کنید.";
                } else {
                    $message = "محدودیت ارسال کد وجود دارد. لطفاً بعداً دوباره تلاش کنید.";
                }

                session()->flash('info', $message);
            }
        }

        // اطلاعات تماس کاربر
        $contactInfo = null;
        if ($userExists) {
            $user = User::where($identifierType, $identifier)->first();
            if ($user) {
                $contactInfo = [
                    'has_phone' => !empty($user->phone),
                    'has_email' => !empty($user->email),
                    'phone' => $user->phone ? $this->maskIdentifier($user->phone, 'phone') : null,
                    'email' => $user->email ? $this->maskEmail($user->email) : null,
                ];
            }
        }

        return view('auth.verify', [
            'identifier' => $this->maskIdentifier($identifier, $identifierType),
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
     * ارسال مجدد کد تأیید
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendVerificationCode(Request $request)
    {
        $authData = $this->getAuthSessionData();

        if (!$authData) {
            return response()->json([
                'success' => false,
                'message' => 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.'
            ], 401);
        }

        $identifier = $authData['auth_identifier'];
        $identifierType = $authData['auth_identifier_type'];

        if ($request->session_token !== $authData['session_token']) {
            return response()->json([
                'success' => false,
                'message' => 'درخواست نامعتبر است. لطفاً صفحه را بارگذاری مجدد کنید.'
            ], 403);
        }

        // استفاده از سرویس برای پردازش درخواست کد تأیید
        $result = $this->authService->processVerificationRequest(
            $identifier,
            $identifierType,
            self::VERIFICATION_CODE_LENGTH,
            self::VERIFICATION_CODE_EXPIRY_MINUTES,
            self::RESEND_COOLDOWN_SECONDS,
            self::MAX_DAILY_CODES_PER_IDENTIFIER
        );

        // حذف کلیدهایی که در پاسخ API نیازی به آنها نیست
        return response()->json(
            array_diff_key($result, ['status' => null, 'verification_code' => null]),
            $result['status'] ?? 200
        );
    }

    /**
     * بررسی و تأیید کد وارد شده
     */
    /**
     * بررسی و تأیید کد وارد شده
     */
    public function verifyCode(Request $request)
    {
        $authData = $this->getAuthSessionData();

        if (!$authData) {
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

        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|digits:'.self::VERIFICATION_CODE_LENGTH,
        ], ValidationMessagesProvider::getAuthValidationMessages());

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        try {
            // استفاده از سرویس برای اعتبارسنجی کد و احراز هویت کاربر
            $result = $this->authService->verifyCodeAndAuthenticate(
                $identifier,
                $identifierType,
                $request->input('verification_code'),
                $request->filled('remember_me')
            );

            if (!$result['success']) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $result['message']
                    ], $result['status']);
                }

                return back()->withErrors([
                    'verification_code' => $result['message']
                ]);
            }

            // پاکسازی نشست احراز هویت
            session()->forget('auth_data');
            $request->session()->regenerate();

            $redirectTo = $authData['redirect_to'] ?? '/profile';

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ورود با موفقیت انجام شد.',
                    'redirect' => $redirectTo
                ]);
            }

            return redirect($redirectTo);

        } catch (\Exception $e) {
            Log::error('خطا در فرایند تأیید', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
     * تأیید مجدد ایمیل یا شماره تلفن کاربر
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier_type' => 'required|in:email,phone',
            'identifier' => 'required|string',
        ], ValidationMessagesProvider::getAuthValidationMessages());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $identifierType = $request->input('identifier_type');
        $identifier = $request->input('identifier');

        $existingUser = User::where($identifierType, $identifier)->first();

        if ($existingUser && $existingUser->id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => $identifierType === 'email'
                    ? 'این ایمیل قبلاً توسط کاربر دیگری ثبت شده است.'
                    : 'این شماره تلفن قبلاً توسط کاربر دیگری ثبت شده است.'
            ], 400);
        }

        // اطلاعاتی که می‌خواهیم در نشست ذخیره کنیم
        $sessionData = [
            'auth_identifier' => $identifier,
            'auth_identifier_type' => $identifierType,
            'user_exists' => $existingUser ? true : false,
            'has_password' => $existingUser && !empty($existingUser->password) ? true : false,
            'session_token' => Str::random(40),
            'created_at' => now(),
        ];

        // استفاده از سرویس برای پردازش درخواست کد تأیید
        $result = $this->authService->processVerificationRequest(
            $identifier,
            $identifierType,
            self::VERIFICATION_CODE_LENGTH,
            self::VERIFICATION_CODE_EXPIRY_MINUTES,
            self::RESEND_COOLDOWN_SECONDS,
            self::MAX_DAILY_CODES_PER_IDENTIFIER,
            true,
            $sessionData,
            'verification_data'
        );

        // حذف کلیدهایی که در پاسخ API نیازی به آنها نیست
        return response()->json(
            array_diff_key($result, ['status' => null, 'verification_code' => null]),
            $result['status'] ?? 200
        );
    }

    /**
     * تأیید شناسه جدید کاربر (ایمیل یا تلفن) و به‌روزرسانی پروفایل
     */
    public function verifyNewIdentifier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|digits:'.self::VERIFICATION_CODE_LENGTH,
        ], ValidationMessagesProvider::getAuthValidationMessages());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            if (!session()->has('verification_data')) {
                return response()->json([
                    'success' => false,
                    'message' => 'اطلاعات تأیید در سشن موجود نیست. لطفاً دوباره تلاش کنید.'
                ], 400);
            }

            $sessionData = decrypt(session()->get('verification_data'));

            $identifier = $sessionData['auth_identifier'];
            $identifierType = $sessionData['auth_identifier_type'];
            $verificationCode = $request->input('verification_code');

            $isValid = VerificationCode::validate($identifier, $verificationCode, $identifierType);

            if (!$isValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'کد تأیید وارد شده صحیح نیست یا منقضی شده است.'
                ], 400);
            }

            $user = auth()->user();

            if ($identifierType === 'email') {
                $user->email = $identifier;
                $user->email_verified_at = now();
            } else {
                $user->phone = $identifier;
                $user->phone_verified_at = now();
            }

            $user->save();
            session()->forget('verification_data');

            return response()->json([
                'success' => true,
                'message' => $identifierType === 'email'
                    ? 'ایمیل جدید شما با موفقیت تأیید و ثبت شد.'
                    : 'شماره موبایل جدید شما با موفقیت تأیید و ثبت شد.',
                'redirect' => route('profile.account-info')
            ]);
        } catch (\Exception $e) {
            Log::error("خطا در تأیید شناسه جدید: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'خطای سیستمی: ' . $e->getMessage()
            ], 500);
        }
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
