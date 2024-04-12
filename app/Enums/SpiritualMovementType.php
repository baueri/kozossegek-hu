<?php

declare(strict_types=1);

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum SpiritualMovementType: string
{
    use HasTranslation;
    use EnumTrait;

    case spiritual_movement = 'spiritual_movement';
    case monastic_community = 'monastic_community';
}
