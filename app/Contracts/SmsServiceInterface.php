<?php

namespace App\Contracts;

interface SmsServiceInterface
{
    /**
     * ارسال پیامک ساده
     *
     * @param string $phoneNumber شماره موبایل
     * @param string $message متن پیامک
     * @return bool
     */
    public function send(string $phoneNumber, string $message): bool;

    /**
     * ارسال کد تأیید
     *
     * @param string $phoneNumber شماره موبایل
     * @param string $code کد تأیید
     * @return bool
     */
    public function sendVerificationCode(string $phoneNumber, string $code): bool;
}
