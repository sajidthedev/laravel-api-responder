<?php

declare(strict_types=1);

namespace ApiResponder\Http\Responses;

use ApiResponder\ErrorCodes\ErrorCodeRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    /**
     * Create a successful JSON response.
     *
     * @param mixed $data
     * @param array $meta
     * @param int $status
     * @return JsonResponse
     */
    public static function success(mixed $data = null, array $meta = [], int $status = 200): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'data' => $data,
            'meta' => $meta,
        ], $status);
    }

    /**
     * Create an error JSON response.
     *
     * @param string $code
     * @param string $message
     * @param array $details
     * @param int $status
     * @return JsonResponse
     */
    public static function error(string $code, string $message, array $details = [], int $status = 400): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => $details,
            ],
        ], $status);
    }

    /**
     * Create an error JSON response from a registered error code.
     *
     * @param string $code
     * @param string|null $message
     * @param array $details
     * @param int|null $status
     * @param array $meta
     * @return JsonResponse
     */
    public static function errorFromCode(
        string $code,
        ?string $message = null,
        array $details = [],
        ?int $status = null,
        array $meta = []
    ): JsonResponse {
        $resolvedMessage = $message;
        $resolvedStatus = $status;

        try {
            /** @var ErrorCodeRegistry|null $registry */
            $registry = app(ErrorCodeRegistry::class);

            if ($registry && $registry->has($code)) {
                $errorCode = $registry->get($code);
                $resolvedMessage ??= $errorCode->defaultMessage ?? $code;
                $resolvedStatus ??= $errorCode->defaultStatus;
            }
        } catch (\Throwable) {
            // Container or registry resolution failed
        }

        return new JsonResponse([
            'success' => false,
            'data' => null,
            'meta' => $meta,
            'error' => [
                'code' => $code,
                'message' => $resolvedMessage ?? $code,
                'details' => $details,
            ],
        ], $resolvedStatus ?? 400);
    }

    /**
     * Create a paginated success response.
     *
     * @param LengthAwarePaginator $paginator
     * @param callable|null $transformer
     * @return JsonResponse
     */
    public static function paginated(LengthAwarePaginator $paginator, ?callable $transformer = null): JsonResponse
    {
        $items = $transformer
            ? array_map($transformer, $paginator->items())
            : $paginator->items();

        return self::success($items, [
            'page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ]);
    }
}
