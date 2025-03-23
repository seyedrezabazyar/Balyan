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
        // ثبت لاگ ابتدایی
        Log::debug('Generating verification code', [
            'identifier' => $identifier,
            'type' => $type,
            'length' => $codeLength,
            'expiry' => $expiresInMinutes
        ]);

        // تولید کد تصادفی
        $code = self::generateRandomCode($codeLength);

        // محاسبه زمان انقضا
        $expiresAt = now()->addMinutes($expiresInMinutes);

        // ایجاد رکورد جدید
        $verificationCode = self::create([
            'identifier' => $identifier,
            'type' => $type,
            'code' => $code,
            'expires_at' => $expiresAt,
            'used' => false,
            'attempts' => 0
        ]);

        Log::info("Generated verification code", [
            'identifier' => $identifier,
            'type' => $type,
            'code' => $code, // در محیط واقعی این لاگ را حذف کنید
            'expires_at' => $expiresAt->format('Y-m-d H:i:s')
        ]);

        return $verificationCode;
    }

    /**
     * تولید کد تصادفی با طول مشخص
     *
     * @param int $length طول کد
     * @return string
     */
    protected static function generateRandomCode(int $length): string
    {
        // روش اول: استفاده از اعداد تصادفی
        return (string) mt_rand(
            (int) pow(10, $length - 1),
            (int) pow(10, $length) - 1
        );

        // روش دوم: تولید کد با کاراکترهای تصادفی
        // $characters = '0123456789';
        // $code = '';
        // for ($i = 0; $i < $length; $i++) {
        //     $code .= $characters[random_int(0, strlen($characters) - 1)];
        // }
        // return $code;
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
        // پیدا کردن کد تأیید معتبر و منقضی نشده
        $verificationCode = self::where('identifier', $identifier)
            ->where('type', $type)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verificationCode) {
            Log::info("Invalid verification code attempt", [
                'identifier' => $identifier,
                'type' => $type,
                'provided_code' => $code
            ]);

            // افزودن تعداد تلاش‌های ناموفق برای همه کدهای فعال این شناسه
            self::where('identifier', $identifier)
                ->where('type', $type)
                ->where('used', false)
                ->where('expires_at', '>', now())
                ->increment('attempts');

            return false;
        }

        // بررسی تعداد تلاش‌های ناموفق
        if ($verificationCode->attempts >= 5) {
            Log::warning("Too many unsuccessful attempts for verification code", [
                'identifier' => $identifier,
                'type' => $type,
                'attempts' => $verificationCode->attempts
            ]);

            // غیرفعال کردن کد بعد از تلاش‌های زیاد
            $verificationCode->update([
                'used' => true
            ]);

            return false;
        }

        // علامت‌گذاری کد به عنوان استفاده شده
        $verificationCode->update([
            'used' => true
        ]);

        Log::info("Verification code validated successfully", [
            'identifier' => $identifier,
            'type' => $type
        ]);

        return true;
    }

    /**
     * بررسی اینکه آیا می‌توان کد جدیدی ارسال کرد یا خیر
     *
     * @param string $identifier ایمیل یا شماره موبایل
     * @param string $type نوع شناسه (email یا phone)
     * @param int $limitInSeconds محدودیت زمانی بین درخواست‌ها به ثانیه (پیش‌فرض: 60)
     * @param int $maxPerDay حداکثر تعداد درخواست در روز (پیش‌فرض: 10)
     * @return true|int true اگر ارسال مجاز باشد، وگرنه ثانیه‌های باقی‌مانده
     */
    public static function canSendNew(string $identifier, string $type, int $limitInSeconds = 300, int $maxPerDay = 10)
    {
        // بررسی زمان آخرین کد ارسال شده
        $lastCode = self::where('identifier', $identifier)
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastCode) {
            // محاسبه زمان بین درخواست فعلی و آخرین درخواست
            $timeSinceLastCode = $lastCode->created_at->diffInSeconds(now());

            // اگر کمتر از محدودیت زمانی است
            if ($timeSinceLastCode < $limitInSeconds) {
                return $limitInSeconds - $timeSinceLastCode;
            }
        }

        // بررسی تعداد کدهای ارسال شده در 24 ساعت گذشته
        $codesInLastDay = self::where('identifier', $identifier)
            ->where('type', $type)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($codesInLastDay >= $maxPerDay) {
            Log::warning("Maximum daily verification codes reached", [
                'identifier' => $identifier,
                'type' => $type,
                'count' => $codesInLastDay
            ]);

            // محاسبه زمان باقی‌مانده تا ریست محدودیت روزانه
            $oldestInDay = self::where('identifier', $identifier)
                ->where('type', $type)
                ->where('created_at', '>=', now()->subDay())
                ->orderBy('created_at', 'asc')
                ->first();

            if ($oldestInDay) {
                $resetTime = $oldestInDay->created_at->addDay()->diffInSeconds(now());
                return $resetTime > 0 ? $resetTime : 1;
            }

            return 86400; // 24 ساعت به ثانیه
        }

        return true;
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
        // حذف کدهای منقضی شده و قدیمی
        $count = self::where(function ($query) {
            $query->where('used', true)
                ->orWhere('expires_at', '<', now());
        })
            ->where('created_at', '<', now()->subDays($olderThanDays))
            ->delete();

        Log::info("Cleaned up old verification codes", ['count' => $count]);

        return $count;
    }
}
