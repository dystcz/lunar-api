<?php

namespace Dystcz\LunarApi\Routing\Contracts;

interface RouteGroup
{
    /**
     * Register routes.
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void;
}
