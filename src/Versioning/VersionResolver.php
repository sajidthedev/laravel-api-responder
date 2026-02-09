<?php

declare(strict_types=1);

namespace ApiResponder\Versioning;

use Illuminate\Http\Request;

class VersionResolver
{
    public function __construct(private readonly Request $request)
    {
    }

    public function resolve(): ApiVersion
    {
        $header = config('api_responder.header', 'X-API-Version');
        $version = $this->request->header($header)
            ?? $this->request->header('Accept-Version')
            ?? config('api_responder.default', 'v1');

        return ApiVersion::fromString((string) $version);
    }
}
