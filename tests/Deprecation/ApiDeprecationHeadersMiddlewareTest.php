<?php

declare(strict_types=1);

namespace ApiResponder\Tests\Deprecation;

use ApiResponder\Deprecation\Middleware\ApiDeprecationHeaders;
use ApiResponder\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class ApiDeprecationHeadersMiddlewareTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('api_responder.deprecations', [
            'ping.v1' => [
                'sunset' => '2030-01-01T00:00:00Z',
                'link' => 'https://example.com/migrate',
                'message' => 'Use /api/v2/ping'
            ],
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/api/v1/ping', fn() => response()->json(['ok' => true]))
            ->name('ping.v1')
            ->middleware(ApiDeprecationHeaders::class);
    }

    public function test_it_adds_deprecation_headers_to_response(): void
    {
        $response = $this->get('/api/v1/ping');

        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);

        $response->assertHeader('Deprecation', 'true');
        $response->assertHeader('Sunset');
        $sunset = $response->headers->get('Sunset');
        $this->assertTrue(
            str_contains($sunset, 'GMT') || str_contains($sunset, '+0000'),
            "Sunset header should contain a timezone: {$sunset}"
        );

        $response->assertHeader('Link', '<https://example.com/migrate>; rel="deprecation"');
        $response->assertHeader('X-API-Deprecation-Message', 'Use /api/v2/ping');
    }

    public function test_it_does_not_add_headers_to_non_deprecated_routes(): void
    {
        Route::get('/api/v2/ping', fn() => response()->json(['ok' => true]))
            ->middleware(ApiDeprecationHeaders::class);

        $response = $this->get('/api/v2/ping');

        $response->assertHeaderMissing('Deprecation');
        $response->assertHeaderMissing('Sunset');
        $response->assertHeaderMissing('Link');
        $response->assertHeaderMissing('X-API-Deprecation-Message');
    }
}
