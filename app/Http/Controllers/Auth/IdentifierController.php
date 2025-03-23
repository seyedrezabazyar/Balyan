<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\AuthSessionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class IdentifierController extends Controller
{
    use AuthSessionTrait;

    /**
     * پردازش مرحله اول ورود (شناسایی کاربر)
     */
    public function identify(Request $request)
    {
        Log::info('Login identify request', [
            'has_recaptcha' => $request->has('g-recaptcha-response'),
            'login_method' => $request->input('login_method'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
        ]);

        // اعتبارسنجی اولیه براساس روش انتخاب شده
        $validator = Validator::make($request->all(), [
            'login_method' => 'required|in:phone,email',
            'email' => 'required_if:login_method,email|email|nullable',
            'phone' => 'required_if:login_method,phone|regex:/^09\d{9}$/|nullable',
            'g-recaptcha-response' => 'nullable', // reCAPTCHA اختیاری شد
        ], [
            'email.required_if' => 'لطفاً ایمیل خود را وارد کنید.',
            'email.email' => 'ایمیل وارد شده معتبر نیست.',
            'phone.required_if' => 'لطفاً شماره موبایل خود را وارد کنید.',
            'phone.regex' => 'شماره موبایل وارد شده معتبر نیست.',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // بررسی reCAPTCHA
        if (!$this->validateRecaptcha($request)) {
            return back()->with('error', 'تأیید reCAPTCHA ناموفق بود. لطفاً دوباره تلاش کنید.');
        }

        // تعیین نوع شناسه و مقدار آن براساس روش انتخاب شده
        $loginMethod = $request->input('login_method');

        if ($loginMethod === 'phone') {
            $identifier = $request->phone;
            $identifierType = 'phone';
        } else {
            $identifier = $request->email;
            $identifierType = 'email';
        }

        // بررسی وجود کاربر
        $user = User::where($identifierType, $identifier)->first();

        $sessionData = [
            'auth_identifier' => $identifier,
            'auth_identifier_type' => $identifierType,
            'redirect_to' => $request->redirect_to ?? null,
            'user_exists' => $user ? true : false,
            'has_password' => $user && !empty($user->password) ? true : false,
            'session_token' => Str::random(40),
            'created_at' => now(),
        ];

        // ذخیره اطلاعات در جلسه
        try {
            session()->put('auth_data', encrypt($sessionData));
            // علامت‌گذاری اینکه کاربر از صفحه شناسایی آمده است
            session()->put('coming_from_identify', true);

            Log::info('Session data stored successfully', [
                'identifier' => $identifier,
                'type' => $identifierType,
                'user_exists' => $sessionData['user_exists'],
                'has_password' => $sessionData['has_password'],
                'coming_from_identify' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Session store error', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'خطایی رخ داد. لطفاً دوباره تلاش کنید.');
        }

        return redirect()->route('auth.verify-form');
    }

    /**
     * اعتبارسنجی reCAPTCHA
     *
     * @param Request $request
     * @return bool
     */
    public function validateRecaptcha(Request $request)
    {
        // اگر reCAPTCHA وجود نداشت، اعتبارسنجی را رد نمی‌کنیم
        if (!$request->has('g-recaptcha-response') || empty($request->input('g-recaptcha-response'))) {
            return true;
        }

        try {
            $response = Http::timeout(3)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            $responseData = $response->json();
            Log::info('reCAPTCHA response', [
                'success' => $responseData['success'] ?? false,
                'score' => $responseData['score'] ?? null
            ]);

            // فقط در صورت امتیاز بسیار پایین، جلوگیری می‌کنیم
            if (isset($responseData['success']) && $responseData['success'] === true) {
                if (isset($responseData['score']) && $responseData['score'] < 0.3) {
                    return false;
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA error', ['error' => $e->getMessage()]);
            // حتی در صورت خطا، اجازه ادامه می‌دهیم
            return true;
        }
    }

    /**
     * عدم نمایش بخشی از ایمیل
     *
     * @param string $email
     * @return string
     */
    public static function maskEmail($email)
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
     * عدم نمایش بخشی از شناسه (ایمیل یا شماره تلفن)
     *
     * @param string $identifier
     * @param string $type
     * @return string
     */
    public static function maskIdentifier($identifier, $type)
    {
        if ($type === 'email') {
            return self::maskEmail($identifier);
        } else {
            // پنهان کردن بخشی از شماره موبایل
            return substr_replace($identifier, '***', 4, 4);
        }
    }
}
