<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  $requiredRoleId
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $requiredRoleId)
    {
        $user = Auth::user();

        // Redirect if not logged in
        if (!$user) {
            return redirect()->route('login.show')->withErrors('You must be logged in to access this page.');
        }

        // Check if user is verified
        if ($user->is_verified == 0 || $user->is_verified === null) {
            return redirect()->route('send-otp');
        }

        // Check if user has paid
        if ($user->status == 0 || $user->status === null) {
            return redirect()->route('stripe.payment');
        }

        // Get the active role from pivot
        $activeRole = $user->activeRole();

        // If the user's current active role does not match the required role
        if (!$activeRole || $activeRole->id != (int)$requiredRoleId) {
            // Redirect based on actual active role
            switch ($activeRole?->id) {
                case 1:
                    return redirect()->route('superadmin.dashboard')->withErrors('Unauthorized access – Super Admin only.');
                case 2:
                    return redirect()->route('admin.dashboard')->withErrors('Unauthorized access – Admin only.');
                case 3:
                    return redirect()->route('user.dashboard')->withErrors('Unauthorized access – User only.');
                default:
                    return redirect()->route('login.show')->withErrors('Unauthorized access.');
            }
        }

        return $next($request);
    }
}
