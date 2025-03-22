<?php

namespace App\Http\Middleware;

use App\Models\VerificationCode;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyCode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // استخراج پارامترها از درخواست
        $identifier = $request->input('identifier');
        $type = $request->input('type', 'phone'); // پیش‌فرض: شماره تلفن
        $code = $request->input('code');

        if (!$identifier || !$code) {
            Log::warning('Verification code missing parameters', [
                'identifier' => $identifier,
                'type' => $type,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'شناسه کاربر و کد تأیید الزامی است'
            ], 422);
        }

        // بررسی صحت کد تأیید
        $isValid = VerificationCode::validate($identifier, $code, $type);

        if (!$isValid) {
            Log::warning('Invalid verification code', [
                'identifier' => $identifier,
                'type' => $type,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'کد تأیید نامعتبر یا منقضی شده است'
            ], 422);
        }

        // اضافه کردن اطلاعات تأیید به درخواست
        $request->merge([
            'verified' => true,
            'verified_identifier' => $identifier,
            'verified_type' => $type
        ]);

        return $next($request);
    }
}
