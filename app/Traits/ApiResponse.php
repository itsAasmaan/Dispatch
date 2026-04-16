<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Success response
     */
    protected function success(
        mixed $data = null,
        string $message = 'Request successful',
        int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], $statusCode);
    }

    /**
     * Created response (201)
     */
    protected function created(
        mixed $data = null,
        string $message = 'Resource created successfully'
    ) {
        return $this->success($data, $message, 201);
    }

    /**
     * Error response
     */
    protected function error(
        string $message = 'Something went wrong',
        int $statusCode = 400,
        mixed $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * Validation error response (422)
     */
    protected function validationError(mixed $errors): JsonResponse
    {
        return $this->error('Validation failed', 422, $errors);
    }

    /**
     * Not found response (404)
     */
    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, 404);
    }

    /**
     * Unauthorized response (401)
     */
    protected function unauthorized(string $message = 'Unauthenticated'): JsonResponse
    {
        return $this->error($message, 401);
    }

    /**
     * Forbidden response (403)
     */
    protected function forbidden(string $message = 'Unauthorized action'): JsonResponse
    {
        return $this->error($message, 403);
    }
}