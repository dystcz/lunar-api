<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\Http\Routing;

use Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers\AttachShippingOptionController;
use Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers\CartAddressesController;
use Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers\ContinuousUpdateCartAddressController;
use Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers\DetachShippingOptionController;
use Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers\UpdateCartAddressCountryController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class CartAddressRouteGroup extends RouteGroup
{
    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server->resource($this->getPrefix(), CartAddressesController::class)
                    ->only('store', 'update');

                $server->resource($this->getPrefix(), AttachShippingOptionController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->patch('attach-shipping-option');
                    });

                $server->resource($this->getPrefix(), DetachShippingOptionController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->delete('detach-shipping-option');
                    });

                $server->resource($this->getPrefix(), UpdateCartAddressCountryController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->patch('update-country');
                    });

                $server->resource($this->getPrefix(), ContinuousUpdateCartAddressController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->patch('continuous-update');
                    });
            });
    }
}
