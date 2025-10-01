<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;

class RateLimitAI
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Determine the rate limit key. It's best to key it by user ID.
        $key = 'ai_requests_' . $user->id;

        // Determine the allowed number of requests per minute based on user's plan/role.
        // This is a professional approach to tiered API access.
        if ($user->hasRole('pro_user')) {
            $maxRequests = 100;
        } else {
            $maxRequests = 20;
        }

        // Check if the user has too many attempts.
        if ($this->limiter->tooManyAttempts($key, $maxRequests)) {
            $retryAfter = $this->limiter->availableIn($key);
            return ResponseHelper::error(
                'Too Many Requests. Please try again later.',
                429, // HTTP 429 Too Many Requests
                ['retry_after' => $retryAfter]
            );
        }

        // If not, hit the limiter for this request.
        $this->limiter->hit($key);

        // Add rate limit headers to the response so clients know their limits.
        $response = $next($request);
        $response->headers->add([
            'X-RateLimit-Limit' => $maxRequests,
            'X-RateLimit-Remaining' => $this->limiter->retriesLeft($key, $maxRequests),
        ]);

        return $response;
    }
}