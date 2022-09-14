<?php

namespace App\Enums;

use Framework\Support\Arr;
use Framework\Support\Enum;

class UserRole extends Enum
{
    public const SUPER_ADMIN = 'SUPER_ADMIN';
    public const SPIRITUAL_MOVEMENT_LEADER = 'SPIRITUAL_MOVEMENT_LEADER';
    public const GROUP_LEADER = 'GROUP_LEADER';

    private static array $roles = [
        self::SUPER_ADMIN => [
            UserRight::FULL_ACCESS
        ],
        self::SPIRITUAL_MOVEMENT_LEADER => [
            UserRight::ACCESS_BACKEND,
            UserRight::MANAGE_SPIRITUAL_MOVEMENT,
            UserRight::MANAGE_SPIRITUAL_MOVEMENT_GROUPS
        ]
    ];

    private static array $text = [
        self::SUPER_ADMIN => 'Super Admin',
        self::SPIRITUAL_MOVEMENT_LEADER => 'Lelikségi mozgalom vezető',
        self::GROUP_LEADER => 'Közösségvezető',
    ];

    /**
     * @return array<string, string>
     */
    public static function getTranslated(): array
    {
        return UserRole::get()->map(fn (UserRole $group) => $group->text())->all();
    }

    public static function can(string $role, $right): bool
    {
        $userRoles = collect(self::$roles[$role] ?? []);

        return $userRoles->containsAny((array) $right);
    }

    public function text(): string
    {
        return Arr::get(self::$text, $this->value);
    }
}
