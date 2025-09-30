<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Helper class for generating standardized API responses.
 */
class ResponseHelper
{
    /**
     * Generate a standardized success response.
     *
     * @param mixed $data The payload to return.
     * @param string $message A success message.
     * @param int $statusCode The HTTP status code.
     * @return JsonResponse
     */
    public static function success($data = [], string $message = 'Success', int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Generate a standardized error response.
     *
     * @param string $message The primary error message.
     * @param int $statusCode The HTTP status code.
     * @param mixed|null $errors Additional error details or validation messages.
     * @return JsonResponse
     */
    public static function error(string $message, int $statusCode = Response::HTTP_BAD_REQUEST, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Generate a response for a not found resource.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFound(string $message = 'The requested resource was not found.'): JsonResponse
    {
        return self::error($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Generate a response for an unauthorized action.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'You are not authorized to perform this action.'): JsonResponse
    {
        return self::error($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Generate a response for a server-side error.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function serverError(string $message = 'A server error occurred.'): JsonResponse
    {
        return self::error($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}