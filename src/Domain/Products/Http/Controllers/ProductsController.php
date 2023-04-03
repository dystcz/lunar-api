<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductCollectionQuery;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductQuery;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use Dystcz\LunarApi\Domain\Products\ProductViews;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;
use Lunar\Models\Product;

class ProductsController extends Controller
{
    use FetchMany;
    use FetchOne;

    // use Actions\Store;
    // use Actions\Update;
    // use Actions\Destroy;
    use FetchRelated;
    use FetchRelationship;

    // use Actions\UpdateRelationship;
    // use Actions\AttachRelationship;
    // use Actions\DetachRelationship;

    /**
     * Fetch zero to many JSON API resources.
     *
     * @param  ProductSchema  $schema
     * @param  ProductCollectionQuery  $request
     * @return Responsable|Response
     */
    // public function index(ProductSchema $schema, ProductCollectionQuery $request): Responsable|Response
    // {
    //     $models = $schema
    //         ->repository()
    //         ->queryAll()
    //         ->withRequest($request)
    //         ->firstOrPaginate($request->page());
    //
    //     return new DataResponse($models);
    // }

    /**
     * Fetch zero to one JSON API resource by id.
     *
     * @param  ProductSchema  $schema
     * @param  ProductQuery  $request
     * @param  Product  $product
     * @return Responsable|Response
     */
    // public function show(ProductSchema $schema, ProductQuery $request, Product $product): Responsable|Response
    // {
    //     $model = $schema
    //         ->repository()
    //         ->queryOne($product)
    //         ->withRequest($request)
    //         ->first();
    //
    //     // do something custom...
    //
    //     return new DataResponse($model);
    // }

    public function read(?\Dystcz\LunarApi\Domain\Products\Models\Product $product, ProductQuery $query): void
    {
        $productId = $product?->id;

        if ($productId) {
            dispatch(function () use ($productId) {
                App::get(ProductViews::class)->record($productId);
            });
        }
    }
}
