<?php

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum Permission
{
    use EnumTrait;

    case FULL_ACCESS;
    case MANAGE_SPIRITUAL_MOVEMENT;
    case MANAGE_SPIRITUAL_MOVEMENT_GROUPS;
    case ACCESS_BACKEND;
}
