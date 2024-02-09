<?php

namespace Dystcz\LunarApi\Domain\Collections\Http\Routing;

use Dystcz\LunarApi\Domain\Collections\Http\Controllers\CollectionsController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class CollectionRouteGroup extends RouteGroup implements RouteGroupContract
{
    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        $this->router->group([
            // 'prefix' => $this->getPrefix($prefix),
            'middleware' => $this->getMiddleware($middleware),
        ], function () {
            JsonApiRoute::server('v1')->prefix('v1')->resources(function ($server) {
                $server->resource($this->getPrefix(), CollectionsController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasMany('products')->readOnly();
                        $relationships->hasOne('default_url')->readOnly();
                    })
                    ->only('index', 'show')
                    ->readOnly();
            });
        });
    }
}
