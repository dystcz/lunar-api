<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductCollectionQuery;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductQuery;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use Lunar\Models\Product;

class ProductsController extends Controller
{
    use Actions\FetchMany;
    use Actions\FetchOne;
    // use Actions\Store;
    // use Actions\Update;
    // use Actions\Destroy;
    use Actions\FetchRelated;
    use Actions\FetchRelationship;
    // use Actions\UpdateRelationship;
    // use Actions\AttachRelationship;
    // use Actions\DetachRelationship;

    /**
     * Fetch zero to many JSON API resources.
     *
     * @param ProductSchema $schema
     * @param ProductCollectionQuery $request
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
     * @param ProductSchema $schema
     * @param ProductQuery $request
     * @param Product $product
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
}
