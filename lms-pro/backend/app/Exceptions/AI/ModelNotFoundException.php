<?php

namespace App\Exceptions\AI;

use Exception;
use Throwable;

class ModelNotFoundException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "The requested AI model was not found.", int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     *
     * This method can be used to log the exception or send it to an external service.
     *
     * @return void
     */
    public function report()
    {
        // You can add custom logging here if needed.
        // For example: \Log::error('AI Model Not Found: ' . $this->getMessage());
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'error' => 'Model Not Found',
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}