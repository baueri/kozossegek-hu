<?php

declare(strict_types=1);

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum UserRole
{
    use EnumTrait;
    use HasTranslation;

    case SUPER_ADMIN;

    case SPIRITUAL_MOVEMENT_LEADER;

    case GROUP_LEADER;

    protected const PERMISSIONS = [
        self::SUPER_ADMIN->name => [
            Permission::FULL_ACCESS,
            Permission::ACCESS_BACKEND,
            Permission::MANAGE_SPIRITUAL_MOVEMENT,
            Permission::MANAGE_SPIRITUAL_MOVEMENT_GROUPS
        ],
        self::SPIRITUAL_MOVEMENT_LEADER->name => [
            Permission::ACCESS_BACKEND,
            Permission::MANAGE_SPIRITUAL_MOVEMENT,
            Permission::MANAGE_SPIRITUAL_MOVEMENT_GROUPS
        ]
    ];

    public function can(Permission $permission): bool
    {
        if ($this === self::SUPER_ADMIN) {
            return true;
        }

        return in_array($permission, self::PERMISSIONS[$this->name], true);
    }
}