<?php

namespace Dystcz\LunarApi\Tests\TestCases;

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Support\Facades\Config;

abstract class SwappedBrandPolicyTestCase extends TestCase
{
    /**
     * @param  Application  $app
     */
    public function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        Config::set(
            'lunar-api.domains.brands.policy',
            \Dystcz\LunarApi\Tests\Stubs\Policies\TestBrandPolicy::class,
        );
    }
}
