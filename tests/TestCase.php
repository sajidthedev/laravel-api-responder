<?php

declare(strict_types=1);

namespace ApiResponder\Tests;

use ApiResponder\Providers\ApiResponderServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array<int, string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            ApiResponderServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        // Setup default configuration
        $app['config']->set('api_responder.versions', ['v1', 'v2']);
        $app['config']->set('api_responder.default', 'v1');
        $app['config']->set('api_responder.header', 'X-API-Version');
        $app['config']->set('api_responder.error_codes', []);
        $app['config']->set('api_responder.deprecations', []);
    }
}
