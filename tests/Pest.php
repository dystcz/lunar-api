<?php

use Illuminate\Support\Facades\Config;

function serverUrl(string $path = null, bool $full = false): string
{
    $path = implode('/', [Config::get('lunar-api.route_prefix'), 'v1', ltrim($path, '/')]);

    return $path;
}
