<?php

namespace App\Mail;

use App\Models\User;
use App\Models\UserToken;
use Framework\Mail\Mailable;

class ResetPasswordEmail extends Mailable
{
    public function __construct(User $user, UserToken $user_token)
    {
        $this->subject(site_name() . ' - elfelejtett jelszó')
            ->view('email_templates:forgot-password')
            ->with(['user_token' => $user_token->getUrl(), 'name' => $user->name]);
    }
}
