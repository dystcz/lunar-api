<?php

namespace Dystcz\LunarApi\Domain\Countries\Http\Routing;

use Dystcz\LunarApi\Domain\Countries\Contracts\CountriesController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class CountryRouteGroup extends RouteGroup
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server->resource($this->getPrefix(), CountriesController::class)
                    ->only('index')
                    ->readOnly();
            });
    }
}
