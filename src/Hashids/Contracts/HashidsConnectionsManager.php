<?php

namespace Dystcz\LunarApi\Hashids\Contracts;

use Illuminate\Support\Collection;

interface HashidsConnectionsManager
{
    /**
     * Register hashids connections.
     */
    public function registerConnections(): void;

    /**
     * Get all registered hashids connections.
     */
    public function getConnections(): Collection
}
