<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;

class ValidateAPIKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');

        if (!$apiKey) {
            return ResponseHelper::error('API Key is missing.', 401);
        }

        // In a real application, you would look up the API key in a dedicated database table.
        // For this example, we will check against a comma-separated list in the .env file.
        $validKeys = explode(',', config('app.authorized_api_keys'));

        if (!in_array($apiKey, $validKeys)) {
            return ResponseHelper::error('Invalid API Key.', 401);
        }

        // Optional: You could also associate the API key with a specific user or "service account"
        // and authenticate them here.
        // $serviceAccount = User::where('api_key', $apiKey)->first();
        // Auth::login($serviceAccount);

        return $next($request);
    }
}