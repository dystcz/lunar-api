<?php

namespace Dystcz\LunarApi\Domain\Collections\Http\Controllers;

use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;
use Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionCollectionQuery;
use Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionQuery;
use Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionSchema;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use Lunar\Models\Collection;

class CollectionsController extends Controller
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
     * @param  CollectionSchema  $schema
     * @param  CollectionCollectionQuery  $request
     * @return Responsable|Response
     */
    // public function index(CollectionSchema $schema, CollectionCollectionQuery $request): Responsable|Response
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
     * @param  CollectionSchema  $schema
     * @param  CollectionQuery  $request
     * @param  Collection  $product
     * @return Responsable|Response
     */
    // public function show(CollectionSchema $schema, CollectionQuery $request, Collection $collection): Responsable|Response
    // {
    //     $model = $schema
    //         ->repository()
    //         ->queryOne($collection)
    //         ->withRequest($request)
    //         ->first();
    //
    //     // do something custom...
    //
    //     return new DataResponse($model);
    // }
}
