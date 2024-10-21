<?php

namespace Dystcz\LunarApi\Domain\Auth\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Auth\Contracts\RegisterUserWithoutPasswordController as RegisterUserWithoutPasswordControllerContract;
use Dystcz\LunarApi\Domain\Users\Contracts\RegistersUser;
use Dystcz\LunarApi\Domain\Users\Data\UserData;
use Dystcz\LunarApi\Domain\Users\JsonApi\V1\UserWithoutPasswordRequest;
use LaravelJsonApi\Core\Responses\DataResponse;

class RegisterUserWithoutPasswordController extends Controller implements RegisterUserWithoutPasswordControllerContract
{
    public function __construct(
        protected RegistersUser $registerUser,
    ) {}

    /**
     * Register a user without a password.
     */
    public function registerWithoutPassword(
        UserWithoutPasswordRequest $request,
    ): DataResponse {
        $user = $this->registerUser->register(
            new UserData(
                name: $request->validated('name'),
                email: $request->validated('email')
            )
        );

        return DataResponse::make($user)
            ->didCreate();
    }
}
