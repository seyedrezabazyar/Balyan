<?php

namespace App\Services;

use App\Models\User;

class UsernameService
{
    /**
     * ایجاد نام کاربری استاندارد براساس نوع و مقدار شناسه
     *
     * @param string $identifier
     * @param string $identifierType
     * @return string
     */
    public function generateStandardUsername($identifier, $identifierType)
    {
        if ($identifierType === 'email') {
            $username = explode('@', $identifier)[0];
            $username = preg_replace('/[^a-zA-Z0-9]/', '', $username);

            if (strlen($username) < 5) {
                $username .= rand(1000, 9999);
            }
        } else {
            $username = preg_replace('/[^0-9]/', '', $identifier);
            $username = substr($username, -8);
            $username = 'user_' . $username;
        }

        $originalUsername = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * ایجاد نام کاربری منحصر به فرد براساس شناسه کاربر
     *
     * @param string $identifier
     * @return string
     */
    public function generateUniqueUsername($identifier)
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
