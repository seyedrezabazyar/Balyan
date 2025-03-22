<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
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
        'access_token_hash'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'access_token_hash', //  این رو هم مخفی کنید
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime', // اضافه شد
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed', // اگر از لاراول 10 به بالا استفاده می‌کنید، نیازی به این نیست
    ];

    /**
     * Route notifications for the mail channel.
     *
     * @return  array<string, string>|string
     */
    public function routeNotificationForMail(): array|string
    {
        // اگر کاربر هم ایمیل و هم نام داشته باشه، از این فرمت استفاده می‌کنیم:
        if ($this->email && $this->first_name) {
            return [$this->email => $this->first_name];
        }
        // در غیر این صورت، فقط ایمیل رو برمی‌گردونیم
        return $this->email;
    }    /**
 * Route notifications for the Vonage channel.
 *
 * @return string
 */
    public function routeNotificationForVonage(): string
    {
        return $this->phone;
    }
}
