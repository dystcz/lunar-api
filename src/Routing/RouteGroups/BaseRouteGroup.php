<?php

namespace Dystcz\GetcandyApi\Routing\RouteGroups;

use Dystcz\GetcandyApi\Routing\Contracts\RouteGroup;
use Illuminate\Container\Container;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;

class BaseRouteGroup implements RouteGroup
{
    protected Router $router;

    public function __construct()
    {
        $this->router = Container::getInstance()->make('router');
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
            return Config::get('getcandy-api.route_groups.products.prefix');
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
        if (! $middleware) {
            return Config::get('getcandy-api.route_groups.products.middleware');
        }

        if (is_string($middleware)) {
            $middleware = [$middleware];
        }

        return $middleware;
    }

    /**
     * Call static methods on a new instance.
     *
     * @param string $method
     * @param mixed $args
     * @return mixed
     */
    public static function __callStatic(string $method, $args)
    {
        if (! in_array($method, ['routes'])) {
            throw new \BadMethodCallException("Method {$method} does not exist.");
        }

        $instance = new self;

        return $instance->$method(...$args);
    }
}
