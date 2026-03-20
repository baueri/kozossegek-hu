<?php

declare(strict_types=1);

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum PageStatus
{
    use EnumTrait;

    case PUBLISHED;
    case DRAFT;
}
