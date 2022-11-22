<?php

namespace Dystcz\LunarApi\Domain\Collections\Http\Routing;

use Dystcz\LunarApi\Domain\Collections\Http\Controllers\CollectionsController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

class CollectionsRouteGroup extends RouteGroup implements RouteGroupContract
{
    /** @var string */
    public string $prefix = 'collections';

    /** @var array */
    public array $middleware = [];

    /**
     * Register routes.
     *
     * @param  null|string  $prefix
     * @param  array|string  $middleware
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
            });
        });
    }
}
