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
     * Register the application services.
     */
    public function register(): void
    {
        $this->registerConfig();

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
            PaymentAdaptersRegister::class,
            fn () => new PaymentAdaptersRegister,
        );
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom("{$this->root}/routes/api.php");

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
        DomainConfigCollection::make()
            ->getPolicies()
            ->each(
                fn (string $policy, string $model) => Gate::policy($model, $policy),
            );

    }
}
