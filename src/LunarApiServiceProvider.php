<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Domain\Carts\Actions\CreateUserFromCart;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister;
use Dystcz\LunarApi\Domain\Users\Actions\RegisterUser;
use Dystcz\LunarApi\Support\Config\Collections\DomainConfigCollection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Lunar\Facades\ModelManifest;

class LunarApiServiceProvider extends ServiceProvider
{
    protected array $configFiles = [
        'domains',
        'general',
        'hashids',
    ];

    protected $root = __DIR__.'/..';

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom("{$this->root}/routes/api.php");

        $this->loadTranslationsFrom("{$this->root}/lang", 'lunar-api');

        $this->registerModels();

        $this->registerEvents();

        LunarApi::createUserFromCartUsing(Config::get('domains.auth.actions.create_user_from_cart', CreateUserFromCart::class));
        LunarApi::registerUserUsing(Config::get('domains.auth.actions.register_user', RegisterUser::class));

        if ($this->app->runningInConsole()) {
            $this->publishConfig();

            $this->publishTranslations();

            $this->registerCommands();
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Register config files.
        $this->registerConfig();

        $this->booting(function () {
            $this->registerPolicies();
        });

        // Register the main class to use with the facade.
        $this->app->singleton(
            'lunar-api',
            fn () => new LunarApi,
        );

        // Register payment adapters register.
        $this->app->singleton(
            PaymentAdaptersRegister::class,
            fn () => new PaymentAdaptersRegister,
        );
    }

    /**
     * Publish config files.
     */
    protected function publishConfig(): void
    {
        foreach ($this->configFiles as $configFile) {
            $this->publishes([
                "{$this->root}/config/{$configFile}.php" => config_path("lunar-api.{$configFile}.php"),
            ], 'lunar-api');
        }

        $this->publishes([
            "{$this->root}/config/jsonapi.php" => config_path('jsonapi.php'),
        ], 'jsonapi');
    }

    /**
     * Publish translations.
     */
    protected function publishTranslations(): void
    {
        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/lunar-api'),
        ], 'translations');
    }

    /**
     * Register config files.
     */
    protected function registerConfig(): void
    {
        foreach ($this->configFiles as $configFile) {
            $this->mergeConfigFrom(
                "{$this->root}/config/{$configFile}.php",
                "lunar-api.{$configFile}",
            );
        }

        $this->mergeConfigFrom(
            "{$this->root}/config/jsonapi.php",
            'jsonapi',
        );
    }

    /**
     * Register commands.
     */
    protected function registerCommands(): void
    {
        $this->commands([
            //
        ]);
    }

    /**
     * Register events.
     */
    protected function registerEvents(): void
    {
        $events = [
            \Dystcz\LunarApi\Domain\Carts\Events\CartCreated::class => [
                \Dystcz\LunarApi\Domain\Carts\Listeners\CreateCartAddresses::class,
            ],
            \Dystcz\LunarApi\Domain\Orders\Events\OrderPaymentFailed::class => [
                \Dystcz\LunarApi\Domain\Payments\Listeners\HandleFailedPayment::class,
            ],
            \Dystcz\LunarApi\Domain\Orders\Events\OrderPaymentCanceled::class => [
                \Dystcz\LunarApi\Domain\Payments\Listeners\HandleFailedPayment::class,
            ],
        ];

        foreach ($events as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    /**
     * Swap models.
     */
    protected function registerModels(): void
    {
        ModelManifest::register(
            DomainConfigCollection::make()->getModelsForModelManifest(),
        );
    }

    /**
     * Register the application's policies.
     */
    public function registerPolicies(): void
    {
        $policies = [
            \Lunar\Models\Address::class => \Dystcz\LunarApi\Domain\Addresses\Policies\AddressPolicy::class,
            \Lunar\Models\Attribute::class => \Dystcz\LunarApi\Domain\Attributes\Policies\AttributePolicy::class,
            \Lunar\Models\AttributeGroup::class => \Dystcz\LunarApi\Domain\AttributeGroups\Policies\AttributeGroupPolicy::class,
            \Lunar\Models\Brand::class => \Dystcz\LunarApi\Domain\Brands\Policies\BrandPolicy::class,
            \Lunar\Models\Cart::class => \Dystcz\LunarApi\Domain\Carts\Policies\CartPolicy::class,
            \Lunar\Models\CartAddress::class => \Dystcz\LunarApi\Domain\CartAddresses\Policies\CartAddressPolicy::class,
            \Lunar\Models\CartLine::class => \Dystcz\LunarApi\Domain\CartLines\Policies\CartLinePolicy::class,
            \Lunar\Models\Collection::class => \Dystcz\LunarApi\Domain\Collections\Policies\CollectionPolicy::class,
            \Lunar\Models\CollectionGroup::class => \Dystcz\LunarApi\Domain\CollectionGroups\Policies\CollectionGroupPolicy::class,
            \Lunar\Models\Country::class => \Dystcz\LunarApi\Domain\Countries\Policies\CountryPolicy::class,
            \Lunar\Models\Currency::class => \Dystcz\LunarApi\Domain\Currencies\Policies\CurrencyPolicy::class,
            \Lunar\Models\Customer::class => \Dystcz\LunarApi\Domain\Customers\Policies\CustomerPolicy::class,
            \Lunar\Models\Order::class => \Dystcz\LunarApi\Domain\Orders\Policies\OrderPolicy::class,
            \Lunar\Models\OrderAddress::class => \Dystcz\LunarApi\Domain\OrderAddresses\Policies\OrderAddressPolicy::class,
            \Lunar\Models\OrderLine::class => \Dystcz\LunarApi\Domain\OrderLines\Policies\OrderLinePolicy::class,
            \Lunar\Models\Price::class => \Dystcz\LunarApi\Domain\Prices\Policies\PricePolicy::class,
            \Lunar\Models\Product::class => \Dystcz\LunarApi\Domain\Products\Policies\ProductPolicy::class,
            \Lunar\Models\ProductAssociation::class => \Dystcz\LunarApi\Domain\ProductAssociations\Policies\ProductAssociationPolicy::class,
            \Lunar\Models\ProductOption::class => \Dystcz\LunarApi\Domain\ProductOptions\Policies\ProductOptionPolicy::class,
            \Lunar\Models\ProductOptionValue::class => \Dystcz\LunarApi\Domain\ProductOptionValues\Policies\ProductOptionValuePolicy::class,
            \Lunar\Models\ProductType::class => \Dystcz\LunarApi\Domain\ProductTypes\Policies\ProductTypePolicy::class,
            \Lunar\Models\ProductVariant::class => \Dystcz\LunarApi\Domain\ProductVariants\Policies\ProductVariantPolicy::class,
            \Lunar\Models\Tag::class => \Dystcz\LunarApi\Domain\Tags\Policies\TagPolicy::class,
            \Lunar\Models\Transaction::class => \Dystcz\LunarApi\Domain\Transactions\Policies\TransactionPolicy::class,
            \Lunar\Models\Url::class => \Dystcz\LunarApi\Domain\Urls\Policies\UrlPolicy::class,
            \Spatie\MediaLibrary\MediaCollections\Models\Media::class => \Dystcz\LunarApi\Domain\Media\Policies\MediaPolicy::class,
        ];

        foreach ($policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
