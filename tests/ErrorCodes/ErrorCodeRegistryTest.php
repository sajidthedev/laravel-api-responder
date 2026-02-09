<?php

declare(strict_types=1);

namespace ApiResponder\Tests\ErrorCodes;

use ApiResponder\Contracts\ErrorCodeProvider;
use ApiResponder\ErrorCodes\ErrorCode;
use ApiResponder\ErrorCodes\ErrorCodeRegistry;
use ApiResponder\Tests\TestCase;

class ErrorCodeRegistryTest extends TestCase
{
    private ErrorCodeRegistry $registry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registry = new ErrorCodeRegistry();
    }

    public function test_it_can_register_and_retrieve_codes(): void
    {
        $errorCode = new ErrorCode('TEST_CODE', 'Test Message', 400);

        $this->registry->register($errorCode);

        $this->assertTrue($this->registry->has('TEST_CODE'));
        $this->assertSame($errorCode, $this->registry->get('TEST_CODE'));
        $this->assertFalse($this->registry->has('NON_EXISTENT'));
        $this->assertNull($this->registry->get('NON_EXISTENT'));
    }

    public function test_it_can_register_many_codes_and_filters_instances(): void
    {
        $code1 = new ErrorCode('CODE_1');
        $code2 = new ErrorCode('CODE_2');

        $this->registry->registerMany([
            $code1,
            'invalid_item',
            $code2,
            new \stdClass()
        ]);

        $this->assertTrue($this->registry->has('CODE_1'));
        $this->assertTrue($this->registry->has('CODE_2'));
        $this->assertSame($code1, $this->registry->get('CODE_1'));
        $this->assertSame($code2, $this->registry->get('CODE_2'));
    }

    public function test_it_can_register_codes_from_a_provider(): void
    {
        $provider = new class implements ErrorCodeProvider {
            public function all(): array
            {
                return [
                    'USER_NOT_FOUND' => [
                        'message' => 'User not found',
                        'status' => 404
                    ],
                    'BAD_REQUEST' => [
                        'status' => 400
                    ]
                ];
            }
        };

        $this->registry->registerProvider($provider);

        $this->assertTrue($this->registry->has('USER_NOT_FOUND'));
        $userError = $this->registry->get('USER_NOT_FOUND');
        $this->assertSame('User not found', $userError->defaultMessage);
        $this->assertSame(404, $userError->defaultStatus);

        $this->assertTrue($this->registry->has('BAD_REQUEST'));
        $badRequestError = $this->registry->get('BAD_REQUEST');
        $this->assertNull($badRequestError->defaultMessage);
        $this->assertSame(400, $badRequestError->defaultStatus);
    }
}
