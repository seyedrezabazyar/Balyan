<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait AuthUtils
{
    /**
     * دریافت اطلاعات احراز هویت از session
     *
     * @return array|null
     */
    public function getAuthSessionData()
    {
        if (!session()->has('auth_data')) {
            return null;
        }

        try {
            return decrypt(session('auth_data'));
        } catch (\Exception $e) {
            Log::error('خطا در رمزگشایی اطلاعات نشست احراز هویت: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * عدم نمایش بخشی از ایمیل
     *
     * @param string $email
     * @return string
     */
    public function maskEmail($email)
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
    public function maskIdentifier($identifier, $type)
    {
        if ($type === 'email') {
            return $this->maskEmail($identifier);
        } else {
            return substr_replace($identifier, '***', 4, 4);
        }
    }

    /**
     * مخفی کردن اطلاعات حساس برای لاگ کردن
     *
     * @param string $data داده حساس
     * @return string داده مخفی شده
     */
    public static function maskSensitiveData(string $data): string
    {
        if (empty($data)) {
            return '';
        }

        if (preg_match('/^\d+$/', $data)) {
            $length = strlen($data);
            if ($length <= 4) {
                return $data;
            }
            return substr($data, 0, 4) . str_repeat('*', min($length - 7, 5)) . substr($data, -3);
        }

        if (strpos($data, '@') !== false) {
            list($username, $domain) = explode('@', $data);
            $usernameLength = strlen($username);
            $maskedUsername = substr($username, 0, min(3, $usernameLength)) .
                str_repeat('*', max(1, $usernameLength - 3));
            return $maskedUsername . '@' . $domain;
        }

        return substr($data, 0, 3) . '***' . substr($data, -3);
    }
}
