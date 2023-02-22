<?php

namespace Dystcz\LunarApi\Domain\Countries\Http\Routing;

use Dystcz\LunarApi\Domain\Carts\Http\Controllers\CheckoutCartController;
use Dystcz\LunarApi\Domain\Carts\Http\Controllers\ClearUserCartController;
use Dystcz\LunarApi\Domain\Carts\Http\Controllers\ReadUserCartController;
use Dystcz\LunarApi\Domain\Countries\Http\Controllers\CountriesController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class CountryRouteGroup extends RouteGroup
{
    /** @var string */
    public string $prefix = 'countries';

    /** @var array */
    public array $middleware = [];

    /**
     * Register routes.
     *
     * @param null|string  $prefix
     * @param array|string $middleware
     * @return void
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server->resource($this->getPrefix(), CountriesController::class)
                    ->only('index');
            });
    }
}
