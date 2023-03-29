<?php

namespace Dystcz\LunarApi\Domain\Users\Http\Routing;

use Dystcz\LunarApi\Domain\Users\Http\Controllers\UsersController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class UserRouteGroup extends RouteGroup
{
    public string $prefix = 'users';

    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), UsersController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasMany('customers')->readOnly();
                    })
                    ->only('');
            });
    }
}
