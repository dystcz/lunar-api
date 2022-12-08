<?php

namespace Dystcz\LunarApi\Domain\Collections\Http\Controllers;

use Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionCollectionQuery;
use Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionQuery;
use Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionSchema;
use Dystcz\LunarApi\Domain\Collections\OpenApi\Parameters\IndexCollectionParameters;
use Dystcz\LunarApi\Domain\Collections\OpenApi\Parameters\ShowCollectionParameters;
use Dystcz\LunarApi\Domain\Collections\OpenApi\Responses\IndexCollectionResponse;
use Dystcz\LunarApi\Domain\Collections\OpenApi\Responses\ShowCollectionResponse;
use Dystcz\LunarApi\Domain\OpenApi\Responses\ErrorNotFoundResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Models\Collection;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class CollectionsController extends Controller
{
    /**
     * Fetch zero to many JSON API resources.
     *
     * @param CollectionSchema $schema
     * @param CollectionCollectionQuery $request
     * @return Responsable|Response
     */
    #[OpenApi\Operation(tags: ['collections'])]
    #[OpenApi\Parameters(factory: IndexCollectionParameters::class)]
    #[OpenApi\Response(factory: IndexCollectionResponse::class, statusCode: 200)]
    public function index(CollectionSchema $schema, CollectionCollectionQuery $request): Responsable|Response
    {
        $models = $schema
            ->repository()
            ->queryAll()
            ->withRequest($request)
            ->firstOrPaginate($request->page());

        return new DataResponse($models);
    }

    /**
     * Fetch zero to one JSON API resource by id.
     *
     * @param CollectionSchema $schema
     * @param CollectionQuery $request
     * @param Collection $product
     * @return Responsable|Response
     */
    #[OpenApi\Operation(tags: ['collections'])]
    #[OpenApi\Parameters(factory: ShowCollectionParameters::class)]
    #[OpenApi\Response(factory: ShowCollectionResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: ErrorNotFoundResponse::class, statusCode: 404)]
    public function show(CollectionSchema $schema, CollectionQuery $request, Collection $collection): Responsable|Response
    {
        $model = $schema
            ->repository()
            ->queryOne($collection)
            ->withRequest($request)
            ->first();

        // do something custom...

        return new DataResponse($model);
    }
}
