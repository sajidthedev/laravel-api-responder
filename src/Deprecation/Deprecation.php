<?php

declare(strict_types=1);

namespace ApiResponder\Deprecation;

use DateTimeImmutable;

final class Deprecation
{
    public function __construct(
        public readonly string $key,
        public readonly ?DateTimeImmutable $sunsetAt = null,
        public readonly ?string $link = null,
        public readonly ?string $message = null
    ) {
    }
}
