<?php

namespace Dystcz\LunarApi\Tests;

use Cartalyst\Converter\Laravel\ConverterServiceProvider;
use Dystcz\LunarApi\LunarApiServiceProvider;
use Kalnoy\Nestedset\NestedSetServiceProvider;
use LaravelJsonApi\Encoder\Neomerx\ServiceProvider;
use LaravelJsonApi\Laravel\ServiceProvider as LaravelJsonApiServiceProvider;
use LaravelJsonApi\Testing\MakesJsonApiRequests;
use Lunar\Database\Factories\LanguageFactory;
use Lunar\Hub\Tests\Stubs\User;
use Lunar\LunarServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Spatie\LaravelBlink\BlinkServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use function Orchestra\Testbench\artisan;

abstract class MySqlTestCase extends TestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        config()->set('database.default', 'mysql');
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

    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function defineDatabaseMigrations()
    {
         // $this->loadLaravelMigrations();
    }
}
