<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductQuery;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;

class ProductsController extends Controller
{
    use FetchMany;
    use FetchOne;
    use FetchRelated;
    use FetchRelationship;

    public function read(?Product $product, ProductQuery $query): void
    {
        $productId = $product?->id;

        if ($productId && App::has('lunar-api-product-views')) {
            dispatch(fn () => App::get('lunar-api-product-views')->record($productId));
        }
    }
}
