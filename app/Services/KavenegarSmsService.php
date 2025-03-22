<?php

namespace App\Services;

use App\Contracts\SmsServiceInterface;
use Illuminate\Support\Facades\Log;
use Kavenegar\KavenegarApi;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

class KavenegarSmsService implements SmsServiceInterface
{
    protected $api;

    public function __construct()
    {
        $this->api = new KavenegarApi(config('services.kavenegar.api_key'));
    }

    /**
     * ارسال پیامک ساده
     *
     * @param string $phoneNumber شماره موبایل
     * @param string $message متن پیامک
     * @return bool
     */
    public function send(string $phoneNumber, string $message): bool
    {
        try {
            $sender = config('services.kavenegar.sender');
            $this->api->Send($sender, $phoneNumber, $message);

            Log::info("SMS sent successfully", ['phone' => $phoneNumber]);
            return true;
        } catch (ApiException | HttpException $e) {
            Log::error("SMS sending error: " . $e->getMessage(), [
                'phone' => $phoneNumber,
                'exception' => $e
            ]);
            return false;
        }
    }

    /**
     * ارسال کد تأیید
     *
     * @param string $phoneNumber شماره موبایل
     * @param string $code کد تأیید
     * @return bool
     */
    public function sendVerificationCode(string $phoneNumber, string $code): bool
    {
        try {
            $template = config('services.kavenegar.verification_template', 'verify');
            $this->api->VerifyLookup($phoneNumber, $code, null, null, $template);

            Log::info("Verification code sent", ['phone' => $phoneNumber]);
            return true;
        } catch (ApiException | HttpException $e) {
            Log::error("Verification code sending error: " . $e->getMessage(), [
                'phone' => $phoneNumber,
                'exception' => $e
            ]);
            return false;
        }
    }
}
