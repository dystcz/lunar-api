<?php

use Dystcz\LunarApi\Domain\Brands\Factories\BrandFactory;
use Dystcz\LunarApi\Domain\CollectionGroups\Factories\CollectionGroupFactory;
use Dystcz\LunarApi\Domain\Collections\Factories\CollectionFactory;
use Dystcz\LunarApi\Domain\Media\Factories\MediaFactory;
use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\ProductAssociations\Factories\ProductAssociationFactory;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Domain\Tags\Factories\TagFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Lunar\Database\Factories\LanguageFactory;

uses(TestCase::class, RefreshDatabase::class);

it('can list bare products', function () {
    /** @var TestCase $this */
    $products = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->has(PriceFactory::new())->count(2),
            'variants'
        )
        ->count(3)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl('/products'));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($products)
        ->assertDoesntHaveIncluded();
})->group('products');

it('can show product detail', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl('/products/'.$product->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product)
        ->assertDoesntHaveIncluded();
})->group('products');

it('returns error response when product doesnt exists', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl('/products/1'));

    $response
        ->assertErrorStatus([
            'status' => '404',
            'title' => 'Not Found',
        ]);
})->group('products');

test('product can include images', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->has(MediaFactory::new(), 'images')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths('images')
        ->get(serverUrl('/products/'.$product->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product)
        ->assertIsIncluded('media', $product->images->first());
})->group('products');

test('product can include thumbnail', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->has(MediaFactory::new()->thumbnail(), 'images')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths('thumbnail')
        ->get(serverUrl('/products/'.$product->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product)
        ->assertIsIncluded('media', $product->thumbnail);
})->group('products');

test('product can include variants', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->has(PriceFactory::new())->count(3),
            'variants'
        )
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths('variants')
        ->get(serverUrl('/products/'.$product->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product)
        ->assertIsIncluded('variants', $product->variants->first());
})->group('products');

it('can show product with variants count', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->has(PriceFactory::new())->count(5),
            'variants'
        )
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get('/api/v1/products/'.$product->getRouteKey().'?withCount=variants');

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product);

    expect($response->json('data.relationships.variants.meta.count'))->toBe(5);
})->group('products');

test('product can include tags', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->has(TagFactory::new()->count(2))
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths('tags')
        ->get('/api/v1/products/'.$product->getRouteKey());

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product)
        ->assertIncluded([
            ['type' => 'tags', 'id' => $product->tags[0]->getRouteKey()],
            ['type' => 'tags', 'id' => $product->tags[1]->getRouteKey()],
        ]);
})->group('products');

it('can list products with all includes', function () {
    LanguageFactory::new()->create();

    Config::set('lunar.urls.generator', \Lunar\Generators\UrlGenerator::class);

    $allModels = [];

    /** @var TestCase $this */
    $models = ProductFactory::new()
        ->has(TagFactory::new()->count(2), 'tags')
        ->has(MediaFactory::new()->thumbnail(), 'images')
        ->has(ProductVariantFactory::new()->has(PriceFactory::new(), 'prices'), 'variants')
        ->count(3)
        ->create(['brand_id' => fn () => BrandFactory::new()->has(MediaFactory::new()->thumbnail(), 'media')->create()]);

    $allModels['products'] = [...$models];

    foreach ($models as $model) {

        CollectionFactory::new()
            ->has(CollectionGroupFactory::new(), 'group')
            ->hasAttached($model, ['position' => 1], 'products')
            ->create();

        ProductAssociationFactory::new()
            ->create([
                'product_parent_id' => $model,
                'product_target_id' => $target = ProductFactory::new()
                    ->has(ProductVariantFactory::new()->has(PriceFactory::new(), 'prices'), 'variants')
                    ->has(MediaFactory::new()->thumbnail(), 'media')
                    ->create(),
            ]);

        ProductAssociationFactory::new()
            ->create([
                'product_target_id' => $model,
                'product_parent_id' => $parent = ProductFactory::new()
                    ->has(ProductVariantFactory::new()->has(PriceFactory::new(), 'prices'), 'variants')
                    ->has(MediaFactory::new()->thumbnail(), 'media')
                    ->create(),
            ]);

        $allModels['targets'][] = $target;
        $allModels['parents'][] = $parent;
    }

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths(
            'default_url',
            'images',
            'lowest_price',
            'prices',
            'thumbnail',

            'associations',
            'associations.target.default_url',
            'associations.target.thumbnail',
            'associations.target.lowest_price',
            'associations.target.variants.prices',

            'inverse_associations',
            'inverse_associations.parent.default_url',
            'inverse_associations.parent.thumbnail',
            'inverse_associations.parent.lowest_price',
            'inverse_associations.target.variants.prices',

            'brand',
            'brand.default_url',
            'brand.thumbnail',

            'cheapest_variant',
            'cheapest_variant.images',
            'cheapest_variant.prices',

            'collections',
            'collections.default_url',
            'collections.group',

            'variants',
            'variants.images',
            'variants.prices',

            // 'variants.thumbnail',

            'tags',
        )
        ->get(serverUrl('/products'));

    // $res = ray($response->json('included'))->green();
    // ray(
    //     $data = collect($response->json('included'))->map(fn ($item) => ['type' => $item['type'], 'id' => $item['id']]),
    //     collect($response->json('included'))->groupBy('type'),
    //     $data->groupBy('type')->mapWithKeys(fn ($values, $type) => [$type => $values->count()])
    // )->orange();

    $products = Collection::make($allModels['products'])->flatMap(fn ($model) => [
        // Media
        ['type' => 'media', 'id' => (string) $model->thumbnail->getRouteKey()],
        ['type' => 'urls', 'id' => (string) $model->defaultUrl->getRouteKey()],

        // Associations
        ['type' => 'associations', 'id' => (string) $model->associations->first()->getRouteKey()],
        ['type' => 'associations', 'id' => (string) $model->inverseAssociations->first()->getRouteKey()],

        // Brand
        ['type' => 'brands', 'id' => (string) $model->brand->getRouteKey()],
        ['type' => 'urls', 'id' => (string) $model->brand->defaultUrl->getRouteKey()],
        ['type' => 'media', 'id' => (string) $model->brand->thumbnail->getRouteKey()],

        // Other variant
        ['type' => 'variants', 'id' => (string) $model->variants[0]->getRouteKey()],
        ['type' => 'prices', 'id' => (string) $model->prices[0]->first()->getRouteKey()],

        // Collection
        ['type' => 'collections', 'id' => (string) $model->collections->first()->getRouteKey()],
        ['type' => 'urls', 'id' => (string) $model->collections->first()->defaultUrl->getRouteKey()],
        ['type' => 'collections-groups', 'id' => (string) $model->collections->first()->group->getRouteKey()],

        // Product tags
        ['type' => 'tags', 'id' => (string) $model->tags[0]->getRouteKey()],
        ['type' => 'tags', 'id' => (string) $model->tags[1]->getRouteKey()],
    ]);

    // Association targets
    $targets = Collection::make($allModels['targets'])->flatMap(fn ($model) => [
        ['type' => 'products', 'id' => (string) $model->getRouteKey()],
        ['type' => 'variants', 'id' => (string) $model->variants[0]->getRouteKey()],
        ['type' => 'media', 'id' => (string) $model->thumbnail->getRouteKey()],
        ['type' => 'urls', 'id' => (string) $model->defaultUrl->getRouteKey()],
        ['type' => 'prices', 'id' => (string) $model->prices[0]->getRouteKey()],
    ]);

    // Inverse association parents
    $parents = Collection::make($allModels['parents'])->flatMap(fn ($model) => [
        ['type' => 'products', 'id' => (string) $model->getRouteKey()],
        ['type' => 'variants', 'id' => (string) $model->variants[0]->getRouteKey()],
        ['type' => 'urls', 'id' => (string) $model->defaultUrl->getRouteKey()],
        ['type' => 'media', 'id' => (string) $model->thumbnail->getRouteKey()],
    ]);

    $allModels = Collection::make(Arr::flatten($allModels));

    // ray($allModels->map(fn ($m) => [
    //     'id' => $m->id,
    //     'prices' => $m->prices,
    // ]))->red();

    $included = Collection::make()
        ->merge($products)
        ->merge($targets)
        ->merge($parents);

    // ray($included->groupBy('type')->mapWithKeys(fn ($values, $type) => [$type => $values->count()]))->purple();
    // ray($included)->blue();
    //
    // $res = $response->json('included');
    // // dd($res, $included);
    // $res = collect($res)->map(fn ($item) => ['type' => $item['type'], 'id' => $item['id']])->sortBy('id')->groupBy('type');
    // $included = $included->sortBy('id')->groupBy('type');
    //
    // ray($res, $included);

    $response
        ->assertSuccessful()
        ->assertFetchedMany($allModels->map(fn ($model) => [
            'type' => 'products',
            'id' => (string) $model->getRouteKey(),
            'attributes' => [
                'name' => $model->attr('name'),
                'description' => $model->attr('description'),
            ],
        ]));
    // ->assertIncluded($included->toArray());
})->group('products')
    ->todo();
