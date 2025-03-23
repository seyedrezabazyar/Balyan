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

class VerificationController extends Controller
{
    use SendsVerificationCodes, AuthSessionTrait;

    /**
     * نمایش فرم تأیید هویت
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showVerificationForm(Request $request)
    {
        Log::info('===== showVerificationForm started =====');

        // بررسی وجود شناسه در سشن
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

        // پیدا کردن آخرین کد تأیید معتبر
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
                // بررسی انقضای کد
                if ($lastCode->expires_at->gt(now())) {
                    $verificationCode = $lastCode;
                    Log::info("Found existing valid verification code", [
                        'code_id' => $lastCode->id,
                        'expires_at' => $lastCode->expires_at->format('Y-m-d H:i:s'),
                        'remaining_seconds' => $lastCode->getRemainingTime()
                    ]);
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

        // بررسی اینکه آیا کاربر از صفحه شناسایی آمده است
        $comingFromIdentify = $request->session()->pull('coming_from_identify', false);
        Log::info('Coming from identify page?', ['status' => $comingFromIdentify ? 'yes' : 'no']);

        // ارسال کد جدید فقط در شرایط خاص
        $shouldSendNewCode = false;

        // حالت 1: کاربر جدید است یا رمز عبور ندارد و هیچ کدی ندارد - همیشه ارسال شود
        if ((!$userExists || !$hasPassword) && !$verificationCode && !$codeExpired) {
            $shouldSendNewCode = true;
            Log::info('Should send code: New user or no password and no code');
        }
        // حالت 2: کاربر از صفحه شناسایی آمده و کد منقضی شده است - ارسال شود
        else if ($comingFromIdentify && $codeExpired) {
            $shouldSendNewCode = true;
            Log::info('Should send code: Coming from identify page and code expired');
        }
        // حالت 3: کاربر از صفحه شناسایی آمده و هیچ کدی ندارد - ارسال شود
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
                // بررسی محدودیت ارسال کد
                $canSend = VerificationCode::canSendNew($identifier, $identifierType);
                Log::info('Can send new code check result', [
                    'can_send' => $canSend === true ? 'yes' : 'wait ' . $canSend . ' seconds'
                ]);

                if ($canSend === true) {
                    // ایجاد کد تأیید
                    Log::info('About to generate verification code');

                    try {
                        // ثبت دیتابیس را به طور مستقیم آزمایش کنیم
                        DB::beginTransaction();

                        $verificationCode = VerificationCode::generateFor($identifier, $identifierType, 6, 5); // زمان انقضا 5 دقیقه

                        Log::info('Verification code generated successfully', [
                            'code_id' => $verificationCode->id,
                            'code' => $verificationCode->code, // در محیط واقعی حذف کنید
                            'expires_at' => $verificationCode->expires_at->format('Y-m-d H:i:s')
                        ]);

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Database error generating verification code', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw $e; // دوباره پرتاب می‌کنیم تا توسط catch بیرونی مدیریت شود
                    }

                    // واقعاً کد را ارسال می‌کنیم
                    $sendResult = $this->sendCodeToUser($identifier, $identifierType, $verificationCode->code);
                    Log::info('Code sending result', ['success' => $sendResult]);

                    // پیام موفقیت
                    $message = $identifierType === 'email'
                        ? 'کد تأیید به ایمیل شما ارسال شد.'
                        : 'کد تأیید به شماره موبایل شما ارسال شد.';

                    session()->flash('success', $message);
                    $codeExpired = false;
                } else {
                    // اگر محدودیت زمانی وجود دارد
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

        // اگر کاربر وجود دارد، اطلاعات تماس او را دریافت کنیم
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
     * ارسال کد تأیید (برای صفحه verify)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerificationCode(Request $request)
    {
        Log::info('Send verification code request', $request->all());

        // بررسی وجود شناسه در session
        $authData = $this->getAuthSessionData();

        if (!$authData) {
            return response()->json([
                'success' => false,
                'message' => 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.'
            ], 401);
        }

        // اگر شناسه در درخواست ارسال شده، از آن استفاده می‌کنیم
        if ($request->filled('identifier_type') && $request->filled('identifier')) {
            $identifierType = $request->input('identifier_type');
            $identifier = $request->input('identifier');
        } else {
            // در غیر این صورت از شناسه در session استفاده می‌کنیم
            $identifierType = $authData['auth_identifier_type'];
            $identifier = $authData['auth_identifier'];
        }

        // بررسی محدودیت ارسال کد
        $canSend = VerificationCode::canSendNew($identifier, $identifierType);

        if ($canSend !== true) {
            return response()->json([
                'success' => false,
                'message' => "برای ارسال مجدد کد باید {$canSend} ثانیه صبر کنید.",
                'wait_seconds' => $canSend
            ], 429);
        }

        try {
            // ایجاد کد تأیید جدید
            DB::beginTransaction();

            $verificationCode = VerificationCode::generateFor($identifier, $identifierType, 6, 5); // زمان انقضا 5 دقیقه

            Log::info('Verification code generated', [
                'code_id' => $verificationCode->id,
                'identifier' => $identifier,
                'identifier_type' => $identifierType,
                'expires_at' => $verificationCode->expires_at->format('Y-m-d H:i:s')
            ]);

            DB::commit();

            // ارسال کد
            $sendResult = $this->sendCodeToUser($identifier, $identifierType, $verificationCode->code);

            Log::info('Code sending result', ['success' => $sendResult]);

            if (!$sendResult) {
                throw new \Exception('خطا در ارسال کد تأیید');
            }

            // پاسخ موفق
            return response()->json([
                'success' => true,
                'message' => $identifierType === 'email'
                    ? 'کد تأیید به ایمیل شما ارسال شد.'
                    : 'کد تأیید به شماره موبایل شما ارسال شد.',
                'expires_at' => $verificationCode->expires_at->timestamp * 1000,
                'dev_code' => app()->environment('local', 'development') ? $verificationCode->code : null
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error sending verification code: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.'
            ], 500);
        }
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

        // بررسی توکن جلسه برای امنیت AJAX
        if ($request->session_token !== $authData['session_token']) {
            return response()->json([
                'success' => false,
                'message' => 'درخواست نامعتبر است.'
            ], 403);
        }

        // بررسی محدودیت ارسال کد
        $canSend = VerificationCode::canSendNew($identifier, $identifierType);

        if ($canSend !== true) {
            return response()->json([
                'success' => false,
                'message' => "برای ارسال مجدد کد باید {$canSend} ثانیه صبر کنید.",
                'waitTime' => $canSend
            ], 429);
        }

        try {
            // ایجاد کد تأیید جدید
            $verificationCode = VerificationCode::generateFor($identifier, $identifierType, 6, 5); // زمان انقضا 5 دقیقه

            // ارسال کد
            $this->sendCodeToUser($identifier, $identifierType, $verificationCode->code);

            // پاسخ موفق
            return response()->json([
                'success' => true,
                'message' => 'کد تأیید با موفقیت ارسال شد.',
                'expiresAt' => $verificationCode->expires_at->timestamp * 1000,
                'dev_code' => app()->environment('local', 'development') ? $verificationCode->code : null
            ]);
        } catch (\Exception $e) {
            Log::error("Error sending verification code: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'خطا در ارسال کد تأیید. لطفاً دوباره تلاش کنید.'
            ], 500);
        }
    }

    /**
     * بررسی و تأیید کد وارد شده
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
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

        // اعتبارسنجی کد ارسال شده
        $request->validate([
            'code' => 'required|digits:6',
            'verification_code' => 'sometimes|required|digits:6',
        ], [
            'code.required' => 'لطفاً کد تأیید را وارد کنید.',
            'code.digits' => 'کد تأیید باید ۶ رقم باشد.',
            'verification_code.required' => 'لطفاً کد تأیید را وارد کنید.',
            'verification_code.digits' => 'کد تأیید باید ۶ رقم باشد.',
        ]);

        // قبول هر دو فیلد code یا verification_code
        $verificationCode = $request->input('code', $request->input('verification_code'));

        // بررسی صحت کد
        $isValid = VerificationCode::validate($identifier, $verificationCode, $identifierType);

        if (!$isValid) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'کد تأیید وارد شده صحیح نیست یا منقضی شده است.'
                ], 400);
            }

            return back()->withErrors([
                'code' => 'کد تأیید وارد شده صحیح نیست یا منقضی شده است.'
            ]);
        }

        // کد تأیید صحیح است - ثبت یا ورود کاربر
        $user = User::where($identifierType, $identifier)->first();

        if (!$user) {
            // استفاده از RegistrationController برای ایجاد کاربر جدید
            $registrationController = new RegistrationController();
            $user = $registrationController->createUser($identifier, $identifierType);
        }

        // ورود کاربر
        Auth::login($user, $request->filled('remember_me'));

        // پاک کردن اطلاعات احراز هویت از جلسه
        session()->forget('auth_data');

        // بازسازی جلسه برای جلوگیری از حملات session fixation
        $request->session()->regenerate();

        // تعیین مسیر بازگشت
        $redirectTo = $authData['redirect_to'] ?? '/dashboard';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'ورود با موفقیت انجام شد.',
                'redirect' => $redirectTo
            ]);
        }

        return redirect($redirectTo);
    }
}
