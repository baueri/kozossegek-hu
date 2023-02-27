<?php

declare(strict_types=1);

namespace App\Enums;

enum SpiritualMovementType: string
{
    use HasTranslation;
    use EnumTrait;

    case spiritual_movement = 'spiritual_movement';
    case monastic_community = 'monastic_community';
}