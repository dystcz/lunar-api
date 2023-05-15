<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Http\Routing;

use Dystcz\LunarApi\Domain\PaymentOptions\Http\Controllers\PaymentOptionsController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class PaymentOptionRouteGroup extends RouteGroup
{
    public string $prefix = 'payment-options';

    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), PaymentOptionsController::class)
                    ->only('index');
            });
    }
}
