<?php

declare(strict_types=1);

namespace ApiResponder\Versioning;

use ApiResponder\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class VersionedResponse
{
    public function __construct(
        private readonly VersionResolver $resolver
    ) {
    }

    public function success(mixed $data = null, array $meta = [], int $status = 200): JsonResponse
    {
        $version = $this->resolver->resolve();
        $response = ApiResponse::success($data, $meta, $status);

        return $this->applyVersioning($response, $version);
    }

    public function error(string $code, string $message, array $details = [], int $status = 400): JsonResponse
    {
        $version = $this->resolver->resolve();
        $response = ApiResponse::error($code, $message, $details, $status);

        return $this->applyVersioning($response, $version);
    }

    public function paginated(LengthAwarePaginator $paginator, ?callable $transformer = null): JsonResponse
    {
        $version = $this->resolver->resolve();
        $response = ApiResponse::paginated($paginator, $transformer);

        return $this->applyVersioning($response, $version);
    }

    protected function applyVersioning(JsonResponse $response, ApiVersion $version): JsonResponse
    {
        $data = $response->getData(true);

        // Example: Add version info to meta in V2, or change structure
        if ($version === ApiVersion::V2) {
            $data['version'] = '2.0';
        } else {
            $data['version'] = '1.0';
        }

        $response->setData($data);

        return $response;
    }
}
