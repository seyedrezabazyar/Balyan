<?php

namespace App\Services;

class ValidationMessagesProvider
{
    /**
     * دریافت پیام‌های خطای مربوط به احراز هویت
     *
     * @return array
     */
    public static function getAuthValidationMessages()
    {
        return [
            'email.required' => 'لطفاً ایمیل خود را وارد کنید.',
            'email.required_if' => 'لطفاً ایمیل خود را وارد کنید.',
            'email.email' => 'ایمیل وارد شده معتبر نیست.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',
            'phone.required' => 'لطفاً شماره موبایل خود را وارد کنید.',
            'phone.required_if' => 'لطفاً شماره موبایل خود را وارد کنید.',
            'phone.regex' => 'شماره موبایل وارد شده معتبر نیست.',
            'phone.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
            'login_method.required' => 'لطفاً روش ورود را انتخاب کنید.',
            'username.required' => 'لطفاً نام کاربری خود را وارد کنید.',
            'username.alpha_dash' => 'نام کاربری فقط می‌تواند شامل حروف، اعداد و خط تیره باشد.',
            'username.unique' => 'این نام کاربری قبلاً ثبت شده است.',
            'password.required' => 'لطفاً رمز عبور خود را وارد کنید.',
            'password.min' => 'رمز عبور باید حداقل :min کاراکتر باشد.',
            'password.confirmed' => 'تأیید رمز عبور با رمز عبور مطابقت ندارد.',
            'verification_code.required' => 'لطفاً کد تأیید را وارد کنید.',
            'verification_code.digits' => 'کد تأیید باید :digits رقم باشد.',
        ];
    }

    /**
     * دریافت پیام‌های خطای مربوط به پروفایل کاربر
     *
     * @return array
     */
    public static function getProfileValidationMessages()
    {
        return [
            'first_name.required' => 'لطفا نام خود را وارد کنید.',
            'last_name.required' => 'لطفا نام خانوادگی خود را وارد کنید.',
            'profile_image.image' => 'فایل انتخاب شده باید تصویر باشد.',
            'profile_image.mimes' => 'فرمت تصویر باید jpeg، png یا jpg باشد.',
            'profile_image.max' => 'حداکثر حجم تصویر ۲ مگابایت است.',
        ];
    }
}
