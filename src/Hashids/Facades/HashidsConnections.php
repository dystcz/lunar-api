<?php

namespace Dystcz\LunarApi\Hashids\Facades;

use Dystcz\LunarApi\Hashids\Contracts\HashidsConnectionsManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * Class ModelManifest.
 *
 * @method static void registerConnections(Collection $models)
 * @method static \Illuminate\Support\Collection getConnections()
 * @method static ?string getModelConnection(string $model)
 *
 * @see \Lunar\Base\ModelManifest
 */
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
