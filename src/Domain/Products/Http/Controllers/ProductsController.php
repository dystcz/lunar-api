<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Controllers;

use Dystcz\LunarApi\Domain\JsonApi\Builders\ProductJsonApiBuilder;
use Dystcz\LunarApi\Domain\OpenApi\Responses\ErrorNotFoundResponse;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductCollectionQuery;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductQuery;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use Dystcz\LunarApi\Domain\Products\OpenApi\Parameters\IndexProductParameters;
use Dystcz\LunarApi\Domain\Products\OpenApi\Parameters\ShowProductParameters;
use Dystcz\LunarApi\Domain\Products\OpenApi\Responses\IndexProductResponse;
use Dystcz\LunarApi\Domain\Products\OpenApi\Responses\ShowProductResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use Lunar\Models\Product;
use Spatie\QueryBuilder\QueryBuilder;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class ProductsController extends Controller
{
    // use Actions\FetchMany;
    // use Actions\FetchOne;
    // use Actions\Store;
    // use Actions\Update;
    // use Actions\Destroy;
    // use Actions\FetchRelated;
    // use Actions\FetchRelationship;
    // use Actions\UpdateRelationship;
    // use Actions\AttachRelationship;
    // use Actions\DetachRelationship;

    protected QueryBuilder $query;

    public function __construct(ProductJsonApiBuilder $builder)
    {
        $this->query = $builder->query();
    }

    /**
     * Fetch zero to many JSON API resources.
     *
     * @param ProductSchema $schema
     * @param ProductCollectionQuery $request
     * @return Responsable|Response
     */
    #[OpenApi\Operation(tags: ['products'])]
    #[OpenApi\Parameters(factory: IndexProductParameters::class)]
    #[OpenApi\Response(factory: IndexProductResponse::class, statusCode: 200)]
    public function index(ProductSchema $schema, ProductCollectionQuery $request): Responsable|Response
    {
        $models = $schema
            ->repository()
            ->queryAll()
            ->withRequest($request)
            ->firstOrPaginate($request->page());

        return new DataResponse($models);
    }

    // /**
    //  * List products.
    //  *
    //  * Lists all products based on provided filters.
    //  *
    //  * @return JsonApiResourceCollection
    //  */
    // #[OpenApi\Operation(tags: ['products'])]
    // #[OpenApi\Parameters(factory: IndexProductParameters::class)]
    // #[OpenApi\Response(factory: IndexProductResponse::class, statusCode: 200)]
    // public function index(): JsonApiResourceCollection
    // {
    //     $products = $this
    //         ->query
    //         ->with([
    //             'defaultUrl',
    //             'productType',
    //             'productType.mappedAttributes',
    //             'productType.mappedAttributes.attributeGroup',
    //         ])
    //         ->paginate(Config::get('lunar-api.domains.products.pagination', 12));
    //
    //     return ProductResource::collection($products);
    // }

    /**
     * Fetch zero to one JSON API resource by id.
     *
     * @param ProductSchema $schema
     * @param ProductQuery $request
     * @param Product $product
     * @return Responsable|Response
     */
    #[OpenApi\Operation(tags: ['products'])]
    #[OpenApi\Parameters(factory: ShowProductParameters::class)]
    #[OpenApi\Response(factory: ShowProductResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: ErrorNotFoundResponse::class, statusCode: 404)]
    public function show(ProductSchema $schema, ProductQuery $request, Product $product): Responsable|Response
    {
        $model = $schema
            ->repository()
            ->queryOne($product)
            ->withRequest($request)
            ->first();

        // do something custom...

        return new DataResponse($model);
    }

    // /**
    //  * Show product.
    //  *
    //  * Show product by slug.
    //  *
    //  * @param  string  $slug
    //  * @return JsonApiResource
    //  */
    // #[OpenApi\Operation(tags: ['products'])]
    // #[OpenApi\Parameters(factory: ShowProductParameters::class)]
    // #[OpenApi\Response(factory: ShowProductResponse::class, statusCode: 200)]
    // #[OpenApi\Response(factory: ErrorNotFoundResponse::class, statusCode: 404)]
    // public function show(string $slug): JsonApiResource
    // {
    //     $product = $this->query
    //         ->whereHas(
    //             'defaultUrl',
    //             fn ($query) => $query
    //                 ->where('slug', $slug)
    //                 ->whereHas(
    //                     'language',
    //                     fn ($query) => $query->where('code', App::getLocale())
    //                 )
    //         )
    //         ->firstOrFail();
    //
    //     return new ProductResource($product);
    // }
}
