<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'username_changed_at',
        'first_name',
        'last_name',
        'display_name',
        'email',
        'phone',
        'password',
        'access_level_id',
        'profile_image',
        'is_active',
        'last_login_at',
        'access_token_hash',
        'email_verified_at',
        'phone_verified_at',
        'wallet_balance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'access_token_hash',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'username_changed_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
        'wallet_balance' => 'float',
    ];

    /**
     * Route notifications for the mail channel.
     *
     * @return array<string, string>|string
     */
    public function routeNotificationForMail(): array|string
    {
        if ($this->email && $this->first_name) {
            return [$this->email => $this->first_name];
        }
        return $this->email;
    }

    /**
     * Route notifications for the Vonage channel.
     *
     * @return string
     */
    public function routeNotificationForVonage(): string
    {
        return $this->phone;
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the user's display name or full name.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->display_name ?: "{$this->first_name} {$this->last_name}";
    }

    /**
     * Check if the user has verified email.
     *
     * @return bool
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Check if the user has verified phone.
     *
     * @return bool
     */
    public function hasVerifiedPhone(): bool
    {
        return !is_null($this->phone_verified_at);
    }

    /**
     * Check if the user can change their email.
     *
     * @return bool
     */
    public function canChangeEmail(): bool
    {
        return is_null($this->email_verified_at);
    }

    /**
     * Check if the user can change their phone.
     *
     * @return bool
     */
    public function canChangePhone(): bool
    {
        return is_null($this->phone_verified_at);
    }

    /**
     * Get the profile image URL.
     *
     * @return string
     */
    public function getProfileImageUrlAttribute(): string
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }

        return asset('images/default-avatar.png');
    }

    /**
     * Check if the user can change their username.
     *
     * @return bool
     */
    public function canChangeUsername(): bool
    {
        // اگر کاربر قبلاً نام کاربری خود را تغییر نداده باشد
        if (!$this->username_changed_at) {
            return true;
        }

        // بررسی گذشت یک ماه از آخرین تغییر
        $lastChange = Carbon::parse($this->username_changed_at);
        $oneMonthLater = $lastChange->addMonth();

        return Carbon::now()->gte($oneMonthLater);
    }

    /**
     * Get days until the user can change their username again.
     *
     * @return int
     */
    public function daysUntilUsernameChange(): int
    {
        // اگر کاربر قبلاً نام کاربری خود را تغییر نداده باشد
        if (!$this->username_changed_at) {
            return 0;
        }

        $lastChange = Carbon::parse($this->username_changed_at);
        $nextChangeDate = $lastChange->addMonth();

        // اگر زمان تغییر مجدد فرا رسیده باشد
        if (Carbon::now()->gte($nextChangeDate)) {
            return 0;
        }

        return Carbon::now()->diffInDays($nextChangeDate);
    }

    /**
     * Books that belong to the user.
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Orders that belong to the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Favorite books of the user.
     */
    public function favoriteBooks()
    {
        return $this->books()->where('is_favorite', true);
    }

    /**
     * Format phone number to standard format (09123456789)
     *
     * @param string $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        if ($value) {
            // حذف کاراکترهای اضافی
            $phone = preg_replace('/[^0-9]/', '', $value);

            // استانداردسازی شماره
            if (substr($phone, 0, 3) === '989' || substr($phone, 0, 2) === '89') {
                $phone = '0' . substr($phone, -10);
            } elseif (substr($phone, 0, 1) === '9' && strlen($phone) === 10) {
                $phone = '0' . $phone;
            }

            $this->attributes['phone'] = $phone;
        } else {
            $this->attributes['phone'] = null;
        }
    }
}
