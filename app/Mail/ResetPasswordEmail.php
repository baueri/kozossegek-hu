<?php


namespace App\Mail;


use App\Models\User;
use App\Models\UserToken;
use Framework\Mail\Mailable;

class ResetPasswordEmail extends Mailable
{
    public function __construct(User $user, UserToken $user_token)
    {
        $this->subject('kozossegek.hu - elfelejtett jelszó')
            ->view('mail.forgot-password')
            ->with(compact('user_token', 'user'));
    }
}