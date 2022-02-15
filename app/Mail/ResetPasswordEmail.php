<?php


namespace App\Mail;


use App\Models\UserLegacy;
use App\Models\UserToken;
use Framework\Mail\Mailable;

class ResetPasswordEmail extends Mailable
{
    public function __construct(UserLegacy $user, UserToken $user_token)
    {
        $this->subject('kozossegek.hu - elfelejtett jelszó')
            ->view('email_templates:forgot-password')
            ->with(['user_token' => $user_token->getUrl(), 'name' => $user->name]);
    }
}
