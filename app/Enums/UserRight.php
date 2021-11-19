<?php

namespace App\Enums;

use Framework\Support\Enum;

class UserRight extends Enum
{
    public const FULL_ACCESS = 'FULL_ACCESS';
    public const MANAGE_SPIRITUAL_MOVEMENT = 'MANAGE_SPIRITUAL_MOVEMENT';
    public const MANAGE_SPIRITUAL_MOVEMENT_GROUPS = 'MANAGE_SPIRITUAL_MOVEMENT_GROUPS';
    public const ACCESS_BACKEND = 'ACCESS_BACKEND';
}
