<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Domain\Carts\Actions\CreateUserFromCart;
use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Listeners\CreateCartAddresses;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister;
use Dystcz\LunarApi\Domain\Users\Actions\RegisterUser;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Lunar\Facades\ModelManifest;

class LunarApiServiceProvider extends ServiceProvider
{
    protected $policies = [
        \Lunar\Models\Address::class => \Dystcz\LunarApi\Domain\Addresses\Policies\AddressPolicy::class,
        \Lunar\Models\Brand::class => \Dystcz\LunarApi\Domain\Brands\Policies\BrandPolicy::class,
        \Lunar\Models\Cart::class => \Dystcz\LunarApi\Domain\Carts\Policies\CartPolicy::class,
        \Lunar\Models\CartAddress::class => \Dystcz\LunarApi\Domain\Carts\Policies\CartAddressPolicy::class,
        \Lunar\Models\CartLine::class => \Dystcz\LunarApi\Domain\Carts\Policies\CartLinePolicy::class,
        \Lunar\Models\CollectionGroup::class => \Dystcz\LunarApi\Domain\CollectionGroups\Policies\CollectionGroupPolicy::class,
        \Lunar\Models\Collection::class => \Dystcz\LunarApi\Domain\Collections\Policies\CollectionPolicy::class,
        \Lunar\Models\Customer::class => \Dystcz\LunarApi\Domain\Customers\Policies\CustomerPolicy::class,
        \Lunar\Models\Order::class => \Dystcz\LunarApi\Domain\Orders\Policies\OrderPolicy::class,
        \Lunar\Models\OrderAddress::class => \Dystcz\LunarApi\Domain\Orders\Policies\OrderAddressPolicy::class,
        \Lunar\Models\OrderLine::class => \Dystcz\LunarApi\Domain\Orders\Policies\OrderLinePolicy::class,
        \Lunar\Models\Price::class => \Dystcz\LunarApi\Domain\Prices\Policies\PricePolicy::class,
        \Lunar\Models\ProductVariant::class => \Dystcz\LunarApi\Domain\ProductVariants\Policies\ProductVariantPolicy::class,
        \Lunar\Models\ProductAssociation::class => \Dystcz\LunarApi\Domain\ProductAssociations\Policies\ProductAssociationPolicy::class,
        \Lunar\Models\Product::class => \Dystcz\LunarApi\Domain\Products\Policies\ProductPolicy::class,
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Register routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->registerModels();

        $this->addPaymentOptions();

        Event::listen(CartCreated::class, CreateCartAddresses::class);

        LunarApi::createUserFromCartUsing(Config::get('auth.actions.create_user_from_cart', CreateUserFromCart::class));
        LunarApi::registerUserUsing(Config::get('auth.actions.register_user', RegisterUser::class));

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/lunar-api.php' => config_path('lunar-api.php'),
            ], 'config');

            // Register commands.
            $this->commands([
                //
            ]);
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

        $this->booting(function () {
            $this->registerPolicies();
        });

        // Register the main class to use with the facade
        $this->app->singleton('lunar-api', fn () => new LunarApi());

        $this->app->singleton(PaymentAdaptersRegister::class, fn () => new PaymentAdaptersRegister());
    }

    /**
     * Swap models.
     */
    protected function registerModels(): void
    {
        $models = LaravelCollection::make([
            \Lunar\Models\Address::class => \Dystcz\LunarApi\Domain\Addresses\Models\Address::class,
            \Lunar\Models\Attribute::class => \Dystcz\LunarApi\Domain\Attributes\Models\Attribute::class,
            \Lunar\Models\AttributeGroup::class => \Dystcz\LunarApi\Domain\AttributeGroups\Models\AttributeGroup::class,
            \Lunar\Models\Brand::class => \Dystcz\LunarApi\Domain\Brands\Models\Brand::class,
            \Lunar\Models\Cart::class => \Dystcz\LunarApi\Domain\Carts\Models\Cart::class,
            \Lunar\Models\CartAddress::class => \Dystcz\LunarApi\Domain\Carts\Models\CartAddress::class,
            \Lunar\Models\CartLine::class => \Dystcz\LunarApi\Domain\Carts\Models\CartLine::class,
            \Lunar\Models\Collection::class => \Dystcz\LunarApi\Domain\Collections\Models\Collection::class,
            \Lunar\Models\CollectionGroup::class => \Dystcz\LunarApi\Domain\CollectionGroups\Models\CollectionGroup::class,
            \Lunar\Models\Customer::class => \Dystcz\LunarApi\Domain\Customers\Models\Customer::class,
            \Lunar\Models\Order::class => \Dystcz\LunarApi\Domain\Orders\Models\Order::class,
            \Lunar\Models\OrderAddress::class => \Dystcz\LunarApi\Domain\Orders\Models\OrderAddress::class,
            \Lunar\Models\OrderLine::class => \Dystcz\LunarApi\Domain\Orders\Models\OrderLine::class,
            \Lunar\Models\Price::class => \Dystcz\LunarApi\Domain\Prices\Models\Price::class,
            \Lunar\Models\Product::class => \Dystcz\LunarApi\Domain\Products\Models\Product::class,
            \Lunar\Models\ProductAssociation::class => \Dystcz\LunarApi\Domain\ProductAssociations\Models\ProductAssociation::class,
            \Lunar\Models\ProductOption::class => \Dystcz\LunarApi\Domain\Products\Models\ProductOption::class,
            \Lunar\Models\ProductOptionValue::class => \Dystcz\LunarApi\Domain\Products\Models\ProductOptionValue::class,
            \Lunar\Models\ProductType::class => \Dystcz\LunarApi\Domain\Products\Models\ProductType::class,
            \Lunar\Models\ProductVariant::class => \Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant::class,
            \Lunar\Models\Tag::class => \Dystcz\LunarApi\Domain\Tags\Models\Tag::class,
        ]);

        ModelManifest::register($models);
    }

    /**
     * Register the application's policies.
     */
    public function registerPolicies(): void
    {
        foreach ($this->policies() as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Register payment options.
     */
    public function addPaymentOptions(): void
    {
        $paymentTypes = Config::get('lunar.payments.types', []);

        Config::set(
            'lunar.payments.types',
            array_merge($paymentTypes, [
                'card' => [
                    'driver' => 'stripe',
                    'released' => 'payment-received',
                ],
                'paypal' => [
                    'driver' => 'paypal',
                    'released' => 'payment-received',
                ],
            ])
        );
    }

    /**
     * Get the policies defined on the provider.
     *
     * @return array<class-string, class-string>
     */
    public function policies(): array
    {
        return $this->policies;
    }
}
