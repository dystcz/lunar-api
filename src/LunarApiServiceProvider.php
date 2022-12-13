<?php

namespace Dystcz\LunarApi;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Lunar\Facades\ModelManifest;

class LunarApiServiceProvider extends ServiceProvider
{
    // protected $policies = [
    //     Product::class => ProductPolicy::class,
    // ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Register routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        Config::set('openapi', require __DIR__ . '/../config/openapi.php');

        ModelManifest::register(collect([
            \Lunar\Models\Product::class => Domain\Products\Models\Product::class,
            \Lunar\Models\ProductOption::class => Domain\Products\Models\ProductOption::class,
            \Lunar\Models\ProductOptionValue::class => Domain\Products\Models\ProductOptionValue::class,
            \Lunar\Models\ProductVariant::class => Domain\ProductVariants\Models\ProductVariant::class,
            \Lunar\Models\Price::class => Domain\Prices\Models\Price::class,
            \Lunar\Models\Brand::class => Domain\Brands\Models\Brand::class,
            \Lunar\Models\Collection::class => Domain\Collections\Models\Collection::class,
            \Lunar\Models\Customer::class => Domain\Customers\Models\Customer::class,
            \Lunar\Models\Cart::class => Domain\Carts\Models\Cart::class,
            \Lunar\Models\CartLine::class => Domain\Carts\Models\CartLine::class,
            \Lunar\Models\Order::class => Domain\Orders\Models\Order::class,
            \Lunar\Models\OrderLine::class => Domain\Orders\Models\OrderLine::class,
        ]));

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/lunar-api.php' => config_path('lunar-api.php'),
            ], 'config');

            // $this->publishes([
            //     __DIR__ . '/../config/jsonapi.php' => config_path('jsonapi.php'),
            // ], 'config');

            // Register commands.
            $this->commands([
                \Dystcz\LunarApi\Console\GenerateOpenApiSpec::class,
            ]);

            // $this->registerPolicies();
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/lunar-api.php', 'lunar-api');
        $this->mergeConfigFrom(__DIR__ . '/../config/jsonapi.php', 'jsonapi');

        // Register the main class to use with the facade
        $this->app->singleton('lunar-api', function () {
            return new LunarApi();
        });
    }

    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        foreach ($this->policies() as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Get the policies defined on the provider.
     *
     * @return array<class-string, class-string>
     */
    public function policies()
    {
        return $this->policies;
    }
}
