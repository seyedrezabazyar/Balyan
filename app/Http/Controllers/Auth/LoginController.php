<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\AuthSessionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthSessionTrait;

    /**
     * نمایش فرم ورود
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ورود با رمز عبور
     */
    public function loginWithPassword(Request $request)
    {
        // بررسی اطلاعات جلسه
        $authData = $this->getAuthSessionData();

        if (!$authData) {
            return redirect()->route('login')
                ->with('error', 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.');
        }

        $identifier = $authData['auth_identifier'];
        $identifierType = $authData['auth_identifier_type'];

        // اعتبارسنجی درخواست
        $request->validate([
            'password' => 'required|string',
        ], [
            'password.required' => 'لطفاً رمز عبور خود را وارد کنید.',
        ]);

        // تلاش برای ورود
        $credentials = [
            $identifierType => $identifier,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // موفقیت در ورود
            $redirectTo = $authData['redirect_to'] ?? '/dashboard';

            // پاک کردن اطلاعات احراز هویت از جلسه
            session()->forget('auth_data');

            // بازسازی جلسه برای جلوگیری از حملات session fixation
            $request->session()->regenerate();

            return redirect($redirectTo);
        }

        // ورود ناموفق
        throw ValidationException::withMessages([
            'password' => ['رمز عبور وارد شده صحیح نیست.'],
        ]);
    }

    /**
     * خروج کاربر
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
