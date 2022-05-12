<?php

namespace Dystcz\GetcandyApi\Routing\RouteGroups;

use Dystcz\GetcandyApi\Domain\Collections\Http\Controllers\CollectionProductsController;
use Dystcz\GetcandyApi\Domain\Collections\Http\Controllers\CollectionsController;
use Dystcz\GetcandyApi\Routing\Contracts\RouteGroup;
use Illuminate\Support\Facades\Route;

class CollectionsRouteGroup extends BaseRouteGroup implements RouteGroup
{
    /** @var string */
    public string $prefix = 'collections';

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
            Route::get('/', [CollectionsController::class, 'index']);
            Route::group(['prefix' => '{slug}'], function () {
                Route::get('/', [CollectionsController::class, 'show']);
                Route::get('/products', [CollectionProductsController::class, 'show']);
            });
        });
    }
}
