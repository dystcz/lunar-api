<?php

namespace Dystcz\GetcandyApi\Routing\RouteGroups;

use Dystcz\GetcandyApi\Domain\Products\Http\Controllers\ProductsController;
use Dystcz\GetcandyApi\Routing\Contracts\RouteGroup;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class ProductsRouteGroup extends BaseRouteGroup implements RouteGroup
{
    /**
     * Register routes.
     *
     * @param null|string $prefix
     * @param array|string $middleware
     * @return void
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        $this->router->group([
            'prefix' => $prefix ?? Config::get('getcandy-api.route_groups.products.prefix'),
            'middleware' => $middleware ?? Config::get('getcandy-api.route_groups.products.middleware'),
        ], function () {
            Route::get('/', ProductsController::class);
        });
    }
}
