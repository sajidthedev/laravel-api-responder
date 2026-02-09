<?php

declare(strict_types=1);

namespace ApiResponder\Http\Responses;

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
