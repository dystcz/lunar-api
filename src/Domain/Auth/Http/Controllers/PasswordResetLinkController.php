<?php

namespace Dystcz\LunarApi\Domain\Auth\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Auth\Contracts\PasswordResetLinkController as PasswordResetLinkControllerContract;
use Dystcz\LunarApi\Domain\Auth\JsonApi\V1\ForgottenPasswordRequest;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Support\Facades\Password;
use LaravelJsonApi\Core\Responses\DataResponse;

class PasswordResetLinkController extends Controller implements PasswordResetLinkControllerContract
{
    /**
     * Send a reset link to the given user.
     */
    public function forgotPassword(ForgottenPasswordRequest $request): DataResponse
    {
        $data = $request->validated();

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = $this->broker()->sendResetLink($data);

        $success = $status === Password::RESET_LINK_SENT;

        return DataResponse::make(null)
            ->withMeta([
                'message' => __('auth.password_reset.confirmation'),
                'success' => $success,
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
