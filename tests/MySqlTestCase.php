<?php

namespace Dystcz\LunarApi\Tests;

use Illuminate\Foundation\Application;

abstract class MySqlTestCase extends TestCase
{
    /**
     * @param  Application  $app
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
     * @param  Application  $app
     */
    protected function defineDatabaseMigrations()
    {
        // $this->loadLaravelMigrations();
    }
}
