<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\AuthUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    use AuthUtils;

    /**
     * ایجاد کاربر جدید
     *
     * @param array $userData
     * @return User
     */
    public function createUser($userData)
    {
        Log::info('Creating new user from registration controller', [
            'userData' => $userData
        ]);

        $userData['password'] = Hash::make(Str::random(12));

        if (!isset($userData['username'])) {
            $identifier = $userData['email'] ?? $userData['phone'] ?? null;
            $userData['username'] = $this->generateUniqueUsername($identifier);
        }

        $user = User::create($userData);

        Log::info('User created', ['user_id' => $user->id]);

        return $user;
    }

    /**
     * ایجاد نام کاربری منحصر به فرد براساس شناسه کاربر
     *
     * @param string $identifier
     * @return string
     */
    protected function generateUniqueUsername($identifier)
    {
        $baseUsername = preg_replace('/[^a-zA-Z0-9]/', '', $identifier);
        $baseUsername = strtolower(substr($baseUsername, 0, 10));

        $username = $baseUsername . '_' . substr(uniqid(), -5);

        $count = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . substr(uniqid(), -5) . $count;
            $count++;
        }

        return $username;
    }
}
