<?php

use Dystcz\LunarApi\Domain\Brands\Models\Brand;
use Dystcz\LunarApi\Tests\TestCases\SwappedBrandPolicyTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

uses(SwappedBrandPolicyTestCase::class, RefreshDatabase::class);

describe('policies', function () {

    it('can extend brand policy in config', function () {
        /** @var SwappedBrandPolicyTestCase $this */
        $models = Brand::factory()
            ->count(5)
            ->create();

        $this->assertSame(
            \Dystcz\LunarApi\Tests\Stubs\Policies\TestBrandPolicy::class,
            Config::get('lunar-api.domains.brands.policy'),
        );

        $response = $this
            ->jsonApi()
            ->expects('brands')
            ->get(serverUrl('/brands'));

        $response
            ->assertErrorStatus([
                'detail' => 'Unauthenticated.',
                'status' => '401',
                'title' => 'Unauthorized',
            ]);

    })->group('config.policies.extending');

})->group('config.policies');
