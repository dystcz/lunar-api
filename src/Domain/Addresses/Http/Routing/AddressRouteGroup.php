<?php

namespace Dystcz\LunarApi\Domain\Addresses\Http\Routing;

use Dystcz\LunarApi\Domain\Addresses\Http\Controllers\AddressesController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class AddressRouteGroup extends RouteGroup
{
    /** @var string */
    public string $prefix = 'addresses';

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
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), AddressesController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasOne('country')->readOnly();
                        $relationships->hasOne('customer')->readOnly();
                    });
            });
    }
}
