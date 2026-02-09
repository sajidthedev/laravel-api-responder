<?php

declare(strict_types=1);

namespace ApiResponder\ErrorCodes\Providers;

use ApiResponder\Contracts\ErrorCodeProvider;

final class ConfigErrorCodeProvider implements ErrorCodeProvider
{
    /**
     * @param array<string, array{message?: string, status?: int}> $codes
     */
    public function __construct(
        private readonly array $codes
    ) {
    }

    /**
     * @return array<string, array{message?: string, status?: int}>
     */
    public function all(): array
    {
        return $this->codes;
    }
}
