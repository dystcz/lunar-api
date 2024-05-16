<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Facades\LunarApi;
use Dystcz\LunarApi\Hashids\Facades\HashidsConnections;
use Illuminate\Support\ServiceProvider;

class LunarApiTypeScriptTransformerServiceProvider extends ServiceProvider
{
    protected $root = __DIR__.'/..';

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Register typescript transformer config.
        $this->app->bind(
            'lunar-api.typescript-transformer-config',
            fn () => \Spatie\TypeScriptTransformer\TypeScriptTransformerConfig::create()
                ->autoDiscoverTypes(...[LunarApi::getPackageRoot().'/src'])
                ->collectors([
                    \Spatie\TypeScriptTransformer\Collectors\DefaultCollector::class,
                    \Spatie\TypeScriptTransformer\Collectors\EnumCollector::class,
                    \Dystcz\LunarApi\Support\Typescript\Collectors\SchemaCollector::class,
                ])
                ->transformers([
                    \Spatie\TypeScriptTransformer\Transformers\EnumTransformer::class,
                    \Dystcz\LunarApi\Support\Typescript\Transformers\SchemaTransformer::class,
                ])
                ->defaultTypeReplacements([
                    \DateTime::class => 'string',
                    \DateTimeImmutable::class => 'string',
                    \Carbon\CarbonInterface::class => 'string',
                    \Carbon\CarbonImmutable::class => 'string',
                    \Carbon\Carbon::class => 'string',
                ])
                ->outputFile(resource_path('types/lunar-api-resources.d.ts'))
                ->writer(\Spatie\TypeScriptTransformer\Writers\ModuleWriter::class)
                ->formatter(null)
                ->transformToNativeEnums(true)
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
            $this->registerCommands();
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

    /**
     * Register commands.
     */
    protected function registerCommands(): void
    {
        $this->commands([
            \Dystcz\LunarApi\Support\Typescript\Commands\GenerateTypeScriptTypes::class,
        ]);
    }
}
