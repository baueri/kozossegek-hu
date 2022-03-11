<?php

namespace App\Mail;

use App\Models\User;
use App\Models\UserToken;
use Framework\Mail\Mailable;

class RegistrationEmail extends Mailable
{
    public function __construct(User $user, UserToken $userToken, string $view = 'email_templates:register')
    {
        $this->subject('kozossegek.hu - Sikeres regisztráció')
            ->with(['name' => $user->name, 'token_url' => $userToken->getUrl()])
            ->view($view);
    }
}
