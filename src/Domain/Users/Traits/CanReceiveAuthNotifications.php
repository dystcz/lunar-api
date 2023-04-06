<?php

namespace Dystcz\LunarApi\Domain\Users\Traits;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Config;

trait CanReceiveAuthNotifications
{
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $notificationClass = Config::get(
            'lunar-api.auth.notifications.reset_password',
            ResetPassword::class,
        );

        $this->notify(new $notificationClass($token));
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $notificationClass = Config::get(
            'lunar-api.auth.notifications.verify_email',
            VerifyEmail::class,
        );

        $this->notify(new $notificationClass);
    }
}
