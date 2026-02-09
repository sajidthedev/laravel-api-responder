<?php

declare(strict_types=1);

namespace ApiResponder\Contracts;

interface ResponseFormatter
{
    /**
     * Format a success response.
     *
     * @param mixed $data
     * @param array $meta
     * @return array
     */
    public function success(mixed $data, array $meta): array;

    /**
     * Format an error response.
     *
     * @param string $code
     * @param string $message
     * @param array $details
     * @param array $meta
     * @return array
     */
    public function error(string $code, string $message, array $details, array $meta = []): array;
}
