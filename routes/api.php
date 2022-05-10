<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => Config::get('getcandy-api.route_prefix')], function () {
    Route::group([
        'prefix' => 'products',
    ], __DIR__.'/includes/products.php');
});
