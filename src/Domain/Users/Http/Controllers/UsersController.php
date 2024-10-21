<?php

namespace Dystcz\LunarApi\Domain\Users\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Users\Contracts\RegistersUser;
use Dystcz\LunarApi\Domain\Users\Contracts\User as UserContract;
use Dystcz\LunarApi\Domain\Users\Contracts\UsersController as UsersControllerContract;
use Dystcz\LunarApi\Domain\Users\Data\UserData;
use Dystcz\LunarApi\Domain\Users\JsonApi\V1\CreateUserRequest;
use Dystcz\LunarApi\Domain\Users\JsonApi\V1\UpdateUserRequest;
use Dystcz\LunarApi\Domain\Users\JsonApi\V1\UserQuery;
use Dystcz\LunarApi\Domain\Users\JsonApi\V1\UserSchema;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Facades\LunarApi;
use Illuminate\Contracts\Auth\Guard;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Store;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;

class UsersController extends Controller implements UsersControllerContract
{
    use FetchRelated;
    use FetchRelationship;
    use Store;
    use Update;

    public function __construct(
        protected RegistersUser $registerUser,
        protected Guard $guard,
    ) {
        $this->middleware('guest:'.LunarApi::getAuthGuard())->only('store');
    }

    /**
     * Create new user.
     */
    public function store(
        UserSchema $schema,
        CreateUserRequest $request,
        UserQuery $query,
    ): DataResponse {
        $this->authorize('create', User::modelClass());

        $user = $this->registerUser->register(new UserData(
            email: $request->validated('email'),
            password: $request->validated('password'),
        ));

        $this->guard->login($user);

        return DataResponse::make($user)
            ->withQueryParameters($query)
            ->didCreate();
    }

    /**
     * Update user.
     */
    public function update(
        UserSchema $schema,
        UpdateUserRequest $request,
        UserQuery $query,
        UserContract $user,
    ): DataResponse {
        $this->authorize('update', $user);

        $model = $schema
            ->repository()
            ->update($user)
            ->withRequest($query)
            ->store($request->validated());

        return DataResponse::make($model)
            ->withQueryParameters($query)
            ->didntCreate();
    }
}
