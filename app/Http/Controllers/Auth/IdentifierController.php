<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\RecaptchaService;
use App\Services\ValidationMessagesProvider;
use App\Traits\AuthUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IdentifierController extends Controller
{
    use AuthUtils;

    /**
     * سرویس احراز هویت
     *
     * @var AuthService
     */
    protected $authService;

    /**
     * سرویس reCAPTCHA
     *
     * @var RecaptchaService
     */
    protected $recaptchaService;

    /**
     * سازنده کلاس
     *
     * @param AuthService $authService
     * @param RecaptchaService $recaptchaService
     */
    public function __construct(AuthService $authService, RecaptchaService $recaptchaService)
    {
        $this->authService = $authService;
        $this->recaptchaService = $recaptchaService;
    }

    /**
     * پردازش مرحله اول ورود (شناسایی کاربر)
     */
    public function identify(Request $request)
    {
        // لاگ ساده‌تر با داده‌های مورد نیاز
        Log::info('درخواست شناسایی ورود', [
            'login_method' => $request->input('login_method'),
        ]);

        $validator = Validator::make($request->all(), [
            'login_method' => 'required|in:phone,email',
            'email' => 'required_if:login_method,email|email|nullable',
            'phone' => 'required_if:login_method,phone|regex:/^09\d{9}$/|nullable',
        ], ValidationMessagesProvider::getAuthValidationMessages());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // reCaptcha فقط در صورتی بررسی می‌شود که در درخواست وجود داشته باشد
        if ($request->has('g-recaptcha-response') && !$this->recaptchaService->validate($request)) {
            return back()->with('error', 'تأیید reCAPTCHA ناموفق بود. لطفاً دوباره تلاش کنید.');
        }

        $loginMethod = $request->input('login_method');
        $identifier = $loginMethod === 'phone' ? $request->phone : $request->email;
        $identifierType = $loginMethod;

        try {
            // استفاده از سرویس برای ذخیره اطلاعات نشست
            $this->authService->storeAuthSession(
                $identifier,
                $identifierType,
                $request->redirect_to ?? null
            );

            // علامت‌گذاری اینکه کاربر از مرحله شناسایی آمده است
            session()->put('coming_from_identify', true);

        } catch (\Exception $e) {
            Log::error('خطا در ذخیره نشست', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'خطایی رخ داد. لطفاً دوباره تلاش کنید.');
        }

        return redirect()->route('auth.verify-form');
    }
}
