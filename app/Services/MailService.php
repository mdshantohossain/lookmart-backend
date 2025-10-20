<?php

namespace App\Services;

use App\Mail\OrderSuccessMail;
use App\Mail\ResetPasswordMail;
use App\Mail\VerificationMail;
use App\Mail\WelcomeMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class MailService
{
    /**
     * welcome email to user after verification
     *
     * @param array $user
     * @return void
     */
    public function welcomeMail(array $user): void
    {
        Mail::to($user['email'])->send(new WelcomeMail($user['name']));
    }

    /**
     * order successful to orderer user to inform with order
     *
     * @param User $user
     * @param Order $order
     * @return void
     */
    public function orderSuccessMail(User $user, Order $order): void
    {
        Mail::to($user->email)->send(new OrderSuccessMail($user->name, $order));
    }

    /**
     * verification email after registration
     *
     * @param User $user
     * @param string $verificationUrl
     * @return void
     */
    public function verificationMail(User $user, string $verificationUrl): void
    {
        Mail::to($user['user']->email)->send(new VerificationMail($user, $verificationUrl));
    }

    /**
     * reset password link to requested user
     *
     * @param array $user
     * @return void
     */
    public function resetPasswordMail(array $user): void
    {
     Mail::to($user['user']->email)->send(new ResetPasswordMail($user));
    }
}
