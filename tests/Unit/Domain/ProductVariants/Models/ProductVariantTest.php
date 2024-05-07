<?php

use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Lunar\Database\Factories\LanguageFactory;
use Lunar\FieldTypes\Text;
use Lunar\Generators\UrlGenerator;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class);

test('product variant creates url after being created', function () {
    /** @var TestCase $this */
    $language = LanguageFactory::new()->create();

    Config::set('lunar.urls.generator', UrlGenerator::class);

    /** @var ProductVariant $variant */
    $variant = ProductVariantFactory::new()->create([
        'attribute_data' => collect([
            'name' => new Text($this->faker->name),
            'description' => new Text($this->faker->sentence),
        ]),
    ]);

    expect($variant->urls)->toHaveCount(1);

})->group('product-variants');
