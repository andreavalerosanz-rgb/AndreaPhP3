<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ClearOtherGuards
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('corporate')->logout();
            Auth::guard('web')->logout();
        }

        if (Auth::guard('corporate')->check()) {
            Auth::guard('admin')->logout();
            Auth::guard('web')->logout();
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('admin')->logout();
            Auth::guard('corporate')->logout();
        }
        return $next($request);
    }
}
