<?php

namespace App\Models;

use App\Auth\AuthUser;
use App\Models\Traits\UserTrait;
use Framework\Model\Model;
use Framework\Model\TimeStamps;

class UserLegacy extends Model implements AuthUser
{
    use TimeStamps;
    use UserTrait;

    public $id;

    public $name;

    public $username;

    public $password;

    public $email;

    public $last_login;

    public $user_group;

    public $activated_at;

    public $phone_number;
}
