<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Contracts\SmsServiceInterface;
use Illuminate\Support\Facades\Mail;
use Exception;

class VerificationCode extends Model
{
    use HasFactory;

    /**
     * ویژگی‌هایی که اجازه تخصیص جمعی دارند
     */
    protected $fillable = [
        'identifier',
        'type',
        'code',
        'expires_at',
        'used',
        'attempts'
    ];

    /**
     * ویژگی‌هایی که باید تبدیل شوند
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
        'attempts' => 'integer'
    ];

    /**
     * ایجاد یک کد تأیید جدید برای شناسه مشخص شده
     *
     * @param string $identifier ایمیل یا شماره موبایل
     * @param string $type نوع شناسه (email یا phone)
     * @param int $codeLength طول کد تأیید
     * @param int $expiresInMinutes مدت زمان اعتبار کد به دقیقه
     * @return self
     */
    public static function generateFor(string $identifier, string $type, int $codeLength = 6, int $expiresInMinutes = 5): self
    {
        if ($type === 'phone' && strpos($identifier, '*') !== false) {
            $originalIdentifier = self::findOriginalIdentifier($identifier, $type);
            if ($originalIdentifier) {
                Log::info('استفاده از شماره تلفن اصلی به جای شماره تلفن مخفی شده', [
                    'masked' => self::maskSensitiveData($identifier),
                    'original' => self::maskSensitiveData($originalIdentifier)
                ]);
                $identifier = $originalIdentifier;
            }
        }

        Log::debug('در حال ایجاد کد تأیید', [
            'identifier' => self::maskSensitiveData($identifier),
            'type' => $type,
            'length' => $codeLength,
            'expiry' => $expiresInMinutes
        ]);

        $code = self::generateRandomCode($codeLength);
        $expiresAt = now()->addMinutes($expiresInMinutes);

        $verificationCode = self::create([
            'identifier' => $identifier,
            'type' => $type,
            'code' => $code,
            'expires_at' => $expiresAt,
            'used' => false,
            'attempts' => 0
        ]);

        Log::info("کد تأیید ایجاد شد", [
            'identifier' => self::maskSensitiveData($identifier),
            'type' => $type,
            'code' => $code,
            'expires_at' => $expiresAt->format('Y-m-d H:i:s')
        ]);

        return $verificationCode;
    }

    /**
     * اعتبارسنجی یک کد تأیید
     *
     * @param string $identifier ایمیل یا شماره موبایل
     * @param string $code کد ارائه شده
     * @param string $type نوع شناسه (email یا phone)
     * @return bool
     */
    public static function validate(string $identifier, string $code, string $type): bool
    {
        if ($type === 'phone' && strpos($identifier, '*') !== false) {
            $originalIdentifier = self::findOriginalIdentifier($identifier, $type);
            if ($originalIdentifier) {
                Log::info('استفاده از شماره تلفن اصلی برای اعتبارسنجی به جای شماره مخفی شده', [
                    'masked' => self::maskSensitiveData($identifier),
                    'original' => self::maskSensitiveData($originalIdentifier)
                ]);
                $identifier = $originalIdentifier;
            }
        }

        $verificationCode = self::where('identifier', $identifier)
            ->where('type', $type)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verificationCode) {
            Log::info("تلاش با کد تأیید نامعتبر", [
                'identifier' => self::maskSensitiveData($identifier),
                'type' => $type,
                'provided_code' => $code
            ]);

            self::where('identifier', $identifier)
                ->where('type', $type)
                ->where('used', false)
                ->where('expires_at', '>', now())
                ->increment('attempts');

            return false;
        }

        if ($verificationCode->attempts >= 5) {
            Log::warning("تعداد تلاش‌های ناموفق برای کد تأیید بیش از حد مجاز", [
                'identifier' => self::maskSensitiveData($identifier),
                'type' => $type,
                'attempts' => $verificationCode->attempts
            ]);

            $verificationCode->update([
                'used' => true
            ]);

            return false;
        }

        $verificationCode->update([
            'used' => true
        ]);

        Log::info("کد تأیید با موفقیت اعتبارسنجی شد", [
            'identifier' => self::maskSensitiveData($identifier),
            'type' => $type
        ]);

        return true;
    }

    /**
     * بررسی امکان ارسال کد جدید با توجه به محدودیت‌های زمانی و تعداد
     *
     * @param string $identifier ایمیل یا شماره موبایل
     * @param string $type نوع شناسه (email یا phone)
     * @param int $limitInSeconds محدودیت زمانی بین درخواست‌ها به ثانیه
     * @param int $maxPerDay حداکثر تعداد درخواست در روز
     * @return true|int|array true اگر ارسال مجاز باشد، وگرنه ثانیه‌های باقی‌مانده یا آرایه‌ای با اطلاعات محدودیت
     */
    public static function canSendNew(string $identifier, string $type, int $limitInSeconds = 300, int $maxPerDay = 10)
    {
        try {
            $originalIdentifier = $identifier;
            if ($type === 'phone' && strpos($identifier, '*') !== false) {
                $foundIdentifier = self::findOriginalIdentifier($identifier, $type);
                if ($foundIdentifier) {
                    Log::info('استفاده از شماره تلفن اصلی برای بررسی محدودیت ارسال به جای شماره مخفی شده', [
                        'masked' => self::maskSensitiveData($identifier),
                        'original' => self::maskSensitiveData($foundIdentifier)
                    ]);
                    $originalIdentifier = $foundIdentifier;
                }
            }

            $lastCode = self::where('identifier', $originalIdentifier)
                ->where('type', $type)
                ->latest('created_at')
                ->first();

            if ($lastCode) {
                $timeSinceLastCode = $lastCode->created_at->diffInSeconds(now());

                if ($timeSinceLastCode < $limitInSeconds) {
                    $remainingSeconds = $limitInSeconds - $timeSinceLastCode;

                    Log::info("محدودیت ارسال برای کد تأیید", [
                        'identifier' => self::maskSensitiveData($originalIdentifier),
                        'type' => $type,
                        'remaining_seconds' => $remainingSeconds,
                        'limit_type' => 'time_between_requests'
                    ]);

                    return $remainingSeconds;
                }
            }

            $todayStart = now()->startOfDay();
            $codesInToday = self::where('identifier', $originalIdentifier)
                ->where('type', $type)
                ->where('created_at', '>=', $todayStart)
                ->count();

            if ($codesInToday >= $maxPerDay) {
                $endOfDay = now()->endOfDay();
                $remainingSeconds = now()->diffInSeconds($endOfDay);

                Log::warning("حداکثر تعداد کدهای تأیید روزانه استفاده شده", [
                    'identifier' => self::maskSensitiveData($originalIdentifier),
                    'type' => $type,
                    'count' => $codesInToday,
                    'remaining_seconds' => $remainingSeconds,
                    'limit_type' => 'daily_limit'
                ]);

                return [
                    'remaining_seconds' => $remainingSeconds > 0 ? $remainingSeconds : 1,
                    'daily_limit_reached' => true,
                    'message' => 'حداکثر تعداد کدهای مجاز در روز استفاده شده است.'
                ];
            }

            return true;
        } catch (\Exception $e) {
            Log::error("خطا در متد canSendNew: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'identifier_type' => $type
            ]);

            return true;
        }
    }

    /**
     * ارسال کد تأیید به کاربر
     *
     * @param string $identifier ایمیل یا شماره موبایل
     * @param string $type نوع شناسه (email یا phone)
     * @param string $code کد تأیید
     * @return bool
     */
    public static function sendCode(string $identifier, string $type, string $code): bool
    {
        try {
            // در محیط توسعه، فقط کد را لاگ می‌کنیم
            if (app()->environment('local', 'development', 'testing')) {
                Log::debug('Development mode - verification code', [
                    'identifier' => $identifier,
                    'type' => $type,
                    'code' => $code
                ]);
                return true;
            }

            if ($type === 'email') {
                return self::sendEmailVerificationCode($identifier, $code);
            } else {
                return self::sendSmsVerificationCode($identifier, $code);
            }
        } catch (Exception $e) {
            Log::error("خطا در ارسال کد تأیید", [
                'identifier' => self::maskSensitiveData($identifier),
                'type' => $type,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * پاکسازی کدهای منقضی شده
     *
     * @param int $olderThanDays حذف کدهای قدیمی‌تر از این تعداد روز
     * @return int تعداد رکوردهای حذف شده
     */
    public static function cleanup(int $olderThanDays = 30): int
    {
        $count = self::where(function ($query) {
            $query->where('used', true)
                ->orWhere('expires_at', '<', now());
        })
            ->where('created_at', '<', now()->subDays($olderThanDays))
            ->delete();

        Log::info("کدهای تأیید قدیمی پاکسازی شدند", ['count' => $count]);

        return $count;
    }

    /**
     * دریافت زمان باقی‌مانده تا انقضای کد به ثانیه
     *
     * @return int
     */
    public function getRemainingTime(): int
    {
        if ($this->expires_at->isPast()) {
            return 0;
        }

        return now()->diffInSeconds($this->expires_at);
    }

    // متدهای خصوصی (private methods)

    /**
     * پیدا کردن شناسه اصلی (بدون ستاره) برای شناسه مخفی شده
     */
    private static function findOriginalIdentifier(string $maskedIdentifier, string $type): ?string
    {
        if (empty($maskedIdentifier) || !strpos($maskedIdentifier, '*')) {
            return $maskedIdentifier;
        }

        try {
            $parts = preg_split('/\*+/', $maskedIdentifier, -1, PREG_SPLIT_NO_EMPTY);

            if (empty($parts)) {
                return null;
            }

            $query = self::where('type', $type)
                ->where('identifier', 'not like', '%*%')
                ->orderBy('created_at', 'desc');

            foreach ($parts as $index => $part) {
                if (empty($part)) continue;

                if ($index === 0) {
                    $query->where('identifier', 'like', $part . '%');
                } elseif ($index === count($parts) - 1) {
                    $query->where('identifier', 'like', '%' . $part);
                } else {
                    $query->where('identifier', 'like', '%' . $part . '%');
                }
            }

            $originalIdentifier = $query->value('identifier');

            if ($originalIdentifier) {
                return $originalIdentifier;
            }

            $estimatedLength = strlen($maskedIdentifier);
            $prefix = $parts[0] ?? '';
            $suffix = $parts[count($parts) - 1] ?? '';

            return self::where('type', $type)
                ->where('identifier', 'not like', '%*%')
                ->where('identifier', 'like', $prefix . '%')
                ->where('identifier', 'like', '%' . $suffix)
                ->whereRaw('LENGTH(identifier) = ?', [$estimatedLength])
                ->orderBy('created_at', 'desc')
                ->value('identifier');
        } catch (\Exception $e) {
            Log::error('خطا در یافتن شناسه اصلی: ' . $e->getMessage(), [
                'masked' => self::maskSensitiveData($maskedIdentifier),
                'type' => $type,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * تولید کد تصادفی با طول مشخص
     */
    private static function generateRandomCode(int $length): string
    {
        return (string) mt_rand(
            (int) pow(10, $length - 1),
            (int) pow(10, $length) - 1
        );
    }

    /**
     * ارسال کد تأیید از طریق ایمیل
     */
    private static function sendEmailVerificationCode(string $email, string $code): bool
    {
        try {
            // چک کردن کلاس ایمیل
            if (class_exists('App\Mail\VerificationCodeMail')) {
                Mail::to($email)->send(new \App\Mail\VerificationCodeMail($code));
                Log::info("ایمیل کد تأیید ارسال شد", ['email' => self::maskSensitiveData($email)]);
                return true;
            }

            // متن ساده ایمیل
            $subject = 'کد تأیید بَلیان';
            $message = "کد تأیید شما: {$code}\n\nاین کد تا 5 دقیقه معتبر است.";

            // ارسال ایمیل به صورت ساده
            $sent = mail($email, $subject, $message);

            if ($sent) {
                Log::info("ایمیل کد تأیید با استفاده از تابع mail ارسال شد", ['email' => self::maskSensitiveData($email)]);
            } else {
                Log::error("خطا در ارسال ایمیل کد تأیید با استفاده از تابع mail", ['email' => self::maskSensitiveData($email)]);
                return false;
            }

            return $sent;
        } catch (Exception $e) {
            Log::error("خطا در ارسال کد تأیید ایمیل", [
                'email' => self::maskSensitiveData($email),
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * ارسال کد تأیید از طریق پیامک
     */
    private static function sendSmsVerificationCode(string $phone, string $code): bool
    {
        try {
            // بررسی درستی فرمت شماره موبایل (ایران)
            if (!preg_match('/^09[0-9]{9}$/', $phone)) {
                Log::warning("فرمت شماره موبایل نامعتبر است", ['phone' => self::maskSensitiveData($phone)]);
                return false;
            }

            // استفاده از سرویس پیامک ثبت شده در کانتینر
            if (app()->bound(SmsServiceInterface::class)) {
                $smsService = app(SmsServiceInterface::class);
                $result = $smsService->sendVerificationCode($phone, $code);

                Log::info("پیامک از طریق سرویس پیامک ارسال شد", [
                    'phone' => self::maskSensitiveData($phone),
                    'success' => $result
                ]);

                return $result;
            }

            // اگر سرویس پیامک فعال نباشد، به عنوان موفقیت برمی‌گردانیم (برای محیط‌های غیر تولید)
            Log::warning("سرویس پیامک پیدا نشد، اما به هر حال ادامه می‌دهیم");
            return true;
        } catch (Exception $e) {
            Log::error("خطا در ارسال کد تأیید پیامکی", [
                'phone' => self::maskSensitiveData($phone),
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * مخفی کردن اطلاعات حساس برای لاگ کردن
     */
    private static function maskSensitiveData(string $data): string
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
