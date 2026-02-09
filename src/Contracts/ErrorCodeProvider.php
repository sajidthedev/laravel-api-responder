<?php

declare(strict_types=1);

namespace ApiResponder\Contracts;

interface ErrorCodeProvider
{
    /**
     * Get all registered error codes.
     *
     * @return array<string, array{message?: string, status?: int}>
     */
    public function all(): array;
}
