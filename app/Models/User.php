<?php

namespace App\Models;

use App\Auth\AuthUser;
use App\Enums\UserRole;
use Framework\Model\Model;
use Framework\Model\TimeStamps;

class User extends Model implements AuthUser
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

    public function can($right): bool
    {
        return UserRole::can($this->user_group, (array) $right);
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
