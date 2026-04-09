<?php

declare(strict_types=1);

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum EventStatus: string
{
    use HasTranslation;
    use EnumTrait;

    case draft = 'draft';
    case pending = 'pending';
    case approved = 'approved';
    case rejected = 'rejected';
}

