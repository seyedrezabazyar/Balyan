<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait AuthSessionTrait
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
            Log::error('Failed to decrypt auth session data: ' . $e->getMessage());
            return null;
        }
    }
}
