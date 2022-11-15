<?php

namespace Dystcz\LunarApi\Routing\Contracts;

interface RouteGroup
{
    /**
     * Register routes.
     *
     * @param  null|string  $prefix
     * @param  array|string  $middleware
     * @return void
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void;
}
