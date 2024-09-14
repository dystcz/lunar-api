<?php

use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class);

test('product variant creates url after being created', function () {
    /** @var TestCase $this */
    generateUrls();

    /** @var ProductVariant $variant */
    $variant = ProductVariantFactory::new()->create();

    expect($variant->urls)->toHaveCount(1);

})->group('product-variants');
