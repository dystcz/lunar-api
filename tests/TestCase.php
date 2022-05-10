<?php

namespace Dystcz\GetcandyApi\Tests;

use Dystcz\GetcandyApi\GetcandyApiServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
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
