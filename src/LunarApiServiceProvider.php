<?php

namespace Dystcz\LunarApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use TiMacDonald\JsonApi\JsonApiResource;

class LunarApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Register routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        Config::set('openapi', require __DIR__ . '/../config/openapi.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/lunar-api.php' => config_path('lunar-api.php'),
            ], 'config');

            // Register commands.
            $this->commands([
                \Dystcz\LunarApi\Console\GenerateOpenApiSpec::class,
            ]);
        }

        // Change how json api resource type is resolved
        JsonApiResource::resolveTypeUsing(function (mixed $resource, Request $request): string {
            return Str::camel(Str::plural(class_basename($resource)));
        });
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
