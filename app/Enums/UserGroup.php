<?php

namespace App\Enums;

use Framework\Support\Arr;
use Framework\Support\Enum;

class UserGroup extends Enum
{
    public const SUPER_ADMIN = 'SUPER_ADMIN';

    public const GROUP_LEADER = 'GROUP_LEADER';

    private static $text = [
        self::SUPER_ADMIN => 'Super Admin',
        self::GROUP_LEADER => 'Közösségvezető'
    ];

    public function text(): string
    {
        return Arr::get(self::$text, $this->value);
    }
}