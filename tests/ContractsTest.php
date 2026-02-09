<?php

declare(strict_types=1);

namespace ApiResponder\Tests;

use ApiResponder\Contracts\ErrorCodeProvider;
use ApiResponder\Contracts\ResponseFormatter;
use ApiResponder\Contracts\VersionResolver;
use ReflectionClass;
use ReflectionMethod;

class ContractsTest extends TestCase
{
    public function test_interfaces_exist(): void
    {
        $this->assertTrue(interface_exists(ResponseFormatter::class), 'ResponseFormatter interface should exist');
        $this->assertTrue(interface_exists(VersionResolver::class), 'VersionResolver interface should exist');
        $this->assertTrue(interface_exists(ErrorCodeProvider::class), 'ErrorCodeProvider interface should exist');
    }

    public function test_response_formatter_method_signatures(): void
    {
        $reflection = new ReflectionClass(ResponseFormatter::class);

        // success method
        $this->assertTrue($reflection->hasMethod('success'));
        $successMethod = $reflection->getMethod('success');
        $this->assertCount(2, $successMethod->getParameters());
        $this->assertEquals('array', (string) $successMethod->getReturnType());

        // error method
        $this->assertTrue($reflection->hasMethod('error'));
        $errorMethod = $reflection->getMethod('error');
        $this->assertCount(4, $errorMethod->getParameters());
        $this->assertEquals('array', (string) $errorMethod->getReturnType());
    }

    public function test_version_resolver_method_signature(): void
    {
        $reflection = new ReflectionClass(VersionResolver::class);

        $this->assertTrue($reflection->hasMethod('resolve'));
        $resolveMethod = $reflection->getMethod('resolve');
        $this->assertCount(1, $resolveMethod->getParameters());
        $this->assertEquals('string', (string) $resolveMethod->getReturnType());
    }

    public function test_error_code_provider_method_signature(): void
    {
        $reflection = new ReflectionClass(ErrorCodeProvider::class);

        $this->assertTrue($reflection->hasMethod('all'));
        $allMethod = $reflection->getMethod('all');
        $this->assertCount(0, $allMethod->getParameters());
        $this->assertEquals('array', (string) $allMethod->getReturnType());
    }
}
