<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use App\Exceptions\AI\ModelNotFoundException as AIModelNotFoundException;
use App\Exceptions\AI\PredictionException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                // Handle our custom AI exceptions first
                if ($e instanceof AIModelNotFoundException || $e instanceof PredictionException) {
                    return $e->render($request);
                }

                // Handle general model not found for API routes
                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException || $e instanceof NotFoundHttpException) {
                    return response()->json([
                        'error' => 'Resource Not Found',
                        'message' => 'The requested resource could not be found.'
                    ], 404);
                }

                // Handle validation exceptions
                if ($e instanceof ValidationException) {
                    return response()->json([
                        'error' => 'Validation Failed',
                        'message' => 'The given data was invalid.',
                        'errors' => $e->validator->errors(),
                    ], 422);
                }

                // Handle authentication exceptions
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json([
                        'error' => 'Unauthenticated',
                        'message' => 'You must be authenticated to access this resource.'
                    ], 401);
                }

                // Handle authorization exceptions
                if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    return response()->json([
                        'error' => 'Forbidden',
                        'message' => $e->getMessage() ?: 'You do not have permission to perform this action.'
                    ], 403);
                }

                // Default to a generic 500 server error
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                return response()->json([
                    'error' => 'Server Error',
                    'message' => config('app.debug') ? $e->getMessage() : 'A server error occurred.',
                ], $statusCode);
            }
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}