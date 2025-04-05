<?php

namespace App\Services;

use App\Models\User;
use App\Models\VerificationCode;
use App\Traits\AuthUtils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthService
{
    use AuthUtils;

    protected $usernameService;

    public function __construct(UsernameService $usernameService)
    {
        $this->usernameService = $usernameService;
    }

    /**
     * ذخیره اطلاعات احراز هویت در نشست
     *
     * @param string $identifier
     * @param string $identifierType
     * @param string|null $redirectTo
     * @return array
     */
    public function storeAuthSession(string $identifier, string $identifierType, ?string $redirectTo = null): array
    {
        $user = User::where($identifierType, $identifier)->first();

        $sessionData = [
            'auth_identifier' => $identifier,
            'auth_identifier_type' => $identifierType,
            'redirect_to' => $redirectTo,
            'user_exists' => $user ? true : false,
            'has_password' => $user && !empty($user->password) ? true : false,
            'session_token' => Str::random(40),
            'created_at' => now(),
        ];

        session()->put('auth_data', encrypt($sessionData));

        Log::info('اطلاعات نشست احراز هویت ذخیره شد', [
            'identifier_type' => $identifierType,
            'user_exists' => $sessionData['user_exists'],
            'has_password' => $sessionData['has_password']
        ]);

        return $sessionData;
    }

    /**
     * ارسال کد تأیید جدید
     *
     * @param string $identifier
     * @param string $identifierType
     * @param int $codeLength
     * @param int $expiryMinutes
     * @param int $cooldownSeconds
     * @param int $maxDailyCodes
     * @return array
     */
    public function sendVerificationCode(
        string $identifier,
        string $identifierType,
        int $codeLength = 6,
        int $expiryMinutes = 5,
        int $cooldownSeconds = 60,
        int $maxDailyCodes = 10
    ): array {
        // بررسی محدودیت روزانه
        $dailyCount = VerificationCode::where('identifier', $identifier)
            ->where('type', $identifierType)
            ->whereDate('created_at', today())
            ->count();

        if ($dailyCount >= $maxDailyCodes) {
            return [
                'success' => false,
                'message' => "شما به حداکثر تعداد مجاز ارسال کد در روز رسیده‌اید. لطفاً فردا دوباره تلاش کنید.",
                'daily_limit_reached' => true,
                'wait_seconds' => 24 * 60 * 60,
                'status' => 429
            ];
        }

        // بررسی محدودیت زمانی
        $canSend = VerificationCode::canSendNew($identifier, $identifierType, $cooldownSeconds);

        if ($canSend !== true) {
            $waitSeconds = is_array($canSend) && isset($canSend['remaining_seconds']) ?
                $canSend['remaining_seconds'] :
                (is_numeric($canSend) ? $canSend : $cooldownSeconds);

            $message = is_array($canSend) && isset($canSend['message']) ?
                $canSend['message'] :
                "به دلیل محدودیت ارسال، لطفاً {$waitSeconds} ثانیه دیگر مجدداً تلاش کنید.";

            return [
                'success' => false,
                'message' => $message,
                'wait_seconds' => $waitSeconds,
                'status' => 429
            ];
        }

        // ارسال کد تأیید
        try {
            DB::beginTransaction();

            $verificationCode = VerificationCode::generateFor(
                $identifier,
                $identifierType,
                $codeLength,
                $expiryMinutes
            );

            $sendResult = VerificationCode::sendCode($identifier, $identifierType, $verificationCode->code);

            DB::commit();

            if (!$sendResult) {
                throw new \Exception('خطا در ارسال کد تأیید');
            }

            $showCodeInDev = app()->environment('local', 'development') && config('app.show_verification_codes', true);

            return [
                'success' => true,
                'message' => $identifierType === 'email'
                    ? 'کد تأیید به ایمیل شما ارسال شد.'
                    : 'کد تأیید به شماره موبایل شما ارسال شد.',
                'expires_at' => $verificationCode->expires_at->timestamp * 1000,
                'code_expiry_minutes' => $expiryMinutes,
                'dev_code' => $showCodeInDev ? $verificationCode->code : null,
                'status' => 200
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("خطا در ارسال کد تأیید: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.',
                'status' => 500
            ];
        }
    }

    /**
     * بررسی و اعتبارسنجی کد تأیید و ورود کاربر
     *
     * @param string $identifier
     * @param string $identifierType
     * @param string $code
     * @param bool $remember
     * @return array
     */
    public function verifyCodeAndAuthenticate(string $identifier, string $identifierType, string $code, bool $remember = false): array
    {
        try {
            $isValid = VerificationCode::validate($identifier, $code, $identifierType);

            if (!$isValid) {
                return [
                    'success' => false,
                    'message' => 'کد تأیید وارد شده صحیح نیست یا منقضی شده است.',
                    'status' => 400
                ];
            }

            $user = User::where($identifierType, $identifier)->first();

            DB::beginTransaction();

            if (!$user) {
                // ایجاد کاربر جدید
                $username = $this->usernameService->generateUniqueUsername($identifier);

                $user = new User();
                $user->$identifierType = $identifier;
                $user->username = $username;
                $user->password = null;
                $user->first_name = 'کاربر';
                $user->last_name = 'جدید';
                $user->display_name = 'کاربر جدید';
                $user->is_active = true;

                // تنظیم فیلد تأیید براساس نوع شناسه
                if ($identifierType === 'email') {
                    $user->email_verified_at = now();
                } else {
                    $user->phone_verified_at = now();
                }

                $user->save();
            } else {
                // به‌روزرسانی وضعیت تأیید کاربر موجود
                if ($identifierType === 'email' && !$user->email_verified_at) {
                    $user->email_verified_at = now();
                    $user->save();
                } elseif ($identifierType === 'phone' && !$user->phone_verified_at) {
                    $user->phone_verified_at = now();
                    $user->save();
                }
            }

            DB::commit();

            Auth::login($user, $remember);

            return [
                'success' => true,
                'message' => 'ورود با موفقیت انجام شد.',
                'user' => $user,
                'status' => 200
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('خطا در فرایند تأیید', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'خطای سیستمی: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * تأیید و به‌روزرسانی اطلاعات جدید کاربر (ایمیل یا تلفن)
     *
     * @param int $userId
     * @param string $identifier
     * @param string $identifierType
     * @param string $code
     * @return array
     */
    public function verifyAndUpdateUserInfo(int $userId, string $identifier, string $identifierType, string $code): array
    {
        try {
            $isValid = VerificationCode::validate($identifier, $code, $identifierType);

            if (!$isValid) {
                return [
                    'success' => false,
                    'message' => 'کد تأیید وارد شده صحیح نیست یا منقضی شده است.',
                    'status' => 400
                ];
            }

            $user = User::findOrFail($userId);

            if ($identifierType === 'email') {
                $user->email = $identifier;
                $user->email_verified_at = now();
            } else {
                $user->phone = $identifier;
                $user->phone_verified_at = now();
            }

            $user->save();

            return [
                'success' => true,
                'message' => $identifierType === 'email'
                    ? 'ایمیل جدید شما با موفقیت تأیید و ثبت شد.'
                    : 'شماره موبایل جدید شما با موفقیت تأیید و ثبت شد.',
                'status' => 200
            ];
        } catch (\Exception $e) {
            Log::error("خطا در تأیید و به‌روزرسانی اطلاعات کاربر: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'خطای سیستمی: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * پردازش درخواست کد تأیید
     *
     * @param string $identifier
     * @param string $identifierType
     * @param int $codeLength
     * @param int $expiryMinutes
     * @param int $cooldownSeconds
     * @param int $maxDailyCodes
     * @param bool $saveInSession آیا اطلاعات در نشست ذخیره شود
     * @param array|null $sessionData اطلاعات اضافی برای ذخیره در نشست
     * @param string $sessionKey کلید ذخیره در نشست
     * @return array
     */
    public function processVerificationRequest(
        string $identifier,
        string $identifierType,
        int $codeLength = 6,
        int $expiryMinutes = 5,
        int $cooldownSeconds = 60,
        int $maxDailyCodes = 10,
        bool $saveInSession = false,
        ?array $sessionData = null,
        string $sessionKey = 'verification_data'
    ): array {
        // بررسی محدودیت روزانه
        $dailyQuery = VerificationCode::where('type', $identifierType)
            ->whereDate('created_at', today());

        if ($identifierType === 'email') {
            $dailyQuery->where('email', $identifier);
        } else {
            $dailyQuery->where('phone', $identifier);
        }

        $dailyCount = $dailyQuery->count();

        if ($dailyCount >= $maxDailyCodes) {
            return [
                'success' => false,
                'message' => "شما به حداکثر تعداد مجاز ارسال کد در روز رسیده‌اید. لطفاً فردا دوباره تلاش کنید.",
                'daily_limit_reached' => true,
                'wait_seconds' => 24 * 60 * 60,
                'status' => 429
            ];
        }

        // بررسی محدودیت زمانی
        $canSend = VerificationCode::canSendNew($identifier, $identifierType, $cooldownSeconds);

        if ($canSend !== true) {
            $waitSeconds = is_array($canSend) && isset($canSend['remaining_seconds']) ?
                $canSend['remaining_seconds'] :
                (is_numeric($canSend) ? $canSend : $cooldownSeconds);

            $message = is_array($canSend) && isset($canSend['message']) ?
                $canSend['message'] :
                "به دلیل محدودیت ارسال، لطفاً {$waitSeconds} ثانیه دیگر مجدداً تلاش کنید.";

            return [
                'success' => false,
                'message' => $message,
                'wait_seconds' => $waitSeconds,
                'status' => 429
            ];
        }

        // ارسال کد تأیید
        try {
            DB::beginTransaction();

            $verificationCode = VerificationCode::generateFor(
                $identifier,
                $identifierType,
                $codeLength,
                $expiryMinutes
            );

            $sendResult = VerificationCode::sendCode($identifier, $identifierType, $verificationCode->code);

            // اگر نیاز به ذخیره در نشست باشد
            if ($saveInSession && $sessionData) {
                session()->put($sessionKey, encrypt($sessionData));
            }

            DB::commit();

            if (!$sendResult) {
                throw new \Exception('خطا در ارسال کد تأیید');
            }

            $showCodeInDev = app()->environment('local', 'development') && config('app.show_verification_codes', true);

            return [
                'success' => true,
                'message' => $identifierType === 'email'
                    ? 'کد تأیید به ایمیل شما ارسال شد.'
                    : 'کد تأیید به شماره موبایل شما ارسال شد.',
                'expires_at' => $verificationCode->expires_at->timestamp * 1000,
                'code_expiry_minutes' => $expiryMinutes,
                'dev_code' => $showCodeInDev ? $verificationCode->code : null,
                'verification_code' => $verificationCode,
                'status' => 200
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("خطا در ارسال کد تأیید: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.',
                'status' => 500
            ];
        }
    }
}
