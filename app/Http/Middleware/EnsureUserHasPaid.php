<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasPaid
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->status == 0 || Auth::user()->status == null)) {
            return redirect()->route('stripe.payment');
        }
        return $next($request);

    }
}
