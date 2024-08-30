<?php

namespace Dystcz\LunarApi\Hashids\Facades;

use Dystcz\LunarApi\Hashids\Contracts\HashidsConnectionsManager;
use Illuminate\Support\Facades\Facade;

class HashidsConnections extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return HashidsConnectionsManager::class;
    }
}
