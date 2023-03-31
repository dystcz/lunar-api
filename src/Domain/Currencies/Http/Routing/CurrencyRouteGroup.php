<?php

namespace Dystcz\LunarApi\Domain\Currencies\Http\Routing;

use Dystcz\LunarApi\Domain\Currencies\Http\Controllers\CurrenciesController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class CurrencyRouteGroup extends RouteGroup
{
    /** @var string */
    public string $prefix = 'currencies';

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
                $server->resource($this->getPrefix(), CurrenciesController::class)
                    ->only('index');
            });
    }
}
