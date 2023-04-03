<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Console\GenerateOpenApiSpec;
use Dystcz\LunarApi\Domain\Addresses\Models\Address;
use Dystcz\LunarApi\Domain\Addresses\Policies\AddressPolicy;
use Dystcz\LunarApi\Domain\Carts\Actions\CreateUserFromCart;
use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Listeners\CreateCartAddresses;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Carts\Models\CartAddress;
use Dystcz\LunarApi\Domain\Carts\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Policies\CartAddressPolicy;
use Dystcz\LunarApi\Domain\Carts\Policies\CartLinePolicy;
use Dystcz\LunarApi\Domain\Carts\Policies\CartPolicy;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Customers\Policies\CustomerPolicy;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Orders\Policies\OrderPolicy;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\Products\Policies\ProductPolicy;
use Dystcz\LunarApi\Domain\Users\Actions\RegisterUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Lunar\Facades\ModelManifest;
use Lunar\Models\Brand;
use Lunar\Models\CollectionGroup;
use Lunar\Models\OrderLine;
use Lunar\Models\Price;
use Lunar\Models\ProductOption;
use Lunar\Models\ProductOptionValue;
use Lunar\Models\ProductType;
use Lunar\Models\ProductVariant;

class LunarApiServiceProvider extends ServiceProvider
{
    protected $policies = [
        Product::class => ProductPolicy::class,
        Cart::class => CartPolicy::class,
        CartLine::class => CartLinePolicy::class,
        CartAddress::class => CartAddressPolicy::class,
        Order::class => OrderPolicy::class,
        Address::class => AddressPolicy::class,
        Customer::class => CustomerPolicy::class,
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Register routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        Config::set('openapi', require __DIR__.'/../config/openapi.php');

        $this->registerModels();

        Event::listen(CartCreated::class, CreateCartAddresses::class);

        LunarApi::createUserFromCartUsing(CreateUserFromCart::class);
        LunarApi::registerUserUsing(RegisterUser::class);

        // $this->registerPolicies();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/lunar-api.php' => config_path('lunar-api.php'),
            ], 'config');

            // $this->publishes([
            //     __DIR__ . '/../config/jsonapi.php' => config_path('jsonapi.php'),
            // ], 'config');

            // Register commands.
            $this->commands([
                GenerateOpenApiSpec::class,
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
        $this->mergeConfigFrom(__DIR__.'/../config/lunar-api.php', 'lunar-api');
        $this->mergeConfigFrom(__DIR__.'/../config/jsonapi.php', 'jsonapi');

        // Register the main class to use with the facade
        $this->app->singleton('lunar-api', function () {
            return new LunarApi();
        });

        $this->app->singleton(SchemaManifest::class, function () {
            return new SchemaManifest();
        });
        $this->app->singleton(ResourceManifest::class, function () {
            return new ResourceManifest();
        });
    }

    /**
     * Swap models.
     */
    protected function registerModels(): void
    {
        $models = new Collection([
            \Lunar\Models\Address::class => Address::class,
            Brand::class => \Dystcz\LunarApi\Domain\Brands\Models\Brand::class,
            \Lunar\Models\Cart::class => Cart::class,
            \Lunar\Models\CartLine::class => CartLine::class,
            \Lunar\Models\CartAddress::class => CartAddress::class,
            \Lunar\Models\Collection::class => \Dystcz\LunarApi\Domain\Collections\Models\Collection::class,
            \Lunar\Models\Customer::class => Customer::class,
            \Lunar\Models\Order::class => Order::class,
            OrderLine::class => \Dystcz\LunarApi\Domain\Orders\Models\OrderLine::class,
            Price::class => \Dystcz\LunarApi\Domain\Prices\Models\Price::class,
            \Lunar\Models\Product::class => Product::class,
            ProductType::class => \Dystcz\LunarApi\Domain\Products\Models\ProductType::class,
            ProductOption::class => \Dystcz\LunarApi\Domain\Products\Models\ProductOption::class,
            ProductOptionValue::class => \Dystcz\LunarApi\Domain\Products\Models\ProductOptionValue::class,
            ProductVariant::class => \Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant::class,
            CollectionGroup::class => \Dystcz\LunarApi\Domain\CollectionGroups\Models\CollectionGroup::class,
        ]);

        ModelManifest::register($models);
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
