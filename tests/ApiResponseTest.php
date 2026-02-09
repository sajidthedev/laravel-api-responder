<?php

declare(strict_types=1);

namespace ApiResponder\Tests;

use ApiResponder\Http\Responses\ApiResponse;
use ApiResponder\Providers\ApiResponderServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Orchestra\Testbench\TestCase;

class ApiResponseTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ApiResponderServiceProvider::class,
        ];
    }

    public function test_success_response_structure(): void
    {
        $data = ['id' => 1, 'name' => 'John Doe'];
        $meta = ['version' => '1.0'];

        $response = ApiResponse::success($data, $meta, 201);
        $content = $response->getData(true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($content['success']);
        $this->assertEquals($data, $content['data']);
        $this->assertEquals($meta, $content['meta']);
    }

    public function test_error_response_structure(): void
    {
        $code = 'VALIDATION_ERROR';
        $message = 'Invalid input';
        $details = ['field' => 'Required'];

        $response = ApiResponse::error($code, $message, $details, 422);
        $content = $response->getData(true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertFalse($content['success']);
        $this->assertEquals($code, $content['error']['code']);
        $this->assertEquals($message, $content['error']['message']);
        $this->assertEquals($details, $content['error']['details']);
    }

    public function test_paginated_response(): void
    {
        $items = [
            ['id' => 1, 'name' => 'User 1'],
            ['id' => 2, 'name' => 'User 2'],
        ];

        $paginator = new LengthAwarePaginator(
            $items,
            10, // total
            2,  // per page
            1   // current page
        );

        $response = ApiResponse::paginated($paginator);
        $content = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($content['success']);
        $this->assertEquals($items, $content['data']);
        $this->assertEquals(1, $content['meta']['page']);
        $this->assertEquals(2, $content['meta']['per_page']);
        $this->assertEquals(10, $content['meta']['total']);
    }

    public function test_paginated_response_with_transformer(): void
    {
        $items = [
            ['id' => 1, 'name' => 'user 1'],
            ['id' => 2, 'name' => 'user 2'],
        ];

        $paginator = new LengthAwarePaginator($items, 2, 2, 1);

        $transformer = function ($item) {
            $item['name'] = strtoupper($item['name']);
            return $item;
        };

        $response = ApiResponse::paginated($paginator, $transformer);
        $content = $response->getData(true);

        $this->assertEquals('USER 1', $content['data'][0]['name']);
        $this->assertEquals('USER 2', $content['data'][1]['name']);
    }
}
