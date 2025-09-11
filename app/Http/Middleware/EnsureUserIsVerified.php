<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->is_verified == 0 || Auth::user()->is_verified == null)) {
            return redirect()->route('send-otp');
        }
        return $next($request);
    }
}
