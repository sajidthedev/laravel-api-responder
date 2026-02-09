<?php

declare(strict_types=1);

namespace ApiResponder\Exceptions;

use ApiResponder\Http\Responses\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

trait HandlesApiExceptions
{
    /**
     * Handle predefined API exceptions and return a standardized JSON response.
     */
    public function handleApiExceptions(Throwable $e): ?JsonResponse
    {
        if ($e instanceof ValidationException) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'The given data was invalid.',
                $e->errors(),
                422
            );
        }

        if ($e instanceof ModelNotFoundException) {
            return ApiResponse::error(
                'NOT_FOUND',
                'The requested resource was not found.',
                [],
                404
            );
        }

        if ($e instanceof AuthorizationException) {
            return ApiResponse::error(
                'UNAUTHORIZED',
                'This action is unauthorized.',
                [],
                403
            );
        }

        if ($e instanceof ApiException) {
            return ApiResponse::error(
                $e->errorCode,
                $e->getMessage(),
                $e->details,
                $e->status
            );
        }

        return null;
    }
}
