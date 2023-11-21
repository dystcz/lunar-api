<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Routing;

use Dystcz\LunarApi\Domain\Products\Http\Controllers\ProductsController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class ProductRouteGroup extends RouteGroup implements RouteGroupContract
{
    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server->resource($this->getPrefix(), ProductsController::class)
                    ->relationships(function (Relationships $relationships) {
                        $relationships
                            ->hasMany('associations')
                            ->readOnly();

                        $relationships
                            ->hasMany('inverse_associations')
                            ->readOnly();

                        $relationships
                            ->hasOne('brand')
                            ->readOnly();

                        $relationships
                            ->hasOne('cheapest_variant')
                            ->readOnly();

                        $relationships
                            ->hasOne('default_url')
                            ->readOnly();

                        $relationships
                            ->hasOne('lowest_price')
                            ->readOnly();

                        $relationships
                            ->hasMany('prices')
                            ->readOnly();

                        $relationships
                            ->hasMany('variants')
                            ->readOnly();

                        // TODO: Introduce route manifest
                        if (class_exists('Dystcz\LunarApiReviews\LunarReviewsServiceProvider')) {
                            $relationships->hasMany('reviews')->only('index')->readOnly();
                        }
                    })
                    ->only('index', 'show')
                    ->readOnly();
            });
    }
}
