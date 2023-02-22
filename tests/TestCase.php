<?php

namespace Dystcz\LunarApi\Tests;

use Cartalyst\Converter\Laravel\ConverterServiceProvider;
use Dystcz\LunarApi\LunarApiServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redis;
use Kalnoy\Nestedset\NestedSetServiceProvider;
use LaravelJsonApi\Spec\ServiceProvider;
use LaravelJsonApi\Testing\MakesJsonApiRequests;
use LaravelJsonApi\Testing\TestExceptionHandler;
use Lunar\Database\Factories\LanguageFactory;
use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Hub\Tests\Stubs\User;
use Lunar\LunarServiceProvider;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Spatie\LaravelBlink\BlinkServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;

abstract class TestCase extends Orchestra
{
    use MakesJsonApiRequests;

    protected function setUp(): void
    {
        parent::setUp();

        LanguageFactory::new()->create([
            'code' => 'en',
            'name' => 'English',
        ]);

        config()->set('providers.users.model', User::class);

        activity()->disableLogging();

        $currency = Currency::factory()->create();

        $taxClass = TaxClass::factory()->create();

        ShippingManifest::addOption(
            new ShippingOption(
                name: 'Basic Delivery',
                description: 'A basic delivery option',
                identifier: 'Friendly Freight Co.',
                price: new Price(500, $currency, 1),
                taxClass: $taxClass
            )
        );

        $this->beforeApplicationDestroyed(function () {
            Redis::flushall();
        });
    }

    /**
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LunarApiServiceProvider::class,

            // Laravel JsonApi
            \LaravelJsonApi\Encoder\Neomerx\ServiceProvider::class,
            \LaravelJsonApi\Laravel\ServiceProvider::class,
            ServiceProvider::class,

            // Lunar core
            LunarServiceProvider::class,
            MediaLibraryServiceProvider::class,
            ActivitylogServiceProvider::class,
            ConverterServiceProvider::class,
            NestedSetServiceProvider::class,
            BlinkServiceProvider::class,
        ];
    }

    /**
     * @param Application $app
     */
    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite');

        config()->set('database.migrations', 'migrations');

        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // TODO move to testbench.yaml
        config()->set('database.connections.mysql', [
            'driver' => 'mysql',
            'host' => 'mysql',
            'port' => '3306',
            'database' => 'lunar-api-testing',
            'username' => 'homestead',
            'password' => 'secret',
        ]);

        // TODO move to testbench.yaml
        config()->set('database.redis.default', [
            'host' => 'redis',
            'password' => 'secret_redis',
            'port' => '6379',
        ]);
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();

        // // artisan($this, 'lunar:install');
        // // artisan($this, 'vendor:publish', ['--tag' => 'lunar']);
        // // artisan($this, 'vendor:publish', ['--tag' => 'lunar.migrations']);
        //
        // // artisan($this, 'migrate', ['--database' => 'mysql']);
        //
        // $this->beforeApplicationDestroyed(
        //     fn () => artisan($this, 'migrate:rollback', ['--database' => 'mysql'])
        // );
    }

    protected function resolveApplicationExceptionHandler($app): void
    {
        $app->singleton(
            ExceptionHandler::class,
            TestExceptionHandler::class
        );
    }
}
