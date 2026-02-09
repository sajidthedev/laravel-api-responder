<?php

declare(strict_types=1);

namespace ApiResponder\Tests\Http;

use ApiResponder\ErrorCodes\ErrorCodeRegistry;
use ApiResponder\Http\Responses\ApiResponse;
use ApiResponder\Tests\TestCase;
use Illuminate\Http\JsonResponse;

class ApiResponseErrorFromCodeTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('api_responder.error_codes', [
            'USER_NOT_FOUND' => [
                'message' => 'User not found',
                'status' => 404
            ],
        ]);
    }

    public function test_it_returns_error_from_registered_code(): void
    {
        $response = ApiResponse::errorFromCode('USER_NOT_FOUND');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(404, $response->getStatusCode());

        $data = $response->getData(true);
        $this->assertFalse($data['success']);
        $this->assertNull($data['data']);
        $this->assertIsArray($data['meta']);
        $this->assertSame('USER_NOT_FOUND', $data['error']['code']);
        $this->assertSame('User not found', $data['error']['message']);
        $this->assertIsArray($data['error']['details']);
    }

    public function test_it_allows_message_override(): void
    {
        $response = ApiResponse::errorFromCode('USER_NOT_FOUND', message: 'Custom message');

        $data = $response->getData(true);
        $this->assertSame('Custom message', $data['error']['message']);
    }

    public function test_it_falls_back_for_unknown_code(): void
    {
        $response = ApiResponse::errorFromCode('UNKNOWN_CODE');

        $this->assertSame(400, $response->getStatusCode());

        $data = $response->getData(true);
        $this->assertSame('UNKNOWN_CODE', $data['error']['code']);
        $this->assertSame('UNKNOWN_CODE', $data['error']['message']);
    }
}
