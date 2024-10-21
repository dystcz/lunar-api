<?php

namespace Dystcz\LunarApi\Domain\Auth\Http\Routing;

use Dystcz\LunarApi\Domain\Auth\Contracts\AuthController;
use Dystcz\LunarApi\Domain\Auth\Contracts\PasswordResetLinkController;
use Dystcz\LunarApi\Domain\Auth\Contracts\RegisterUserWithoutPasswordController;
use Dystcz\LunarApi\Domain\Auth\Http\Controllers\NewPasswordController;
use Dystcz\LunarApi\Facades\LunarApi;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ActionRegistrar;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class AuthRouteGroup extends RouteGroup implements RouteGroupContract
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
                    ->resource('auth', AuthController::class)
                    ->only('')
                    ->actions('-actions', function (ActionRegistrar $actions) {
                        $actions
                            ->get('me')
                            ->name('auth.me');
                        $actions
                            ->post('logout')
                            ->name('auth.logout');
                        $actions
                            ->post('login')
                            ->name('auth.login')
                            ->withoutMiddleware('auth:'.LunarApi::getAuthGuard());
                    })->middleware('auth:'.LunarApi::getAuthGuard());

                $server->resource('auth', RegisterUserWithoutPasswordController::class)->only('')
                    ->actions('-actions', function (ActionRegistrar $actions) {
                        $actions->post('register-without-password');
                    })
                    ->middleware('guest:'.LunarApi::getAuthGuard());

                $server->resource('auth', PasswordResetLinkController::class)->only('')
                    ->actions('-actions', function (ActionRegistrar $actions) {
                        $actions->post('forgot-password');
                    })
                    ->middleware('guest:'.LunarApi::getAuthGuard());

                $server->resource('auth', NewPasswordController::class)->only('')
                    ->actions('-actions', function (ActionRegistrar $actions) {
                        $actions
                            ->post('reset-password')
                            ->name('users.passwords.reset');
                        $actions
                            ->get('reset-password/{token}', 'create')
                            ->name('users.passwords.set-new-password');
                    })
                    ->middleware('guest:'.LunarApi::getAuthGuard());
            });
    }
}
