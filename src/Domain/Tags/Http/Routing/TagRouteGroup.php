<?php

namespace Dystcz\LunarApi\Domain\Tags\Http\Routing;

use Dystcz\LunarApi\Domain\Tags\Contracts\TagsController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class TagRouteGroup extends RouteGroup implements RouteGroupContract
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server->resource($this->getPrefix(), TagsController::class)
                    ->relationships(function (Relationships $relationships) {
                        // $relationships->hasMany('taggables')->readOnly();
                    })
                    ->only('index', 'show')
                    ->readOnly();
            });
    }
}
