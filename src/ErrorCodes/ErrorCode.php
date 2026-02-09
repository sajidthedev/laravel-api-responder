<?php

declare(strict_types=1);

namespace ApiResponder\ErrorCodes;

final class ErrorCode
{
    public function __construct(
        public readonly string $code,
        public readonly ?string $defaultMessage = null,
        public readonly int $defaultStatus = 400
    ) {
    }
}
