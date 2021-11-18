<?php

namespace App\Models;

use App\Enums\UserGroup;
use Framework\Model\Model;
use Framework\Model\TimeStamps;

/**
 * Description of User
 *
 * @author ivan
 */
class User extends Model
{
    use TimeStamps;

    public $id;

    public $name;

    public $username;

    public $password;

    public $email;

    public $last_login;

    public $user_group;

    public $activated_at;

    public $phone_number;

    public function keresztnev()
    {
        return substr($this->name, strpos($this->name, ' '));
    }

    public function isAdmin(): bool
    {
        return $this->hasUserGroup('SUPER_ADMIN');
    }

    public function hasUserGroup(string $group): bool
    {
        return $this->user_group === $group;
    }

    public function can(string $right): bool
    {
        return UserGroup::can($this->user_group, $right);
    }

    public function firstName()
    {
        return substr($this->name, strpos($this->name, ' '));
    }

    public function isActive(): bool
    {
        return $this->activated_at !== '0000-00-00 00:00:00' && !is_null($this->activated_at);
    }
}
