<?php

namespace Dystcz\LunarApi\Hashids\Contracts;

interface HashidsConnectionsManager
{
    /**
     * Register hashids connections.
     */
    public function registerConnections(): void;

    /**
     * Get all registered hashids connections.
     */
    public function getConnections(): array;
}
