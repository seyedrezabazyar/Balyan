<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    /**
     * اعتبارسنجی reCAPTCHA
     *
     * @param Request $request
     * @return bool
     */
    public function validate(Request $request): bool
    {
        // اگر reCAPTCHA فعال نباشد یا در درخواست نباشد، تایید می‌شود
        if (!config('recaptcha.enabled', false) ||
            !$request->has('g-recaptcha-response') ||
            empty($request->input('g-recaptcha-response'))) {
            return true;
        }

        try {
            $response = Http::timeout(3)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('recaptcha.secret_key'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            $responseData = $response->json();

            // فقط لاگ کردن در صورت وجود خطا
            if (!isset($responseData['success']) || $responseData['success'] !== true) {
                Log::warning('اعتبارسنجی reCAPTCHA ناموفق بود', [
                    'score' => $responseData['score'] ?? null
                ]);
                return false;
            }

            // بررسی اسکور reCAPTCHA فقط در صورتی که تنظیم شده باشد
            $minScore = config('recaptcha.min_score', 0.3);
            if (isset($responseData['score']) && $responseData['score'] < $minScore) {
                Log::warning('امتیاز reCAPTCHA خیلی پایین است', [
                    'score' => $responseData['score']
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            // در صورت خطا، به عنوان استثنا، تایید می‌کنیم تا جریان ورود مختل نشود
            // اما خطا را ثبت می‌کنیم
            Log::error('خطا در تأیید reCAPTCHA', ['error' => $e->getMessage()]);
            return true;
        }
    }
}
