<?php

namespace Dystcz\LunarApi\Domain\Addresses\Http\Routing;

use Dystcz\LunarApi\Domain\Addresses\Http\Controllers\AddressesController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class AddressRouteGroup extends RouteGroup
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->middleware('auth')
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), AddressesController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasOne('country')->readOnly();
                        $relationships->hasOne('customer')->readOnly();
                    });
            });
    }
}
