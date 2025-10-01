<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            // This should ideally be caught by the 'auth' middleware first, but it's a good safeguard.
            return ResponseHelper::error('Unauthenticated.', 401);
        }

        $user = Auth::user();

        // The HasRoles trait provides the hasRole() method.
        if (!$user->hasRole($roles)) {
            // If the user does not have any of the required roles, return a forbidden response.
            return ResponseHelper::unauthorized('You do not have the required role to access this resource.');
        }

        return $next($request);
    }
}