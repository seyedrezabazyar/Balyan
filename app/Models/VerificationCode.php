<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class VerificationCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
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
     * The attributes that should be cast.
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
     * @param int $codeLength طول کد تأیید (پیش‌فرض: 6)
     * @param int $expiresInMinutes مدت زمان اعتبار کد به دقیقه (پیش‌فرض: 15)
     * @return self
     */
    public static function generateFor(string $identifier, string $type, int $codeLength = 6, int $expiresInMinutes = 5): self
    {
        if ($type === 'phone' && strpos($identifier, '*') !== false) {
            $originalIdentifier = self::findOriginalIdentifier($identifier, $type);
            if ($originalIdentifier) {
                Log::info('Using original phone number instead of masked one', [
                    'masked' => self::maskSensitiveData($identifier),
                    'original' => self::maskSensitiveData($originalIdentifier)
                ]);
                $identifier = $originalIdentifier;
            }
        }

        Log::debug('Generating verification code', [
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

        Log::info("Generated verification code", [
            'identifier' => self::maskSensitiveData($identifier),
            'type' => $type,
            'code' => $code,
            'expires_at' => $expiresAt->format('Y-m-d H:i:s')
        ]);

        return $verificationCode;
    }

    /**
     * پیدا کردن شناسه اصلی (بدون ستاره) برای شناسه مخفی شده
     *
     * @param string $maskedIdentifier شناسه مخفی شده با ستاره
     * @param string $type نوع شناسه
     * @return string|null شناسه اصلی یا null اگر پیدا نشد
     */
    protected static function findOriginalIdentifier(string $maskedIdentifier, string $type): ?string
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
            Log::error('Error finding original identifier: ' . $e->getMessage(), [
                'masked' => self::maskSensitiveData($maskedIdentifier),
                'type' => $type,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * تولید کد تصادفی با طول مشخص
     *
     * @param int $length طول کد
     * @return string
     */
    protected static function generateRandomCode(int $length): string
    {
        return (string) mt_rand(
            (int) pow(10, $length - 1),
            (int) pow(10, $length) - 1
        );
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
                Log::info('Using original phone number for validation instead of masked one', [
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
            Log::info("Invalid verification code attempt", [
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
            Log::warning("Too many unsuccessful attempts for verification code", [
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

        Log::info("Verification code validated successfully", [
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
     * @param int $limitInSeconds محدودیت زمانی بین درخواست‌ها به ثانیه (پیش‌فرض: 300)
     * @param int $maxPerDay حداکثر تعداد درخواست در روز (پیش‌فرض: 10)
     * @return true|int|array true اگر ارسال مجاز باشد، وگرنه ثانیه‌های باقی‌مانده یا آرایه‌ای با اطلاعات محدودیت
     */
    public static function canSendNew(string $identifier, string $type, int $limitInSeconds = 300, int $maxPerDay = 10)
    {
        try {
            $originalIdentifier = $identifier;
            if ($type === 'phone' && strpos($identifier, '*') !== false) {
                $foundIdentifier = self::findOriginalIdentifier($identifier, $type);
                if ($foundIdentifier) {
                    Log::info('Using original phone number for rate limiting instead of masked one', [
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

                    Log::info("Rate limit for verification code", [
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

                Log::warning("Maximum daily verification codes reached", [
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
            Log::error("Error in canSendNew method: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'identifier_type' => $type
            ]);

            return true;
        }
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

    /**
     * پاکسازی کدهای منقضی شده
     *
     * @param int $olderThanDays حذف کدهای قدیمی‌تر از این تعداد روز (پیش‌فرض: 30)
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

        Log::info("Cleaned up old verification codes", ['count' => $count]);

        return $count;
    }

    /**
     * مخفی کردن اطلاعات حساس برای لاگ کردن
     *
     * @param string $data داده حساس
     * @return string داده مخفی شده
     */
    protected static function maskSensitiveData(string $data): string
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
