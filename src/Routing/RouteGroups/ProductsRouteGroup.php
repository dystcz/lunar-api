<?php

namespace Dystcz\GetcandyApi\Routing\RouteGroups;

use Dystcz\GetcandyApi\Domain\Products\Http\Controllers\ProductsController;
use Dystcz\GetcandyApi\Routing\Contracts\RouteGroup;
use Illuminate\Support\Facades\Route;

class ProductsRouteGroup extends BaseRouteGroup implements RouteGroup
{
    /** @var string */
    public string $prefix = 'products';

    /** @var array */
    public array $middleware = [];

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
            'prefix' => $this->getPrefix($prefix),
            'middleware' => $this->getMiddleware($middleware),
        ], function () {
            Route::get('/', [ProductsController::class, 'index']);
        });
    }
}
