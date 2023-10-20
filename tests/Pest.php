<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Lunar\Base\BaseModel;

function serverUrl(string $path = null, bool $full = false): string
{
    $path = implode('/', [Config::get('lunar-api.route_prefix'), 'v1', ltrim($path, '/')]);

    return $path;
}

function decodeHashedId(BaseModel $model, mixed $id): mixed
{
    /** @var \Vinkla\Hashids\Facades\Hashids $hashids */
    $hashids = App::get('hashids');

    return $hashids->connection($model->getMorphClass())->decode($id);
}
