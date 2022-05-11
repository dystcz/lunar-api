<?php

namespace Dystcz\GetcandyApi;

use Illuminate\Support\ServiceProvider;

class GetcandyApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Register routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/getcandy-api.php' => config_path('getcandy-api.php'),
            ], 'config');

            // Register commands.
            $this->commands([
                \Dystcz\GetcandyApi\Console\GenerateOpenApiSpec::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/getcandy-api.php', 'getcandy-api');

        // Register the main class to use with the facade
        $this->app->singleton('getcandy-api', function () {
            return new GetcandyApi;
        });
    }
}
