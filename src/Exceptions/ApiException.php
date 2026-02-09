<?php

declare(strict_types=1);

namespace ApiResponder\Exceptions;

use Exception;
use Throwable;

class ApiException extends Exception
{
    /**
     * Create a new ApiException instance.
     *
     * @param string $message
     * @param string $errorCode
     * @param int $status
     * @param array $details
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = "",
        public readonly string $errorCode = 'INTERNAL_ERROR',
        public readonly int $status = 400,
        public readonly array $details = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $status, $previous);
    }
}
