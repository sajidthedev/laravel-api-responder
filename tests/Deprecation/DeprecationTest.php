<?php

declare(strict_types=1);

namespace ApiResponder\Tests\Deprecation;

use ApiResponder\Deprecation\Deprecation;
use ApiResponder\Tests\TestCase;
use DateTimeImmutable;

class DeprecationTest extends TestCase
{
    public function test_it_stores_values_correctly(): void
    {
        $sunsetAt = new DateTimeImmutable('2026-12-31T00:00:00Z');
        $deprecation = new Deprecation(
            key: 'api.v1.users',
            sunsetAt: $sunsetAt,
            link: 'https://api.example.com/docs/v2',
            message: 'Use v2 instead'
        );

        $this->assertSame('api.v1.users', $deprecation->key);
        $this->assertSame($sunsetAt, $deprecation->sunsetAt);
        $this->assertSame('https://api.example.com/docs/v2', $deprecation->link);
        $this->assertSame('Use v2 instead', $deprecation->message);
    }

    public function test_it_accepts_nullable_values(): void
    {
        $deprecation = new Deprecation(key: 'api.v1.users');

        $this->assertSame('api.v1.users', $deprecation->key);
        $this->assertNull($deprecation->sunsetAt);
        $this->assertNull($deprecation->link);
        $this->assertNull($deprecation->message);
    }
}
