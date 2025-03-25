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
        'access_token_hash',
        'email_verified_at',
        'phone_verified_at'
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
        'is_active' => 'boolean',
        'password' => 'hashed',
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
}
