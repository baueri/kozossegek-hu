<?php

declare(strict_types=1);

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum EventLifeCycle: string
{
    use HasTranslation;
    use EnumTrait;

    case active = 'active';
    case cancelled = 'cancelled';
}

