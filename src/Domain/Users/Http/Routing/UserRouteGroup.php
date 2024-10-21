<?php

namespace Dystcz\LunarApi\Domain\Users\Http\Routing;

use Dystcz\LunarApi\Domain\Users\Contracts\ChangePasswordController;
use Dystcz\LunarApi\Domain\Users\Contracts\UserOrdersController;
use Dystcz\LunarApi\Domain\Users\Contracts\UsersController;
use Dystcz\LunarApi\Facades\LunarApi;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ActionRegistrar;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class UserRouteGroup extends RouteGroup implements RouteGroupContract
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $authGuard = LunarApi::getAuthGuard();

                $server
                    ->resource('users', UsersController::class)
                    ->relationships(function (Relationships $relationships) {
                        $relationships->hasMany('customers')->readOnly();
                    })
                    ->only('store', 'update');

                $server
                    ->resource('users', ChangePasswordController::class)
                    ->only('')
                    ->actions('-actions', function (ActionRegistrar $actions) {
                        $actions
                            ->withId()
                            ->patch('change-password', 'update')
                            ->name('users.change-password');
                    })
                    ->middleware('auth:'.LunarApi::getAuthGuard());

                /**
                 * Me
                 */
                $server->resource('users', UserOrdersController::class)->only('')
                    ->actions('-actions/me', function (ActionRegistrar $actions) {
                        $actions->get('orders', 'index')->name('my-orders');
                    })
                    ->middleware('auth:'.LunarApi::getAuthGuard());

            });
    }
}
