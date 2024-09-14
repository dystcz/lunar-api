<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Routing;

use Dystcz\LunarApi\Domain\Products\Contracts\ProductsController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class ProductRouteGroup extends RouteGroup implements RouteGroupContract
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server->resource($this->getPrefix(), ProductsController::class)
                    ->relationships(function (Relationships $relationships) {
                        $relationships->hasMany('product-associations')->readOnly();
                        $relationships->hasOne('brand')->readOnly();
                        $relationships->hasOne('cheapest-variant')->readOnly();
                        $relationships->hasOne('most-expensive-variant')->readOnly();
                        $relationships->hasMany('collections')->readOnly();
                        $relationships->hasOne('default-url')->readOnly();
                        $relationships->hasOne('images')->readOnly();
                        $relationships->hasMany('inverse-associations')->readOnly();
                        $relationships->hasOne('lowest-price')->readOnly();
                        $relationships->hasOne('highest-price')->readOnly();
                        $relationships->hasMany('prices')->readOnly();
                        $relationships->hasMany('tags')->readOnly();
                        $relationships->hasOne('thumbnail')->readOnly();
                        $relationships->hasMany('urls')->readOnly();
                        $relationships->hasMany('product-variants')->readOnly();
                    })
                    ->only('index', 'show')
                    ->readOnly();
            });
    }
}
