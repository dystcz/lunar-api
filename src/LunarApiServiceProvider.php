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

        // Register the main class to use with the facade.
        $this->app->singleton(
            'lunar-api',
            fn () => new LunarApi,
        );

        // Register payment adapters register.
        $this->app->singleton(
            \Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister::class,
            fn () => new \Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister,
        );

        // Register payment modifiers.
        $this->app->singleton(
            \Dystcz\LunarApi\Domain\PaymentOptions\Modifiers\PaymentModifiers::class,
            fn (Application $app) => new \Dystcz\LunarApi\Domain\PaymentOptions\Modifiers\PaymentModifiers(),
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
        $this->registerObservers();
        $this->registerEvents();

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
        $applyShippingIndex = array_search(\Lunar\Pipelines\Cart\ApplyShipping::class, $cartPipelines);

        if ($applyShippingIndex) {
            $cartPipelines = array_merge(
                array_slice($cartPipelines, 0, $applyShippingIndex + 1),
                [\Dystcz\LunarApi\Domain\Carts\Pipelines\ApplyPayment::class],
                array_slice($cartPipelines, $applyShippingIndex + 1),
            );
        }

        // Push CalculatePayment pipeline after Calculate pipeline
        $calculateIndex = array_search(\Lunar\Pipelines\Cart\Calculate::class, $cartPipelines);

        if ($calculateIndex) {
            $cartPipelines = array_merge(
                array_slice($cartPipelines, 0, $calculateIndex + 1),
                [\Dystcz\LunarApi\Domain\Carts\Pipelines\CalculatePayment::class],
                array_slice($cartPipelines, $calculateIndex + 1),
            );
        }

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

        // Push ApplyPayment pipeline after ApplyShipping pipeline
        $orderPipelines = Config::get('lunar.orders.pipelines.creation', []);
        $createShippingLineIndex = array_search(\Lunar\Pipelines\Order\Creation\CreateShippingLine::class, $orderPipelines);

        if ($createShippingLineIndex) {
            $orderPipelines = array_merge(
                array_slice($orderPipelines, 0, $createShippingLineIndex + 1),
                [\Dystcz\LunarApi\Domain\Orders\Pipelines\CreatePaymentLine::class],
                array_slice($orderPipelines, $createShippingLineIndex + 1),
            );
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
     * Register observers.
     */
    protected function registerObservers(): void
    {
        // NOTE: Use custom observer, because Lunar ignores only shipping purchasable type
        $orderLineEventDispatcher = \Lunar\Models\OrderLine::getEventDispatcher();
        $orderLineEventDispatcher->forget('eloquent.creating: '.\Lunar\Models\OrderLine::class);
        $orderLineEventDispatcher->forget('eloquent.updating: '.\Lunar\Models\OrderLine::class);

        $orderLine = ModelManifest::getRegisteredModel(\Lunar\Models\OrderLine::class);

        \Dystcz\LunarApi\Domain\OrderLines\Models\OrderLine::observe(\Dystcz\LunarApi\Domain\OrderLines\Observers\OrderLineObserver::class);
        \Lunar\Models\Order::observe(\Dystcz\LunarApi\Domain\Orders\Observers\OrderObserver::class);
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
        DomainConfigCollection::make()
            ->getPolicies()
            ->each(
                fn (string $policy, string $model) => Gate::policy($model, $policy),
            );
    }
}
