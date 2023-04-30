<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductQuery;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\Products\ProductViews;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Contracts\Routing\Route;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;
use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;

class ProductsController extends Controller
{
    use FetchMany;
    use FetchOne;
    use FetchRelated;
    use FetchRelationship;

    // /**
    //  * Fetch zero to many JSON API resources.
    //  *
    //  * @return Responsable|Response
    //  */
    // public function index(Route $route)
    // {
    //     $request = ResourceQuery::queryMany(
    //         $resourceType = $route->resourceType()
    //     );
    //
    //     dd($request);
    //
    // }

    public function read(?Product $product, ProductQuery $query): void
    {
        $productId = $product?->id;

        if ($productId) {
            dispatch(function () use ($productId) {
                App::get(ProductViews::class)->record($productId);
            });
        }
    }
}
