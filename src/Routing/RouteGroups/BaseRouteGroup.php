<?php

namespace Dystcz\LunarApi\Routing\RouteGroups;

use Dystcz\LunarApi\Routing\Contracts\RouteGroup;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;

abstract class BaseRouteGroup implements RouteGroup
{
    /** @var string */
    public string $prefix = '';

    /** @var array */
    public array $middleware = [];

    /** @var \Illuminate\Routing\Router */
    protected Router $router;

    /**
     * BaseRouteGroup constructor.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        /** @var Illuminate\Routing\Router */
        $this->router = App::make('router');
    }

    /**
     * Register routes.
     *
     * @param null|string $prefix
     * @param array|string $middleware
     * @return void
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        $this->router->group([
            'prefix' => $this->getPrefix($prefix),
            'middleware' => $this->getMiddleware($middleware),
        ], function () {
            // Add routes here in the class that extends this class
        });
    }

    /**
     * Get prefix for route group.
     *
     * @param string|null $prefix
     * @return ?string
     */
    protected function getPrefix(?string $prefix = null): ?string
    {
        if (! $prefix) {
            return $this->prefix;
        }

        return $prefix;
    }

    /**
     * Get middleware for route group.
     *
     * @param array|string $middleware
     * @return array
     */
    protected function getMiddleware(array|string $middleware = []): array
    {
        if (is_array($middleware) && empty($middleware)) {
            return $this->middleware;
        }

        if (is_string($middleware)) {
            $middleware = [$middleware];
        }

        return $middleware;
    }
}
