<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => Config::get('lunar-api.route_prefix'),
    'middleware' => Config::get('lunar-api.route_middleware'),
], function () {
    $routeGroups = collect(Config::get('lunar-api.domains'))
        ->pluck('route_groups')
        ->filter()
        ->flatten();

    foreach ($routeGroups as $prefix => $group) {
        (new $group())($prefix);
    }
});
