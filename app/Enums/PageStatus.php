<?php

declare(strict_types=1);

namespace App\Enums;

enum PageStatus
{
    use EnumTrait;

    case PUBLISHED;
    case DRAFT;
}
