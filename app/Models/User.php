<?php

namespace App\Models;

use App\Auth\AuthUser;
use App\Models\Traits\UserTrait;
use Framework\Model\Entity;
use Framework\Model\TimeStamps;

/**
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $last_login
 * @property string $user_group
 * @property string $activated_at
 * @property string $phone_number
 */
class User extends Entity implements AuthUser
{
    use TimeStamps;
    use UserTrait;
}
