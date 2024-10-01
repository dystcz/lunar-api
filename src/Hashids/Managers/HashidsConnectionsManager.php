<?php

namespace Dystcz\LunarApi\Hashids\Managers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class HashidsConnectionsManager
{
    /**
     * Register hashids connections.
     */
    public function registerConnections(): void
    {
        Config::set(
            'lunar-api.hashids.connections',
            $this->getConnectionsFromModels()->toArray(),
        );
    }

    /**
     * Get all registered hashids connections.
     */
    public function getConnections(): array
    {
        return Config::get('lunar-api.hashids.connections', []);
    }

    /**
     * Get connection for model.
     *
     * @param  class-string  $model
     */
    public function getModelConnection(string $model): ?string
    {
        if (array_key_exists($model, $this->getConnections())) {
            return $model;
        }

        return null;
    }

    /**
     * Get connections from models.
     */
    protected function getConnectionsFromModels(): Collection
    {
        return Collection::make(Relation::morphMap())->mapWithKeys(function (string $modelClass, $connectionKey) {
            return [
                $connectionKey => [
                    'salt' => $modelClass.Config::get('app.key'),
                    'length' => Config::get('lunar-api.hashids.default_length', 16),
                    'alphabet' => Config::get(
                        'lunar-api.hashids.default_alphabet',
                        'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
                    ),
                ],
            ];
        });
    }
}
