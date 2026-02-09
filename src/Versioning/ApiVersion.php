<?php

declare(strict_types=1);

namespace ApiResponder\Versioning;

enum ApiVersion: string
{
    case V1 = 'v1';
    case V2 = 'v2';

    public static function fromString(string $version): self
    {
        return self::tryFrom(strtolower($version)) ?? self::V1;
    }
}
