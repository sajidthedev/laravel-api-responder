<?php

declare(strict_types=1);

namespace ApiResponder\Contracts;

use Illuminate\Http\Request;

interface VersionResolver
{
    /**
     * Resolve the API version from the request.
     *
     * @param Request $request
     * @return string
     */
    public function resolve(Request $request): string;
}
