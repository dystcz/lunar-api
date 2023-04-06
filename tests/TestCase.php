<?php

namespace Dystcz\LunarApi\Tests;

use Dystcz\LunarApi\LunarApiServiceProvider;
use Dystcz\LunarApi\Tests\Stubs\Carts\Modifiers\TestShippingModifier;
use Dystcz\LunarApi\Tests\Stubs\JsonApi\V1\Server;
use Dystcz\LunarApi\Tests\Stubs\Lunar\TestTaxDriver;
use Dystcz\LunarApi\Tests\Stubs\Lunar\TestUrlGenerator;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use LaravelJsonApi\Testing\MakesJsonApiRequests;
use LaravelJsonApi\Testing\TestExceptionHandler;
use Lunar\Base\ShippingModifiers;
use Lunar\Facades\Taxes;
use Lunar\Models\Channel;
use Lunar\Models\Currency;
use Lunar\Models\CustomerGroup;
use Lunar\Models\TaxClass;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use MakesJsonApiRequests;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('auth.providers.users', [
            'driver' => 'eloquent',
            'model' => \Dystcz\LunarApi\Tests\Stubs\Users\User::class,
        ]);
        Config::set('lunar.urls.generator', TestUrlGenerator::class);
        Config::set('lunar.taxes.driver', 'test');

        Taxes::extend('test', function ($app) {
            return $app->make(TestTaxDriver::class);
        });

        Currency::factory()->create([
            'code' => 'EUR',
            'decimal_places' => 2,
        ]);

        Channel::factory()->create([
            'default' => true,
        ]);

        CustomerGroup::factory()->create([
            'default' => true,
        ]);

        TaxClass::factory()->create();

        App::get(ShippingModifiers::class)->add(TestShippingModifier::class);

        activity()->disableLogging();

        $this->beforeApplicationDestroyed(function () {
            Redis::flushall();
        });
    }

    /**
     * @param  Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LunarApiServiceProvider::class,

            // Laravel JsonApi
            \LaravelJsonApi\Encoder\Neomerx\ServiceProvider::class,
            \LaravelJsonApi\Laravel\ServiceProvider::class,
            \LaravelJsonApi\Spec\ServiceProvider::class,

            // Lunar core
            \Lunar\LunarServiceProvider::class,
            \Spatie\MediaLibrary\MediaLibraryServiceProvider::class,
            \Spatie\Activitylog\ActivitylogServiceProvider::class,
            \Cartalyst\Converter\Laravel\ConverterServiceProvider::class,
            \Kalnoy\Nestedset\NestedSetServiceProvider::class,
            \Spatie\LaravelBlink\BlinkServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    public function getEnvironmentSetUp($app)
    {
        Config::set('lunar-api.additional_servers', [
            Server::class,
        ]);

        // Set cart auto creation to true
        Config::set('lunar.cart.auto_create', true);

        Config::set('database.default', 'sqlite');

        Config::set('database.migrations', 'migrations');

        Config::set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // TODO: move to testbench.yaml
        Config::set('database.connections.mysql', [
            'driver' => 'mysql',
            'host' => 'mysql',
            'port' => '3306',
            'database' => 'lunar-api-testing',
            'username' => 'homestead',
            'password' => 'secret',
        ]);

        // TODO: move to testbench.yaml
        Config::set('database.redis.default', [
            'host' => 'localhost',
            'password' => '',
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
