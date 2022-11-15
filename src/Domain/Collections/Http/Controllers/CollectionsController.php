<?php

namespace Dystcz\LunarApi\Domain\Collections\Http\Controllers;

use Dystcz\LunarApi\Domain\Api\Http\Api\Responses\ErrorNotFoundResponse;
use Dystcz\LunarApi\Domain\Collections\Http\Api\Responses\CollectionShowResponse;
use Dystcz\LunarApi\Domain\Collections\Http\Api\Responses\CollectionsIndexResponse;
use Dystcz\LunarApi\Domain\Collections\Http\Resources\CollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Lunar\Models\Collection;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class CollectionsController extends Controller
{
    /**
     * List collections.
     *
     * Lists all collections.
     *
     * @param  Request  $request
     * @return JsonResource
     */
    #[OpenApi\Operation(tags: ['collections'])]
    #[OpenApi\Response(factory: CollectionsIndexResponse::class, statusCode: 200)]
    public function index(Request $request): JsonResource
    {
        $collections = Collection::query()
            ->with([
                'thumbnail',
            ])
            ->get();

        return CollectionResource::collection($collections);
    }

    /**
     * Show collection.
     *
     * Show collection by slug.
     *
     * @param  Request  $request
     * @param  string  $slug
     * @return JsonResource
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    #[OpenApi\Operation(tags: ['collections'])]
    #[OpenApi\Response(factory: CollectionShowResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: ErrorNotFoundResponse::class, statusCode: 404)]
    public function show(Request $request, string $slug): JsonResource
    {
        $collection = Collection::query()
            ->whereHas(
                'url',
                fn ($query) => $query->where('slug', $slug)->whereHas(
                    'language',
                    fn ($query) => $query->where('code', App::getLocale())
                )
            )
            ->with([
                'thumbnail',
            ])
            ->firstOrFail();

        return new CollectionResource($collection);
    }
}
