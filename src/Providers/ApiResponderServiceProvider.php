<?php

namespace ApiResponder\Providers;

use ApiResponder\Deprecation\Deprecation;
use ApiResponder\Deprecation\DeprecationRegistry;
use ApiResponder\ErrorCodes\ErrorCodeRegistry;
use ApiResponder\ErrorCodes\Providers\ConfigErrorCodeProvider;
use DateTimeImmutable;
use Illuminate\Support\ServiceProvider;

class ApiResponderServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/api_responder.php',
            'api_responder'
        );

        $this->app->singleton(\ApiResponder\Versioning\VersionResolver::class, function ($app) {
            return new \ApiResponder\Versioning\VersionResolver($app['request']);
        });

        $this->app->singleton(ErrorCodeRegistry::class, function ($app) {
            $registry = new ErrorCodeRegistry();
            $registry->registerProvider(new ConfigErrorCodeProvider(
                config('api_responder.error_codes', [])
            ));

            return $registry;
        });

        $this->app->singleton(DeprecationRegistry::class, function ($app) {
            $registry = new DeprecationRegistry();
            $deprecations = config('api_responder.deprecations', []);

            foreach ($deprecations as $key => $config) {
                $registry->register(new Deprecation(
                    key: (string) $key,
                    sunsetAt: isset($config['sunset']) ? new DateTimeImmutable($config['sunset']) : null,
                    link: $config['link'] ?? null,
                    message: $config['message'] ?? null
                ));
            }

            return $registry;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/api_responder.php' => config_path('api_responder.php'),
            ], 'config');
        }

        $this->app['router']->aliasMiddleware(
            'api.deprecations',
            \ApiResponder\Deprecation\Middleware\ApiDeprecationHeaders::class
        );
    }
}
