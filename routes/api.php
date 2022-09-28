<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => Config::get('lunar-api.route_prefix'),
    'middleware' => Config::get('lunar-api.route_middleware'),
], function (Router $router) {
    foreach (Config::get('lunar-api.route_groups') as $key => $group) {
        (new $group())->routes();
    }
});
