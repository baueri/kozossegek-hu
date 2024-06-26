<?php

declare(strict_types=1);

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum GroupPending: int
{
    use EnumTrait;

    case confirmed = 0;
    case pending = 1;
    case rejected = -1;
}
