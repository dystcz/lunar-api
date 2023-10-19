<?php

namespace Dystcz\LunarApi\Hashids\Managers;

use Illuminate\Support\Facades\Config;
use Lunar\Facades\ModelManifest;

class HashidsConnectionsManager
{
    /**
     * Register hashids connections.
     */
    public function registerConnections(): void
    {
        Config::set(
            'hashids.connections',
            $this->getConnectionsFromModels(),
        );
    }

    /**
     * Get all registered hashids connections.
     */
    public function getConnections(): array
    {
        return Config::get('hashids.connections', []);
    }

    /**
     * Get connections from models.
     */
    protected function getConnectionsFromModels(): array
    {
        $models = ModelManifest::getRegisteredModels();

        $connections = $models->mapWithKeys(function (string $targetModelClass, string $baseModelClass) {
            return [
                $baseModelClass => [
                    'salt' => $baseModelClass.Config::get('app.key'),
                    'length' => Config::get('hashids.default_length', 16),
                    'alphabet' => Config::get(
                        'hashids.default_alphabet',
                        'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
                    ),
                ],
            ];
        });

        return $connections->toArray();
    }
}
