<?php

namespace App\Exceptions\AI;

use Exception;
use Throwable;

class PredictionException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "An error occurred during the prediction process.", int $code = 500, ?Throwable $previous = null)
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
        // Add specific logging for prediction failures.
        // It's often useful to log the context or input that caused the failure.
        // \Log::error('AI Prediction Failed: ' . $this->getMessage());
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
            'error' => 'Prediction Failed',
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}