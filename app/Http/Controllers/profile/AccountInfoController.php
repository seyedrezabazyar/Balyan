<?php

namespace App\Http\Controllers\profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class AccountInfoController extends Controller
{
    /**
     * نمایش صفحه ویرایش اطلاعات کاربری
     */
    public function index()
    {
        // بررسی وضعیت احراز هویت کاربر
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'شما باید وارد حساب کاربری خود شوید');
        }

        $user = Auth::user();

        // لاگ کردن برای دیباگ
        Log::info('AccountInfo page accessed by user: ' . $user->id);

        // محاسبه درصد تکمیل پروفایل
        $profileCompletionPercentage = $this->calculateProfileCompletion($user);

        // بررسی محدودیت تغییر نام کاربری
        $canChangeUsername = $this->canChangeUsername($user);

        // بررسی امکان تغییر ایمیل و شماره تلفن
        $canChangeEmail = !$user->email_verified_at;
        $canChangePhone = !$user->phone_verified_at;

        // زمان باقی‌مانده تا امکان تغییر مجدد نام کاربری (گرد شده به عدد صحیح)
        $daysUntilUsernameChange = (int)$this->daysUntilUsernameChange($user);

        return view('profile.account-info', [
            'user' => $user,
            'profileCompletionPercentage' => $profileCompletionPercentage,
            'canChangeUsername' => $canChangeUsername,
            'canChangeEmail' => $canChangeEmail,
            'canChangePhone' => $canChangePhone,
            'daysUntilUsernameChange' => $daysUntilUsernameChange
        ]);
    }

    /**
     * به‌روزرسانی اطلاعات کاربری
     */
    public function update(Request $request)
    {
        // ثبت داده‌های ارسالی برای دیباگ
        Log::info('Profile update request data:', $request->all());

        $user = Auth::user();

        // بررسی محدودیت تغییر نام کاربری
        $canChangeUsername = $this->canChangeUsername($user);

        // بررسی امکان تغییر ایمیل و شماره تلفن
        $canChangeEmail = !$user->email_verified_at;
        $canChangePhone = !$user->phone_verified_at;

        // قوانین اعتبارسنجی پایه
        $rules = [
            'display_name' => 'nullable|string|max:200',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        // اضافه کردن قوانین ایمیل اگر کاربر مجاز به تغییر آن باشد
        if ($canChangeEmail) {
            $rules['email'] = [
                Rule::requiredIf(function () use ($user, $request) {
                    return !$request->filled('phone') && !$user->phone;
                }),
                'nullable',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ];
        }

        // اضافه کردن قوانین شماره تلفن اگر کاربر مجاز به تغییر آن باشد
        if ($canChangePhone) {
            $rules['phone'] = [
                Rule::requiredIf(function () use ($user, $request) {
                    return !$request->filled('email') && !$user->email;
                }),
                'nullable',
                'string',
                'regex:/^0[0-9]{10}$/', // شماره 11 رقمی با صفر ابتدا
                Rule::unique('users')->ignore($user->id),
            ];
        }

        // اضافه کردن قوانین نام کاربری اگر کاربر مجاز به تغییر آن باشد
        if ($canChangeUsername) {
            $rules['username'] = [
                'nullable',
                'alpha_dash',
                'max:50',
                Rule::unique('users')->ignore($user->id),
            ];
        }

        // پیام‌های خطا
        $messages = [
            'first_name.required' => 'لطفا نام خود را وارد کنید.',
            'last_name.required' => 'لطفا نام خانوادگی خود را وارد کنید.',
            'email.required' => 'لطفا ایمیل خود را وارد کنید.',
            'email.email' => 'لطفا یک ایمیل معتبر وارد کنید.',
            'email.unique' => 'این ایمیل قبلا ثبت شده است.',
            'phone.required' => 'لطفا شماره تماس خود را وارد کنید.',
            'phone.regex' => 'شماره تماس باید ۱۱ رقم و با صفر شروع شود (مانند 09123456789).',
            'phone.unique' => 'این شماره تماس قبلا ثبت شده است.',
            'username.alpha_dash' => 'نام کاربری فقط می‌تواند شامل حروف، اعداد و خط تیره باشد.',
            'username.unique' => 'این نام کاربری قبلا ثبت شده است.',
            'profile_image.image' => 'فایل انتخاب شده باید تصویر باشد.',
            'profile_image.mimes' => 'فرمت تصویر باید jpeg، png یا jpg باشد.',
            'profile_image.max' => 'حداکثر حجم تصویر ۲ مگابایت است.',
        ];

        // اعتبارسنجی داده‌ها
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            Log::error('Profile update validation errors:', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Log::info('Starting user profile update');

            // به‌روزرسانی اطلاعات کاربر
            $user->display_name = $request->input('display_name');
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');

            // به‌روزرسانی نام کاربری اگر مجاز باشد و تغییر کرده باشد
            if ($canChangeUsername && $request->filled('username') && $request->input('username') !== $user->username) {
                $user->username = $request->input('username');

                // اگر ستون username_changed_at در دیتابیس موجود است
                if (Schema::hasColumn('users', 'username_changed_at')) {
                    $user->username_changed_at = now(); // ثبت زمان تغییر نام کاربری
                }

                Log::info('Username updated to: ' . $request->input('username'));
            } elseif (!$canChangeUsername && $request->filled('username') && $request->input('username') !== $user->username) {
                // اگر کاربر مجاز به تغییر نام کاربری نباشد و نام کاربری را تغییر داده باشد
                $daysRemaining = (int)$this->daysUntilUsernameChange($user);
                return redirect()->back()->with('error', "شما فقط یک بار در ماه می‌توانید نام کاربری خود را تغییر دهید. {$daysRemaining} روز دیگر می‌توانید مجدداً تغییر دهید.")->withInput();
            }

            Log::info('Basic user info updated');

            // بررسی ایمیل
            if ($canChangeEmail && $request->filled('email') && $request->input('email') !== $user->email) {
                $user->email = $request->input('email');
                $user->email_verified_at = null; // حذف تاریخ تایید ایمیل برای ایمیل جدید
                Log::info('User email updated to: ' . $request->input('email'));
            } elseif (!$canChangeEmail && $request->filled('email') && $request->input('email') !== $user->email) {
                // اگر کاربر مجاز به تغییر ایمیل نباشد و ایمیل را تغییر داده باشد
                return redirect()->back()->with('error', "امکان تغییر ایمیل وجود ندارد. ایمیل شما قبلاً تایید شده است.")->withInput();
            }

            // بررسی و استانداردسازی شماره تماس
            if ($canChangePhone && $request->filled('phone')) {
                $phone = $request->input('phone');

                // حذف کاراکترهای اضافی و فرمت‌بندی
                $phone = preg_replace('/[^0-9]/', '', $phone); // فقط اعداد را نگه می‌داریم

                // اگر با +98 یا 98 شروع شده، آن را به 0 تبدیل می‌کنیم
                if (substr($phone, 0, 3) === '989' || substr($phone, 0, 2) === '98') {
                    $phone = '0' . substr($phone, -10);
                } // اگر با 9 شروع شده و 10 رقم است، 0 به ابتدای آن اضافه می‌کنیم
                elseif (substr($phone, 0, 1) === '9' && strlen($phone) === 10) {
                    $phone = '0' . $phone;
                } // اگر طول شماره کمتر از 11 است یا با 0 شروع نمی‌شود، خطا می‌دهیم
                elseif (strlen($phone) !== 11 || substr($phone, 0, 1) !== '0') {
                    return redirect()->back()->withErrors(['phone' => 'فرمت شماره تلفن صحیح نیست. شماره باید 11 رقم و با صفر شروع شود (مانند 09123456789).'])->withInput();
                }

                if ($phone !== $user->phone) {
                    $user->phone = $phone;
                    $user->phone_verified_at = null; // حذف تاریخ تایید شماره تلفن برای شماره جدید
                    Log::info('User phone updated to: ' . $phone);
                }
            } elseif (!$canChangePhone && $request->filled('phone') && $request->input('phone') !== $user->phone) {
                // اگر کاربر مجاز به تغییر شماره تلفن نباشد و شماره تلفن را تغییر داده باشد
                return redirect()->back()->with('error', "امکان تغییر شماره تلفن وجود ندارد. شماره تلفن شما قبلاً تایید شده است.")->withInput();
            }

            // بررسی آپلود تصویر
            if ($request->hasFile('profile_image')) {
                Log::info('Profile image upload detected');

                // حذف تصویر قبلی اگر وجود داشته باشد
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                    Log::info('Previous profile image deleted');
                }

                // ذخیره تصویر جدید
                $path = $request->file('profile_image')->store('profile-images', 'public');
                $user->profile_image = $path;
                Log::info('New profile image saved at: ' . $path);
            }

            $user->save();
            Log::info('User profile saved successfully');

            return redirect()->route('profile.account-info')->with('success', 'اطلاعات حساب کاربری با موفقیت به‌روزرسانی شد.');
        } catch (\Exception $e) {
            Log::error('Error updating user profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'خطا در به‌روزرسانی اطلاعات: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * محاسبه درصد تکمیل پروفایل
     */
    private function calculateProfileCompletion($user)
    {
        $fields = [
            'username' => 10,
            'first_name' => 15,
            'last_name' => 15,
            'email' => 20,
            'phone' => 20,
            'profile_image' => 20,
        ];

        $total = 0;
        foreach ($fields as $field => $weight) {
            if (!empty($user->$field)) {
                $total += $weight;
            }
        }

        return min(100, $total);
    }

    /**
     * بررسی امکان تغییر نام کاربری
     *
     * @param \App\Models\User $user
     * @return bool
     */
    private function canChangeUsername($user)
    {
        // اگر ستون username_changed_at در دیتابیس موجود نیست یا کاربر قبلاً نام کاربری خود را تغییر نداده باشد
        if (!Schema::hasColumn('users', 'username_changed_at') || !$user->username_changed_at) {
            return true;
        }

        // بررسی گذشت یک ماه از آخرین تغییر
        $lastChange = Carbon::parse($user->username_changed_at);
        $oneMonthLater = $lastChange->addMonth();

        return Carbon::now()->gte($oneMonthLater);
    }

    /**
     * محاسبه تعداد روزهای باقی‌مانده تا امکان تغییر مجدد نام کاربری
     *
     * @param \App\Models\User $user
     * @return int
     */
    private function daysUntilUsernameChange($user)
    {
        // اگر ستون username_changed_at در دیتابیس موجود نیست یا کاربر قبلاً نام کاربری خود را تغییر نداده باشد
        if (!Schema::hasColumn('users', 'username_changed_at') || !$user->username_changed_at) {
            return 0;
        }

        $lastChange = Carbon::parse($user->username_changed_at);
        $nextChangeDate = $lastChange->addMonth();

        // اگر زمان تغییر مجدد فرا رسیده باشد
        if (Carbon::now()->gte($nextChangeDate)) {
            return 0;
        }

        return Carbon::now()->diffInDays($nextChangeDate);
    }
}
