<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => Config::get('lunar-api.route_prefix'),
    'middleware' => Config::get('lunar-api.route_middleware'),
], function (Router $router) {
    foreach (Arr::flatten(Arr::pluck(Config::get('lunar-api.domains'), 'route_groups')) as $key => $group) {
        (new $group())();
    }
});
