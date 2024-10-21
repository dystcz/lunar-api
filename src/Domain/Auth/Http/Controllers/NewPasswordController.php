<?php

namespace Dystcz\LunarApi\Domain\Auth\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Auth\Actions\CompletePasswordReset;
use Dystcz\LunarApi\Domain\Auth\Actions\ResetUserPassword;
use Dystcz\LunarApi\Domain\Auth\Contracts\NewPasswordController as NewPasswordControllerContract;
use Dystcz\LunarApi\Domain\Auth\JsonApi\V1\NewPasswordRequest;
use Dystcz\LunarApi\Domain\Users\Contracts\User as UserContract;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use LaravelJsonApi\Core\Responses\DataResponse;

class NewPasswordController extends Controller implements NewPasswordControllerContract
{
    /**
     * The guard implementation.
     */
    protected ResetUserPassword $resetPassword;

    protected CompletePasswordReset $completePasswordReset;

    public function __construct(protected Guard $guard)
    {
        $this->resetPassword = App::make(ResetUserPassword::class);

        $this->completePasswordReset = App::make(CompletePasswordReset::class);
    }

    /**
     * Redirect to the new password view.
     */
    public function create(Request $request): RedirectResponse
    {
        return redirect()
            ->away(
                Config::get('app.client.url').
                '/auth/create-new-password?token='.$request->token.
                '&email='.$request->email
            );
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(NewPasswordRequest $request): Responsable
    {
        $data = $request->validated();

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = $this->broker()->reset($data, function (UserContract $user) use ($data) {
            /** @var User $user */
            $this->resetPassword->handle($user, $data);

            $this->completePasswordReset->handle($this->guard, $user);
        });

        return DataResponse::make(null)
            ->withMeta([
                'success' => $status === Password::PASSWORD_RESET,
            ]);
    }

    /**
     * Get the broker to be used during password reset.
     */
    protected function broker(): PasswordBroker
    {
        return Password::broker('users');
    }
}
