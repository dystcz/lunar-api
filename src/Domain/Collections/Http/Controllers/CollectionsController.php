<?php

namespace Dystcz\LunarApi\Domain\Collections\Http\Controllers;

use Dystcz\LunarApi\Domain\Collections\Http\Resources\CollectionResource;
use Dystcz\LunarApi\Domain\Collections\OpenApi\Parameters\IndexCollectionParameters;
use Dystcz\LunarApi\Domain\Collections\OpenApi\Parameters\ShowCollectionParameters;
use Dystcz\LunarApi\Domain\Collections\OpenApi\Responses\IndexCollectionResponse;
use Dystcz\LunarApi\Domain\Collections\OpenApi\Responses\ShowCollectionResponse;
use Dystcz\LunarApi\Domain\JsonApi\Builders\CollectionJsonApiBuilder;
use Dystcz\LunarApi\Domain\OpenApi\Responses\ErrorNotFoundResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Spatie\QueryBuilder\QueryBuilder;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class CollectionsController extends Controller
{
    protected QueryBuilder $query;

    public function __construct(CollectionJsonApiBuilder $builder)
    {
        $this->query = $builder->query();
    }

    /**
     * List collections.
     *
     * Lists all collections.
     *
     * @return JsonApiResourceCollection
     */
    #[OpenApi\Operation(tags: ['collections'])]
    #[OpenApi\Parameters(factory: IndexCollectionParameters::class)]
    #[OpenApi\Response(factory: IndexCollectionResponse::class, statusCode: 200)]
    public function index(): JsonApiResourceCollection
    {
        $collections = $this->query->get();

        return CollectionResource::collection($collections);
    }

    /**
     * Show collection.
     *
     * Show collection by slug.
     *
     * @param  string  $slug
     * @return JsonApiResource
     */
    #[OpenApi\Operation(tags: ['collections'])]
    #[OpenApi\Parameters(factory: ShowCollectionParameters::class)]
    #[OpenApi\Response(factory: ShowCollectionResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: ErrorNotFoundResponse::class, statusCode: 404)]
    public function show(string $slug): JsonApiResource
    {
        $collection = $this->query
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

        return new CollectionResource($collection);
    }
}
