<?php

namespace Dystcz\LunarApi\Domain\Users\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Users\Contracts\ChangePasswordController as ChangePasswordControllerContract;
use Dystcz\LunarApi\Domain\Users\Contracts\User as UserContract;
use Dystcz\LunarApi\Domain\Users\JsonApi\V1\ChangePasswordRequest;
use Dystcz\LunarApi\Domain\Users\JsonApi\V1\UserSchema;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use LaravelJsonApi\Core\Responses\DataResponse;

class ChangePasswordController extends Controller implements ChangePasswordControllerContract
{
    public function update(
        UserSchema $schema,
        ChangePasswordRequest $request,
        UserContract $user,
    ): DataResponse {
        /** @var User $user */
        $this->authorize('update', $user);

        $user->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        $model = $schema
            ->repository()
            ->queryOne($user)
            ->withRequest($request)
            ->first();

        return DataResponse::make($model)
            ->didntCreate();
    }
}
