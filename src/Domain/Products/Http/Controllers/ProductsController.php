<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Controllers;

use Dystcz\LunarApi\Domain\JsonApi\Builders\ProductJsonApiBuilder;
use Dystcz\LunarApi\Domain\OpenApi\Responses\ErrorNotFoundResponse;
use Dystcz\LunarApi\Domain\Products\Http\Resources\ProductResource;
use Dystcz\LunarApi\Domain\Products\OpenApi\Parameters\IndexProductParameters;
use Dystcz\LunarApi\Domain\Products\OpenApi\Parameters\ShowProductParameters;
use Dystcz\LunarApi\Domain\Products\OpenApi\Responses\IndexProductResponse;
use Dystcz\LunarApi\Domain\Products\OpenApi\Responses\ShowProductResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Spatie\QueryBuilder\QueryBuilder;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class ProductsController extends Controller
{
    protected QueryBuilder $query;

    public function __construct(ProductJsonApiBuilder $builder)
    {
        $this->query = $builder->query();
    }

    /**
     * List products.
     *
     * Lists all products based on provided filters.
     *
     * @return JsonApiResourceCollection
     */
    #[OpenApi\Operation(tags: ['products'])]
    #[OpenApi\Parameters(factory: IndexProductParameters::class)]
    #[OpenApi\Response(factory: IndexProductResponse::class, statusCode: 200)]
    public function index(): JsonApiResourceCollection
    {
        $products = $this
            ->query
            ->with([
                'defaultUrl',
                'productType',
                'productType.mappedAttributes',
                'productType.mappedAttributes.attributeGroup',
            ])
            ->paginate(Config::get('lunar-api.domains.products.pagination', 12));

        return ProductResource::collection($products);
    }

    /**
     * Show product.
     *
     * Show product by slug.
     *
     * @param  string  $slug
     * @return JsonApiResource
     */
    #[OpenApi\Operation(tags: ['products'])]
    #[OpenApi\Parameters(factory: ShowProductParameters::class)]
    #[OpenApi\Response(factory: ShowProductResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: ErrorNotFoundResponse::class, statusCode: 404)]
    public function show(string $slug): JsonApiResource
    {
        $product = $this->query
            ->whereHas(
                'defaultUrl',
                fn ($query) => $query
                    ->where('slug', $slug)
                    ->whereHas(
                        'language',
                        fn ($query) => $query->where('code', App::getLocale())
                    )
            )
            ->firstOrFail();

        return new ProductResource($product);
    }
}
