<?php

use Dystcz\LunarApi\Domain\Products\ProductViews;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

uses(\Dystcz\LunarApi\Tests\TestCase::class, RefreshDatabase::class);

it('can record a view', function () {
    app(ProductViews::class)->record(1);
    app(ProductViews::class)->record(1);

    expect(\Illuminate\Support\Facades\Redis::zRange('product:views:1', 0, -1))
        ->toHaveCount(2);
});

it('removes old entries', function () {
    Redis::zAdd("product:views:1", time() - 60 * 60, Str::uuid()->toString());

    expect(\Illuminate\Support\Facades\Redis::zRange('product:views:1', 0, -1))
        ->toHaveCount(1);

    app(ProductViews::class)->record(1);

    expect(\Illuminate\Support\Facades\Redis::zRange('product:views:1', 0, -1))
        ->toHaveCount(1);
});

it('returns a list of product\'s ids sorted by most viewed', function () {
    app(ProductViews::class)->record(1);
    app(ProductViews::class)->record(2);
    app(ProductViews::class)->record(2);

    $sorted = app(ProductViews::class)->sorted();

    expect($sorted)->toBe([2, 1]);
});