<?php


namespace App\Mail;


use App\Models\UserToken;
use App\Models\User;
use Framework\Mail\Mailable;

class RegistrationEmail extends Mailable
{
    public function __construct(User $user, UserToken $passwordReset, string $view = 'mail.register')
    {
        $this->subject('kozossegek.hu - Sikeres regisztráció')
            ->with(['user' => $user, 'password_reset' => $passwordReset])
            ->view($view);
    }
}