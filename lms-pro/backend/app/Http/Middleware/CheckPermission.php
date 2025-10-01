<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!Auth::check()) {
            return ResponseHelper::error('Unauthenticated.', 401);
        }

        $user = Auth::user();

        // The HasRoles trait provides the hasPermissionTo() method,
        // which checks for both direct permissions and permissions inherited from roles.
        if (!$user->hasPermissionTo($permission)) {
            return ResponseHelper::unauthorized('You do not have the required permission to perform this action.');
        }

        return $next($request);
    }
}