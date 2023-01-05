<?php

namespace Dystcz\LunarApi\Tests;

use Cartalyst\Converter\Laravel\ConverterServiceProvider;
use Dystcz\LunarApi\LunarApiServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Kalnoy\Nestedset\NestedSetServiceProvider;
use LaravelJsonApi\Testing\MakesJsonApiRequests;
use LaravelJsonApi\Testing\TestExceptionHandler;
use Lunar\Database\Factories\LanguageFactory;
use Lunar\Hub\Tests\Stubs\User;
use Lunar\LunarServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Spatie\LaravelBlink\BlinkServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use function Orchestra\Testbench\artisan;

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

        $this->beforeApplicationDestroyed(function () {
            \Illuminate\Support\Facades\Redis::flushall();
        });
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
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
            LunarServiceProvider::class,
            MediaLibraryServiceProvider::class,
            ActivitylogServiceProvider::class,
            ConverterServiceProvider::class,
            NestedSetServiceProvider::class,
            BlinkServiceProvider::class,
        ];
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
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
    // protected function defineDatabaseMigrations()
    // {
    //     $this->loadLaravelMigrations(['--database' => 'mysql']);
    //
    //     // artisan($this, 'lunar:install');
    //     // artisan($this, 'vendor:publish', ['--tag' => 'lunar']);
    //     // artisan($this, 'vendor:publish', ['--tag' => 'lunar.migrations']);
    //
    //     // artisan($this, 'migrate', ['--database' => 'mysql']);
    //
    //     $this->beforeApplicationDestroyed(
    //         fn () => artisan($this, 'migrate:rollback', ['--database' => 'mysql'])
    //     );
    // }

    protected function resolveApplicationExceptionHandler($app): void
    {
        $app->singleton(
            ExceptionHandler::class,
            TestExceptionHandler::class
        );
    }
}
