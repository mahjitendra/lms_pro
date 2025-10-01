<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        // For an API, we don't want to redirect. We want to return a JSON error.
        // The exception handler (Handler.php) will catch the AuthenticationException
        // and convert it into a 401 JSON response.
        // So, we just need to ensure this doesn't return a route for API requests.
        return $request->expectsJson() ? null : route('login');
    }
}