<?php

namespace Dystcz\LunarApi;

use Illuminate\Support\ServiceProvider;

class LunarApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Register routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/lunar-api.php' => config_path('lunar-api.php'),
            ], 'config');

            // Register commands.
            $this->commands([
                \Dystcz\LunarApi\Console\GenerateOpenApiSpec::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/lunar-api.php', 'lunar-api');

        // Register the main class to use with the facade
        $this->app->singleton('lunar-api', function () {
            return new LunarApi();
        });
    }
}
