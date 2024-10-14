<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Domain\Carts\Actions\CheckoutCart;
use Dystcz\LunarApi\Domain\Carts\Actions\CreateUserFromCart;
use Dystcz\LunarApi\Domain\Users\Actions\CreateUser;
use Dystcz\LunarApi\Domain\Users\Actions\RegisterUser;
use Dystcz\LunarApi\Support\Config\Collections\DomainConfigCollection;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Lunar\Base\CartSessionInterface;
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
     * Register the application services.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->setPaymentOptionsConfig();

        $this->loadTranslationsFrom(
            "{$this->root}/lang",
            'lunar-api',
        );

        $this->booting(function () {
            $this->registerPolicies();
        });

        $this->app->singleton('lunar-api', fn () => new LunarApi);
        $this->app->singleton('lunar-api-config', fn () => new LunarApiConfig);

        $this->bindControllers();
        $this->bindModels();

        // Register payment adapters register.
        $this->app->singleton(
            \Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister::class,
            fn () => new \Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister,
        );

        // Register payment modifiers.
        $this->app->singleton(
            \Dystcz\LunarApi\Domain\PaymentOptions\Modifiers\PaymentModifiers::class,
            fn (Application $app) => new \Dystcz\LunarApi\Domain\PaymentOptions\Modifiers\PaymentModifiers,
        );

        // Register payment manifest.
        $this->app->singleton(
            \Dystcz\LunarApi\Domain\PaymentOptions\Contracts\PaymentManifest::class,
            fn (Application $app) => $app->make(\Dystcz\LunarApi\Domain\PaymentOptions\Manifests\PaymentManifest::class),
        );

    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom("{$this->root}/routes/api.php");
        $this->loadMigrationsFrom("{$this->root}/database/migrations");

        $this->registerModels();
        $this->registerDynamicRelations();
        $this->registerObservers();
        $this->registerEvents();
        $this->registerPayments();

        LunarApi::createUserUsing(CreateUser::class);
        LunarApi::createUserFromCartUsing(CreateUserFromCart::class);
        LunarApi::registerUserUsing(RegisterUser::class);
        LunarApi::checkoutCartUsing(CheckoutCart::class);

        if ($this->app->runningInConsole()) {
            $this->publishConfig();
            $this->publishTranslations();
            $this->publishMigrations();
            $this->registerCommands();
        }
    }

    /**
     * Publish config files.
     */
    protected function publishConfig(): void
    {
        foreach ($this->configFiles as $configFile) {
            $this->publishes([
                "{$this->root}/config/{$configFile}.php" => config_path("lunar-api/{$configFile}.php"),
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
            "{$this->root}/lang" => $this->app->langPath('vendor/lunar-api'),
        ], 'lunar-api.translations');
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
     * Set payment options config.
     */
    protected function setPaymentOptionsConfig(): void
    {
        // Push ApplyPayment pipeline after ApplyShipping pipeline
        $cartPipelines = Config::get('lunar.cart.pipelines.cart', []);

        // Push apply payment pipeline after apply shipping pipeline
        $applyShippingIndex = array_search(\Lunar\Pipelines\Cart\ApplyShipping::class, $cartPipelines);

        if (array_key_exists($applyShippingIndex, $cartPipelines)) {
            $cartPipelines = array_merge(
                array_slice($cartPipelines, 0, $applyShippingIndex + 1),
                [\Dystcz\LunarApi\Domain\Carts\Pipelines\ApplyPayment::class],
                array_slice($cartPipelines, $applyShippingIndex + 1),
            );
        }

        // Push calculate payment pipeline after calculate pipeline
        $calculateIndex = array_search(\Lunar\Pipelines\Cart\Calculate::class, $cartPipelines);

        if (array_key_exists($calculateIndex, $cartPipelines)) {
            $cartPipelines = array_merge(
                array_slice($cartPipelines, 0, $calculateIndex + 1),
                [\Dystcz\LunarApi\Domain\Carts\Pipelines\CalculatePayment::class],
                array_slice($cartPipelines, $calculateIndex + 1),
            );
        }

        // // NOTE: Workaround
        // // Swap calculate lines pipeline
        // $calculateLinesIndex = array_search(\Lunar\Pipelines\Cart\CalculateLines::class, $cartPipelines);
        // if (array_key_exists($calculateLinesIndex, $cartPipelines)) {
        //     $cartPipelines[$calculateLinesIndex] = \Dystcz\LunarApi\Domain\Carts\Pipelines\CalculateLines::class;
        // }
        //
        // // NOTE: Workaround
        // // Swap apply shipping pipeline
        // $applyShippingIndex = array_search(\Lunar\Pipelines\Cart\ApplyShipping::class, $cartPipelines);
        // if (array_key_exists($applyShippingIndex, $cartPipelines)) {
        //     $cartPipelines[$applyShippingIndex] = \Dystcz\LunarApi\Domain\Carts\Pipelines\ApplyShipping::class;
        // }
        //
        // // NOTE: Workaround
        // // Swap apply discounts pipeline
        // $applyDiscountsIndex = array_search(\Lunar\Pipelines\Cart\ApplyDiscounts::class, $cartPipelines);
        // if (array_key_exists($applyDiscountsIndex, $cartPipelines)) {
        //     $cartPipelines[$applyDiscountsIndex] = \Dystcz\LunarApi\Domain\Carts\Pipelines\ApplyDiscounts::class;
        // }
        //
        // // NOTE: Workaround
        // // Swap calculate tax pipeline
        // $calculateTaxIndex = array_search(\Lunar\Pipelines\Cart\CalculateTax::class, $cartPipelines);
        // if (array_key_exists($calculateTaxIndex, $cartPipelines)) {
        //     $cartPipelines[$calculateTaxIndex] = \Dystcz\LunarApi\Domain\Carts\Pipelines\CalculateTax::class;
        // }
        //
        // // NOTE: Workaround
        // // Swap calculate pipeline
        // $calculateIndex = array_search(\Lunar\Pipelines\Cart\Calculate::class, $cartPipelines);
        // if (array_key_exists($calculateIndex, $cartPipelines)) {
        //     $cartPipelines[$calculateIndex] = \Dystcz\LunarApi\Domain\Carts\Pipelines\Calculate::class;
        // }

        Config::set('lunar.cart.pipelines.cart', $cartPipelines);

        Config::set(
            'lunar.cart.validators.set_payment_option',
            [\Dystcz\LunarApi\Domain\Carts\Validation\PaymentOptionValidator::class],
        );

        Config::set(
            'lunar.cart.actions.set_payment_option',
            \Dystcz\LunarApi\Domain\Carts\Actions\SetPaymentOption::class,
        );

        Config::set(
            'lunar.cart.actions.unset_payment_option',
            \Dystcz\LunarApi\Domain\Carts\Actions\UnsetPaymentOption::class,
        );

        Config::set(
            'lunar.cart.actions.order_create',
            \Dystcz\LunarApi\Domain\Carts\Actions\CreateOrder::class,
        );

        $orderPipelines = Config::get('lunar.orders.pipelines.creation', []);

        // Swap fill order from cart pipeline
        $fillOrderFromCartIndex = array_search(\Lunar\Pipelines\Order\Creation\FillOrderFromCart::class, $orderPipelines);
        if (array_key_exists($fillOrderFromCartIndex, $orderPipelines)) {
            $orderPipelines[$fillOrderFromCartIndex] = \Dystcz\LunarApi\Domain\Orders\Pipelines\FillOrderFromCart::class;
        }

        // Push create payment line pipeline after create shipping line pipeline
        $createShippingLineIndex = array_search(\Lunar\Pipelines\Order\Creation\CreateShippingLine::class, $orderPipelines);
        if (array_key_exists($createShippingLineIndex, $orderPipelines)) {
            $orderPipelines = array_merge(
                array_slice($orderPipelines, 0, $createShippingLineIndex + 1),
                [\Dystcz\LunarApi\Domain\Orders\Pipelines\CreatePaymentLine::class],
                array_slice($orderPipelines, $createShippingLineIndex + 1),
            );
        }
        //
        // // NOTE: Workaround
        // // Swap create order lines pipeline
        // $createOrderLinesIndex = array_search(\Lunar\Pipelines\Order\Creation\CreateOrderLines::class, $orderPipelines);
        // if (array_key_exists($createOrderLinesIndex, $orderPipelines)) {
        //     $orderPipelines[$createOrderLinesIndex] = \Dystcz\LunarApi\Domain\Orders\Pipelines\CreateOrderLines::class;
        // }
        //
        // // NOTE: Workaround
        // // Swap create order addresses pipeline
        // $createOrderAddressesIndex = array_search(\Lunar\Pipelines\Order\Creation\CreateOrderAddresses::class, $orderPipelines);
        // if (array_key_exists($createOrderAddressesIndex, $orderPipelines)) {
        //     $orderPipelines[$createOrderAddressesIndex] = \Dystcz\LunarApi\Domain\Orders\Pipelines\CreateOrderAddresses::class;
        // }
        //
        // // NOTE: Workaround
        // // Swap create shipping line pipeline
        // $createShippingLineIndex = array_search(\Lunar\Pipelines\Order\Creation\CreateShippingLine::class, $orderPipelines);
        // if (array_key_exists($createShippingLineIndex, $orderPipelines)) {
        //     $orderPipelines[$createShippingLineIndex] = \Dystcz\LunarApi\Domain\Orders\Pipelines\CreateShippingLine::class;
        // }

        // Swap clean up order lines pipeline
        $cleanupOrderLinesIndex = array_search(\Lunar\Pipelines\Order\Creation\CleanUpOrderLines::class, $orderPipelines);
        if (array_key_exists($cleanupOrderLinesIndex, $orderPipelines)) {
            $orderPipelines[$cleanupOrderLinesIndex] = \Dystcz\LunarApi\Domain\Orders\Pipelines\CleanUpOrderLines::class;
        }

        Config::set('lunar.orders.pipelines.creation', $orderPipelines);
    }

    /**
     * Publish migrations.
     */
    protected function publishMigrations(): void
    {
        $this->publishes([
            "{$this->root}/database/migrations/" => $this->app->databasePath('migrations'),
        ], 'lunar-api.migrations');
    }

    /**
     * Bind controllers.
     */
    protected function bindControllers(): void
    {
        $controllers = [
            \Dystcz\LunarApi\Domain\Addresses\Contracts\AddressesController::class => \Dystcz\LunarApi\Domain\Addresses\Http\Controllers\AddressesController::class,
            \Dystcz\LunarApi\Domain\Brands\Contracts\BrandsController::class => \Dystcz\LunarApi\Domain\Brands\Http\Controllers\BrandsController::class,
            \Dystcz\LunarApi\Domain\CartAddresses\Contracts\CartAddressShippingOptionController::class => \Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers\CartAddressShippingOptionController::class,
            \Dystcz\LunarApi\Domain\CartAddresses\Contracts\CartAddressesController::class => \Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers\CartAddressesController::class,
            \Dystcz\LunarApi\Domain\CartAddresses\Contracts\ContinuousUpdateCartAddressController::class => \Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers\ContinuousUpdateCartAddressController::class,
            \Dystcz\LunarApi\Domain\CartAddresses\Contracts\UpdateCartAddressCountryController::class => \Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers\UpdateCartAddressCountryController::class,
            \Dystcz\LunarApi\Domain\CartLines\Contracts\CartLinesController::class => \Dystcz\LunarApi\Domain\CartLines\Http\Controllers\CartLinesController::class,
            \Dystcz\LunarApi\Domain\Carts\Contracts\CartCouponsController::class => \Dystcz\LunarApi\Domain\Carts\Http\Controllers\CartCouponsController::class,
            \Dystcz\LunarApi\Domain\Carts\Contracts\CartPaymentOptionController::class => \Dystcz\LunarApi\Domain\Carts\Http\Controllers\CartPaymentOptionController::class,
            \Dystcz\LunarApi\Domain\Carts\Contracts\CartsController::class => \Dystcz\LunarApi\Domain\Carts\Http\Controllers\CartsController::class,
            \Dystcz\LunarApi\Domain\Carts\Contracts\CheckoutCartController::class => \Dystcz\LunarApi\Domain\Carts\Http\Controllers\CheckoutCartController::class,
            \Dystcz\LunarApi\Domain\Carts\Contracts\ClearUserCartController::class => \Dystcz\LunarApi\Domain\Carts\Http\Controllers\ClearUserCartController::class,
            \Dystcz\LunarApi\Domain\Carts\Contracts\CreateEmptyCartAddressesController::class => \Dystcz\LunarApi\Domain\Carts\Http\Controllers\CreateEmptyCartAddressesController::class,
            \Dystcz\LunarApi\Domain\Carts\Contracts\ReadUserCartController::class => \Dystcz\LunarApi\Domain\Carts\Http\Controllers\ReadUserCartController::class,
            \Dystcz\LunarApi\Domain\Channels\Contracts\ChannelsController::class => \Dystcz\LunarApi\Domain\Channels\Http\Controllers\ChannelsController::class,
            \Dystcz\LunarApi\Domain\Collections\Contracts\CollectionsController::class => \Dystcz\LunarApi\Domain\Collections\Http\Controllers\CollectionsController::class,
            \Dystcz\LunarApi\Domain\Countries\Contracts\CountriesController::class => \Dystcz\LunarApi\Domain\Countries\Http\Controllers\CountriesController::class,
            \Dystcz\LunarApi\Domain\Currencies\Contracts\CurrenciesController::class => \Dystcz\LunarApi\Domain\Currencies\Http\Controllers\CurrenciesController::class,
            \Dystcz\LunarApi\Domain\Customers\Contracts\CustomersController::class => \Dystcz\LunarApi\Domain\Customers\Http\Controllers\CustomersController::class,
            \Dystcz\LunarApi\Domain\Media\Contracts\MediaController::class => \Dystcz\LunarApi\Domain\Media\Http\Controllers\MediaController::class,
            \Dystcz\LunarApi\Domain\Orders\Contracts\CheckOrderPaymentStatusController::class => \Dystcz\LunarApi\Domain\Orders\Http\Controllers\CheckOrderPaymentStatusController::class,
            \Dystcz\LunarApi\Domain\Orders\Contracts\CreatePaymentIntentController::class => \Dystcz\LunarApi\Domain\Orders\Http\Controllers\CreatePaymentIntentController::class,
            \Dystcz\LunarApi\Domain\Orders\Contracts\MarkOrderAwaitingPaymentController::class => \Dystcz\LunarApi\Domain\Orders\Http\Controllers\MarkOrderAwaitingPaymentController::class,
            \Dystcz\LunarApi\Domain\Orders\Contracts\MarkOrderPendingPaymentController::class => \Dystcz\LunarApi\Domain\Orders\Http\Controllers\MarkOrderPendingPaymentController::class,
            \Dystcz\LunarApi\Domain\Orders\Contracts\OrdersController::class => \Dystcz\LunarApi\Domain\Orders\Http\Controllers\OrdersController::class,
            \Dystcz\LunarApi\Domain\PaymentOptions\Contracts\PaymentOptionsController::class => \Dystcz\LunarApi\Domain\PaymentOptions\Http\Controllers\PaymentOptionsController::class,
            \Dystcz\LunarApi\Domain\Payments\Contracts\HandlePaymentWebhookController::class => \Dystcz\LunarApi\Domain\Payments\Http\Controllers\HandlePaymentWebhookController::class,
            \Dystcz\LunarApi\Domain\ProductOptionValues\Contracts\ProductOptionValuesController::class => \Dystcz\LunarApi\Domain\ProductOptionValues\Http\Controllers\ProductOptionValuesController::class,
            \Dystcz\LunarApi\Domain\ProductVariants\Contracts\ProductVariantsController::class => \Dystcz\LunarApi\Domain\ProductVariants\Http\Controllers\ProductVariantsController::class,
            \Dystcz\LunarApi\Domain\Products\Contracts\ProductsController::class => \Dystcz\LunarApi\Domain\Products\Http\Controllers\ProductsController::class,
            \Dystcz\LunarApi\Domain\ShippingOptions\Contracts\ShippingOptionsController::class => \Dystcz\LunarApi\Domain\ShippingOptions\Http\Controllers\ShippingOptionsController::class,
            \Dystcz\LunarApi\Domain\Tags\Contracts\TagsController::class => \Dystcz\LunarApi\Domain\Tags\Http\Controllers\TagsController::class,
            \Dystcz\LunarApi\Domain\Urls\Contracts\UrlsController::class => \Dystcz\LunarApi\Domain\Urls\Http\Controllers\UrlsController::class,
            \Dystcz\LunarApi\Domain\Users\Contracts\UsersController::class => \Dystcz\LunarApi\Domain\Users\Http\Controllers\UsersController::class,
        ];

        foreach ($controllers as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    /**
     * Register commands.
     */
    protected function registerCommands(): void
    {
        $this->commands([
            \Dystcz\LunarApi\Domain\ProductVariants\Commands\GenerateUrls::class,
        ]);
    }

    /**
     * Register events.
     */
    protected function registerEvents(): void
    {
        $events = [
            \Dystcz\LunarApi\Domain\Orders\Events\OrderPaymentFailed::class => [
                \Dystcz\LunarApi\Domain\Payments\Listeners\HandleFailedPayment::class,
            ],
            \Dystcz\LunarApi\Domain\Orders\Events\OrderPaymentCanceled::class => [
                \Dystcz\LunarApi\Domain\Payments\Listeners\HandleFailedPayment::class,
            ],
            \Illuminate\Auth\Events\Login::class => [
                \Dystcz\LunarApi\Domain\Auth\Listeners\CartSessionAuthListener::class,
            ],
        ];

        foreach ($events as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    /**
     * Register payment.
     */
    protected function registerPayments(): void
    {
        // Offline payments
        \Dystcz\LunarApi\Domain\Payments\PaymentAdapters\OfflinePaymentAdapter::register();
        \Dystcz\LunarApi\Domain\Payments\PaymentAdapters\BankTransferPaymentAdapter::register();
        \Dystcz\LunarApi\Domain\Payments\PaymentAdapters\CashOnDeliveryPaymentAdapter::register();

        \Lunar\Facades\Payments::extend(
            'offline',
            fn (Application $app) => $app->make(
                \Dystcz\LunarApi\Domain\Payments\PaymentTypes\OfflinePaymentType::class,
            ),
        );
    }

    /**
     * Register observers.
     */
    protected function registerObservers(): void
    {
        \Lunar\Models\Order::observe(\Dystcz\LunarApi\Domain\Orders\Observers\OrderObserver::class);
    }

    /**
     * Swap models.
     */
    protected function registerModels(): void
    {
        ModelManifest::register(
            DomainConfigCollection::make()->getModels(),
        );
    }

    /**
     * Register dynamic relations.
     */
    protected function registerDynamicRelations(): void
    {
        \Lunar\Models\ProductVariant::resolveRelationUsing('attributes', function ($model) {
            return $model
                ->hasMany(
                    \Lunar\Models\Attribute::class,
                    'attribute_type',
                    'attribute_classname',
                );
        });

        \Lunar\Models\ProductVariant::resolveRelationUsing('urls', function ($model) {
            return $model
                ->morphMany(
                    \Lunar\Models\Url::class,
                    'element'
                );
        });

        \Lunar\Models\ProductVariant::resolveRelationUsing('defaultUrl', function ($model) {
            return $model
                ->morphOne(
                    \Lunar\Models\Url::class,
                    'element'
                )->whereDefault(true);
        });

        \Lunar\Models\ProductVariant::resolveRelationUsing('otherVariants', function ($model) {
            return $model
                ->hasMany(\Lunar\Models\ProductVariant::class, 'product_id', 'product_id')
                ->where($model->getRouteKeyName(), '!=', $model->getAttribute($model->getRouteKeyName()));
        });
    }

    /**
     * Bind models.
     */
    protected function bindModels(): void
    {
        $this->app->bind(
            \Dystcz\LunarApi\Domain\Carts\Contracts\CurrentSessionCart::class,
            function (Application $app): ?\Lunar\Models\Contracts\Cart {
                /** @var \Lunar\Managers\CartSessionManager $cartSession */
                $cartSession = $this->app->make(CartSessionInterface::class);

                return $cartSession->current();
            }
        );
    }

    /**
     * Register the application's policies.
     */
    public function registerPolicies(): void
    {
        DomainConfigCollection::make()
            ->getPolicies()
            ->each(
                fn (string $policy, string $model) => Gate::policy($model, $policy),
            );
    }
}
