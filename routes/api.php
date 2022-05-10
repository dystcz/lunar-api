<?php

use Dystcz\GetcandyApi\Routing\RouteGroups\ProductsRouteGroup;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => Config::get('getcandy-api.route_prefix'),
    'middleware' => Config::get('getcandy-api.route_middleware'),
], function (Router $router) {
    (new ProductsRouteGroup)->routes();
});
