<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Http\Routing;

use Dystcz\LunarApi\Domain\ProductVariants\Contracts\ProductVariantsController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class ProductVariantRouteGroup extends RouteGroup implements RouteGroupContract
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server
                    ->resource($this->getPrefix(), ProductVariantsController::class)
                    ->relationships(function (Relationships $relationships) {
                        $relationships->hasOne('default-url')->readOnly();
                        $relationships->hasMany('images')->readOnly();
                        $relationships->hasMany('other-product-variants')->readOnly();
                        $relationships->hasMany('prices')->readOnly();
                        $relationships->hasOne('lowest-price')->readOnly();
                        $relationships->hasOne('highest-price')->readOnly();
                        $relationships->hasMany('product')->readOnly();
                        $relationships->hasOne('thumbnail')->readOnly();
                        $relationships->hasMany('urls')->readOnly();
                    })
                    ->only('index', 'show')
                    ->readOnly();
            });
    }
}
