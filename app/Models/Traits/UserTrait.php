<?php

namespace App\Models\Traits;

use App\Enums\UserRole;

trait UserTrait
{
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
