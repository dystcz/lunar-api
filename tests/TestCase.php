<?php

namespace Madnest\Madzipper\Tests;

use Dystcz\GetcandyApi\GetcandyApiServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            GetcandyApiServiceProvider::class,
        ];
    }
}
