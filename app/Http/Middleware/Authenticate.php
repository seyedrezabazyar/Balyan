<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            // اگر کاربر لاگین نکرده، به صفحه لاگین هدایت کنید
            return redirect()->route('login')->with('error', 'برای دسترسی به این صفحه باید وارد حساب کاربری خود شوید');
        }

        return $next($request);
    }
}
