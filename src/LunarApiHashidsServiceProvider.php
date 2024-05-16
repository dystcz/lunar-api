<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Hashids\Facades\HashidsConnections;
use Illuminate\Support\ServiceProvider;

class LunarApiHashidsServiceProvider extends ServiceProvider
{
    protected $root = __DIR__.'/..';

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/hashids.php', 'lunar-api.hashids');

        // Register payment adapters register.
        $this->app->singleton(
            \Dystcz\LunarApi\Hashids\Contracts\HashidsConnectionsManager::class,
            fn () => new \Dystcz\LunarApi\Hashids\Managers\HashidsConnectionsManager,
        );
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if (LunarApi::usesHashids()) {
            HashidsConnections::registerConnections();
        }

        if ($this->app->runningInConsole()) {
            $this->publishConfig();
        }
    }

    /**
     * Publish config files.
     */
    protected function publishConfig(): void
    {
        $this->publishes([
            "{$this->root}/config/hashids.php" => config_path('lunar-api/hashids.php'),
        ], 'lunar-api.hashids');
    }
}
