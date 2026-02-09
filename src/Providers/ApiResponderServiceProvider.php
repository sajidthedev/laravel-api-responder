<?php

namespace ApiResponder\Providers;

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
    }
}
