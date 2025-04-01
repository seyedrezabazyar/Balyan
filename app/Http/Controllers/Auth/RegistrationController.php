<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UsernameService;
use App\Traits\AuthUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    use AuthUtils;

    /**
     * سرویس تولید نام کاربری
     *
     * @var UsernameService
     */
    protected $usernameService;

    /**
     * سازنده کلاس
     *
     * @param UsernameService $usernameService
     */
    public function __construct(UsernameService $usernameService)
    {
        $this->usernameService = $usernameService;
    }

    /**
     * ایجاد کاربر جدید
     *
     * @param array $userData
     * @return User
     */
    public function createUser($userData)
    {
        // حذف اطلاعات حساس قبل از لاگ کردن
        $logUserData = array_diff_key($userData, array_flip(['password']));
        Log::info('ایجاد کاربر جدید از کنترلر ثبت‌نام', [
            'userData' => $logUserData
        ]);

        // اگر رمز عبور وجود نداشته باشد، یک رمز عبور تصادفی ایجاد می‌کنیم
        if (!isset($userData['password'])) {
            $userData['password'] = Hash::make(Str::random(12));
        }

        // اگر نام کاربری وجود نداشته باشد، از سرویس استفاده می‌کنیم
        if (!isset($userData['username'])) {
            $identifier = $userData['email'] ?? $userData['phone'] ?? null;
            $identifierType = isset($userData['email']) ? 'email' : 'phone';

            // استفاده از سرویس به جای متد داخلی
            $userData['username'] = $this->usernameService->generateUniqueUsername($identifier);
        }

        // ایجاد کاربر جدید
        $user = User::create($userData);

        Log::info('کاربر ایجاد شد', [
            'user_id' => $user->id,
            'username' => $user->username
        ]);

        return $user;
    }

    /**
     * نمایش فرم ثبت‌نام
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        // اگر کاربر قبلاً وارد شده، به صفحه پروفایل هدایت می‌شود
        if (auth()->check()) {
            return redirect()->route('profile.index');
        }

        // دریافت اطلاعات از نشست احراز هویت (در صورت وجود)
        $authData = $this->getAuthSessionData();
        $prefilledData = [];

        if ($authData) {
            $identifierType = $authData['auth_identifier_type'];
            $identifier = $authData['auth_identifier'];

            if ($identifierType === 'email') {
                $prefilledData['email'] = $identifier;
            } elseif ($identifierType === 'phone') {
                $prefilledData['phone'] = $identifier;
            }
        }

        return view('auth.register', ['prefilledData' => $prefilledData]);
    }

    /**
     * ثبت‌نام کاربر جدید
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|regex:/^09\d{9}$/|unique:users,phone',
            'password' => 'required|min:8|confirmed',
        ]);

        // بررسی وجود حداقل یک روش تماس (ایمیل یا تلفن)
        if (empty($request->email) && empty($request->phone)) {
            return back()
                ->withInput()
                ->withErrors(['contact' => 'حداقل یکی از فیلدهای ایمیل یا شماره تلفن باید پر شود.']);
        }

        // ایجاد داده‌های کاربر
        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ];

        // اگر ایمیل یا شماره تلفن قبلاً در فرآیند احراز هویت تایید شده باشد
        $authData = $this->getAuthSessionData();
        if ($authData) {
            $identifierType = $authData['auth_identifier_type'];
            $identifier = $authData['auth_identifier'];

            if ($identifierType === 'email' && $request->email === $identifier) {
                $userData['email_verified_at'] = now();
            } elseif ($identifierType === 'phone' && $request->phone === $identifier) {
                $userData['phone_verified_at'] = now();
            }
        }

        // ایجاد کاربر
        $user = $this->createUser($userData);

        // ورود کاربر
        auth()->login($user, $request->filled('remember'));

        // پاکسازی نشست احراز هویت در صورت وجود
        if ($authData) {
            session()->forget('auth_data');
        }

        return redirect()->route('profile.index')
            ->with('success', 'حساب کاربری شما با موفقیت ایجاد شد.');
    }
}
