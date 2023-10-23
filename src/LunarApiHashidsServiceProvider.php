<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Hashids\Facades\HashidsConnections;
use Illuminate\Support\ServiceProvider;

class LunarApiHashidsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if (LunarApi::usesHashids()) {
            HashidsConnections::registerConnections();
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration.
        $this->mergeConfigFrom(__DIR__.'/../config/hashids.php', 'hashids');

        // Register payment adapters register.
        $this->app->singleton(
            \Dystcz\LunarApi\Hashids\Contracts\HashidsConnectionsManager::class,
            fn () => new \Dystcz\LunarApi\Hashids\Managers\HashidsConnectionsManager,
        );
    }
}
