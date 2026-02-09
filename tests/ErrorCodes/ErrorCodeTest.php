<?php

declare(strict_types=1);

namespace ApiResponder\Tests\ErrorCodes;

use ApiResponder\ErrorCodes\ErrorCode;
use ApiResponder\Tests\TestCase;

class ErrorCodeTest extends TestCase
{
    public function test_it_stores_values_correctly(): void
    {
        $errorCode = new ErrorCode(
            code: 'USER_NOT_FOUND',
            defaultMessage: 'The requested user was not found.',
            defaultStatus: 404
        );

        $this->assertSame('USER_NOT_FOUND', $errorCode->code);
        $this->assertSame('The requested user was not found.', $errorCode->defaultMessage);
        $this->assertSame(404, $errorCode->defaultStatus);
    }

    public function test_it_has_expected_defaults(): void
    {
        $errorCode = new ErrorCode(code: 'SERVER_ERROR');

        $this->assertSame('SERVER_ERROR', $errorCode->code);
        $this->assertNull($errorCode->defaultMessage);
        $this->assertSame(400, $errorCode->defaultStatus);
    }
}
