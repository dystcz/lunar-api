<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Routing;

use Dystcz\LunarApi\Domain\Products\Http\Controllers\ProductsController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class ProductRouteGroup extends RouteGroup implements RouteGroupContract
{
    /** @var string */
    public string $prefix = 'products';

    /** @var array */
    public array $middleware = [];

    /**
     * Register routes.
     *
     * @param  null|string  $prefix
     * @param  array|string  $middleware
     * @return void
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), ProductsController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasMany('associations')->readOnly();
                        $relationships->hasOne('brand')->readOnly();
                        $relationships->hasOne('cheapest_variant')->readOnly();
                        $relationships->hasOne('default_url')->readOnly();
                        $relationships->hasOne('lowest_price')->readOnly();
                        $relationships->hasMany('prices')->readOnly();
                        $relationships->hasMany('variants')->readOnly();
                    })
                    ->only('index', 'show')
                    ->readOnly();
            });
    }
}
