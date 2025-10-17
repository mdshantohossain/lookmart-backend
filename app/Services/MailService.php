<?php

namespace App\Services;

use App\Mail\ResetPasswordLinkMail;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendVerificationMail(array $data): void
    {
        Mail::to($data['user']->email)->send(new VerificationMail($data));
    }
    public function sendResetPasswordMail(array $data): void
    {
     Mail::to($data['user']->email)->send(new ResetPasswordLinkMail());
    }
}
