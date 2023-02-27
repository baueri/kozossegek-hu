<?php

namespace App\Models;

use App\Auth\AuthUser;
use App\Enums\UserRole;
use Framework\Model\Entity;
use Framework\Model\HasTimestamps;

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
    use HasTimestamps;

    public function keresztnev(): string
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
        return $this->user_group === UserRole::SUPER_ADMIN || $this->hasRight($right);
    }

    public function hasRight($right): bool
    {
        return UserRole::can($this->user_group, ($right));
    }

    public function firstName(): string
    {
        return substr($this->name, strpos($this->name, ' '));
    }

    public function isActive(): bool
    {
        return $this->activated_at !== '0000-00-00 00:00:00' && !is_null($this->activated_at);
    }
}
