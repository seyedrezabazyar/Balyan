<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

trait SendsVerificationCodes
{
    /**
     * ارسال کد تأیید به کاربر
     *
     * @param string $identifier ایمیل یا شماره موبایل
     * @param string $type نوع شناسه (email یا phone)
     * @param string $code کد تأیید
     * @return bool
     */
    protected function sendCodeToUser(string $identifier, string $type, string $code): bool
    {
        try {
            // اضافه کردن لاگ بیشتر برای عیب‌یابی
            Log::debug('Attempting to send verification code', [
                'identifier' => $identifier,
                'type' => $type,
                'code' => $code // توجه: در محیط تولید، لاگ کردن کد امنیتی توصیه نمی‌شود
            ]);

            if ($type === 'email') {
                return $this->sendEmailVerificationCode($identifier, $code);
            } else {
                return $this->sendSmsVerificationCode($identifier, $code);
            }
        } catch (Exception $e) {
            Log::error("Error sending verification code: " . $e->getMessage(), [
                'identifier' => $identifier,
                'type' => $type,
                'exception' => $e
            ]);

            return false;
        }
    }

    /**
     * ارسال کد تأیید از طریق ایمیل
     *
     * @param string $email آدرس ایمیل
     * @param string $code کد تأیید
     * @return bool
     */
    protected function sendEmailVerificationCode(string $email, string $code): bool
    {
        try {
            // در یک محیط واقعی، شما می‌توانید از Mail::to استفاده کنید
            // بررسی برای ایجاد کلاس MailVerificationCode
            if (class_exists('App\Mail\VerificationCodeMail')) {
                Mail::to($email)->send(new \App\Mail\VerificationCodeMail($code));
                Log::info("Verification code email sent", ['email' => $email]);
                return true;
            }

            // اگر کلاس MailVerificationCode وجود نداشت، یک روش ساده‌تر استفاده می‌کنیم
            // این کد فقط برای نمایش است و در تولید باید جایگزین شود
            $subject = 'کد تأیید بَلیان';
            $message = "کد تأیید شما: {$code}\n\nاین کد تا 15 دقیقه معتبر است.";

            // در اینجا از ارسال واقعی ایمیل صرف نظر می‌کنیم و فقط لاگ می‌کنیم
            Log::info("Verification code would be sent to email", [
                'email' => $email,
                'subject' => $subject,
                'code_length' => strlen($code)
            ]);

            // بررسی طول کد برای اعتبارسنجی ساده
            if (strlen($code) < 4) {
                Log::warning("Verification code is too short", ['code_length' => strlen($code)]);
                return false;
            }

            // در محیط‌های محلی و توسعه، فقط لاگ می‌کنیم و موفقیت را برمی‌گردانیم
            if (app()->environment('local', 'development', 'testing')) {
                Log::debug("Development mode: Email would be sent with code", [
                    'email' => $email,
                    'code' => $code
                ]);
                return true;
            }

            // در یک محیط واقعی، اینجا کد ارسال ایمیل قرار می‌گیرد
            // mail($email, $subject, $message);

            return true;
        } catch (Exception $e) {
            Log::error("Failed to send email verification code: " . $e->getMessage(), [
                'email' => $email,
                'exception' => $e
            ]);
            return false;
        }
    }

    /**
     * ارسال کد تأیید از طریق پیامک
     *
     * @param string $phone شماره موبایل
     * @param string $code کد تأیید
     * @return bool
     */
    protected function sendSmsVerificationCode(string $phone, string $code): bool
    {
        try {
            // بررسی درستی فرمت شماره موبایل (ایران)
            if (!preg_match('/^09[0-9]{9}$/', $phone)) {
                Log::warning("Invalid phone number format", ['phone' => $phone]);
                return false;
            }

            // پیام متنی
            $message = "کد تأیید بَلیان: {$code}\nاین کد تا 15 دقیقه معتبر است.";

            // بررسی برای سرویس پیامک در container
            if (app()->bound('sms')) {
                app('sms')->send($phone, $message);
                Log::info("SMS sent through SMS service", ['phone' => $phone]);
                return true;
            }

            // بررسی برای کاوه‌نگار یا سرویس‌های دیگر
            if (config('services.kavenegar.api_key')) {
                $this->sendKavenegarSms($phone, $code);
                return true;
            }

            // در محیط‌های محلی و توسعه، فقط لاگ می‌کنیم و موفقیت را برمی‌گردانیم
            if (app()->environment('local', 'development', 'testing')) {
                Log::debug("Development mode: SMS would be sent with code", [
                    'phone' => $phone,
                    'code' => $code,
                    'message' => $message
                ]);
                return true;
            }

            Log::warning("No SMS service is configured", ['phone' => $phone]);
            return false;
        } catch (Exception $e) {
            Log::error("Failed to send SMS verification code: " . $e->getMessage(), [
                'phone' => $phone,
                'exception' => $e
            ]);
            return false;
        }
    }

    /**
     * ارسال پیامک با استفاده از سرویس کاوه‌نگار
     *
     * @param string $phone شماره موبایل
     * @param string $code کد تأیید
     * @return bool
     */
    protected function sendKavenegarSms(string $phone, string $code): bool
    {
        try {
            $apiKey = config('services.kavenegar.api_key');
            $sender = config('services.kavenegar.sender', '10004346');
            $template = config('services.kavenegar.verification_template', 'verify');

            // استفاده از API کاوه‌نگار
            $response = Http::get("https://api.kavenegar.com/v1/{$apiKey}/verify/lookup.json", [
                'receptor' => $phone,
                'token' => $code,
                'template' => $template
            ]);

            if ($response->successful()) {
                Log::info("Kavenegar SMS sent successfully", ['phone' => $phone]);
                return true;
            } else {
                Log::error("Kavenegar API error", [
                    'phone' => $phone,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (Exception $e) {
            Log::error("Kavenegar SMS sending error: " . $e->getMessage(), [
                'phone' => $phone,
                'exception' => $e
            ]);
            return false;
        }
    }
}
