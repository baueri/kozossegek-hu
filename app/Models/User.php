<?php

namespace App\Models;

use App\Auth\AuthUser;
use App\Enums\UserRole;
use App\QueryBuilders\Users;
use Framework\Model\Entity;
use Framework\Model\HasTimestamps;
use Framework\Model\ModelCollection;
use App\QueryBuilders\ChurchGroups;

/**
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $last_login
 * @property UserRole $user_role
 * @property string $activated_at
 * @property string $phone_number
 * @property ModelCollection<ChurchGroup> $groups
 * @property ModelCollection<SocialProfile>|SocialProfile[] $socialProfiles
 * @property int $groups_count
 * @method ChurchGroups groups()
 */
class User extends Entity implements AuthUser
{
    use HasTimestamps;

    protected static ?string $builder = Users::class;

    protected array $casts = [
        'user_role' => UserRole::class
    ];

    public function keresztnev(): string
    {
        return substr($this->name, strpos($this->name, ' '));
    }

    public function isAdmin(): bool
    {
        return $this->user_role === UserRole::SUPER_ADMIN;
    }

    public function can($right): bool
    {
        return $this->user_role->can($right);
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
