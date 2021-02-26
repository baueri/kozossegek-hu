<?php

namespace App\Mail;

use App\Models\GroupView;
use App\Models\User;
use App\Models\UserToken;

class RegistrationByGroupEmailForFirstUsers extends RegistrationEmail
{
    public function __construct(User $user, string $password, UserToken $userToken, GroupView $group)
    {
        parent::__construct($user, $userToken, 'email_templates:register_by_group');

        $this->with([
            'group_name_with_city' => "{$group->name}, {$group->city}",
            'password' => $password,
            'email_address' => $user->email
        ]);
    }
}
