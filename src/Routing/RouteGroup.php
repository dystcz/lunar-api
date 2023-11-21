<?php

namespace Dystcz\LunarApi\Routing;

use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

abstract class RouteGroup implements RouteGroupContract
{
    public string $prefix = '';

    public array $middleware = [];

    protected Router $router;

    /**
     * RouteGroup constructor.
     *
     * @return void
     *
     * @throws BindingResolutionException
     */
    public function __construct(string $prefix = '', array|string $middleware = [])
    {
        $this->prefix = $prefix;

        $this->middleware = Arr::wrap($middleware);

        /** @var Router */
        $this->router = App::make('router');
    }

    /**
     * Static constructor.
     */
    public static function make(string $prefix = '', array|string $middleware = []): self
    {
        return new static($prefix, $middleware);
    }

    /**
     * Register routes.
     */
    abstract public function routes(): void;

    /**
     * Get prefix for route group.
     *
     * @return ?string
     */
    protected function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * Get middleware for route group.
     */
    protected function getMiddleware(): array
    {
        return $this->middleware;
    }
}
