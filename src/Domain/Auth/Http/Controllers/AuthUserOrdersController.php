<?php

namespace Dystcz\LunarApi\Domain\Auth\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Auth\Contracts\AuthUserOrdersController as AuthUserOrdersControllerContract;
use Dystcz\LunarApi\Domain\Users\JsonApi\V1\UserSchema;
use Dystcz\LunarApi\Domain\Users\Models\User;
use LaravelJsonApi\Core\Responses\RelatedResponse;
use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;

class AuthUserOrdersController extends Controller implements AuthUserOrdersControllerContract
{
    /**
     * Get logged in User's Orders.
     */
    public function index(UserSchema $schema, ResourceQuery $request): RelatedResponse
    {
        /** @var User $user */
        $user = $request->user();

        $orders = $schema
            ->repository()
            ->queryToMany($user, 'orders')
            ->withRequest($request)
            ->getOrPaginate($request->page());

        return RelatedResponse::make($user, 'orders', $orders)
            ->withQueryParameters($request);
    }
}
