<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Routing;

use Dystcz\LunarApi\Domain\Products\Http\Controllers\ProductsController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class ProductRouteGroup extends RouteGroup implements RouteGroupContract
{
    public string $prefix = 'products';

    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), ProductsController::class)
                    ->relationships(function ($relationships) {
                        $relationships
                            ->hasMany('associations')
                            // ->only('related', 'show')
                            ->readOnly();

                        $relationships
                            ->hasMany('inverse_associations')
                            // ->only('related', 'show')
                            ->readOnly();

                        $relationships
                            ->hasOne('brand')
                            // ->only('related', 'show')
                            ->readOnly();

                        $relationships
                            ->hasOne('cheapest_variant')
                            // ->only('related', 'show')
                            ->readOnly();

                        $relationships
                            ->hasOne('default_url')
                            // ->only('related', 'show')
                            ->readOnly();

                        $relationships
                            ->hasOne('lowest_price')
                            // ->only('related', 'show')
                            ->readOnly();

                        $relationships
                            ->hasMany('prices')
                            // ->only('related', 'show')
                            ->readOnly();

                        $relationships
                            ->hasMany('variants')
                            // ->only('related', 'show')
                            ->readOnly();
                    })
                    ->only('index', 'show')
                    ->readOnly();
            });
    }
}
