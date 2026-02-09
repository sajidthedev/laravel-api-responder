<?php

declare(strict_types=1);

namespace ApiResponder\Tests;

use ApiResponder\Deprecation\DeprecationRegistry;
use ApiResponder\ErrorCodes\ErrorCodeRegistry;
use DateTimeImmutable;

class ProviderWiringTest extends TestCase
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

        $app['config']->set('api_responder.deprecations', [
            'api/v1/*' => [
                'sunset' => '2030-01-01T00:00:00Z',
                'link' => 'https://example.com',
                'message' => 'Deprecated'
            ],
        ]);
    }

    public function test_error_code_registry_is_wired_with_config(): void
    {
        /** @var ErrorCodeRegistry $registry */
        $registry = $this->app->make(ErrorCodeRegistry::class);

        $this->assertTrue($registry->has('USER_NOT_FOUND'));

        $errorCode = $registry->get('USER_NOT_FOUND');
        $this->assertSame('User not found', $errorCode->defaultMessage);
        $this->assertSame(404, $errorCode->defaultStatus);
    }

    public function test_deprecation_registry_is_wired_with_config(): void
    {
        /** @var DeprecationRegistry $registry */
        $registry = $this->app->make(DeprecationRegistry::class);

        $deprecation = $registry->find(null, 'api/v1/test');

        $this->assertNotNull($deprecation);
        $this->assertSame('Deprecated', $deprecation->message);
        $this->assertSame('https://example.com', $deprecation->link);
        $this->assertInstanceOf(DateTimeImmutable::class, $deprecation->sunsetAt);
        $this->assertSame('2030-01-01 00:00:00', $deprecation->sunsetAt->format('Y-m-d H:i:s'));
    }
}
