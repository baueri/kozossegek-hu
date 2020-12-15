<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Mail;

use App\Models\GroupView;
use App\Models\User;
use App\Models\UserToken;

/**
 * Description of RegistrationByGroupEmail
 *
 * @author ivan
 */
class RegistrationByGroupEmail extends RegistrationEmail
{
    public function __construct(User $user, UserToken $passwordReset, GroupView $group)
    {
        parent::__construct($user, $passwordReset, 'mail.register_by_group');
        
        $this->with(['group_name_with_city' => "{$group->name}, {$group->city}"]);
    }
}
