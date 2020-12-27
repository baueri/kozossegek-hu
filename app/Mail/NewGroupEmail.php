<?php

namespace App\Mail;

use App\Models\User;
use App\Models\UserToken;
use Framework\Mail\Mailable;

class NewGroupEmail extends Mailable
{
    protected string $view = 'mail.created_group_email.created_group';

    public string $subject = 'kozossegek.hu - Új csoport';

    public function __construct(User $user)
    {
        $this->withUser($user);
    }

    private function withUser(User $user)
    {
        $this->with(['user_name' => $user->name]);
    }

    private function withToken(UserToken $token)
    {
        return $this->with(['token_url' => $token->getUrl()]);
    }

    public function withNewUserMessage(UserToken $token)
    {
        $this->withToken($token);
        return $this->view('mail.created_group_email.created_group_with_new_user');
    }
}
