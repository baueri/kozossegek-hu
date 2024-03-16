<?php

namespace App\Enums;

use Framework\Support\Enum;

class UserRight extends Enum
{
    public const string FULL_ACCESS = 'FULL_ACCESS';
    public const string MANAGE_SPIRITUAL_MOVEMENT = 'MANAGE_SPIRITUAL_MOVEMENT';
    public const string MANAGE_SPIRITUAL_MOVEMENT_GROUPS = 'MANAGE_SPIRITUAL_MOVEMENT_GROUPS';
    public const string ACCESS_BACKEND = 'ACCESS_BACKEND';
}
