<?php

declare(strict_types=1);

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum EventType
{
    use EnumTrait;
    use HasTranslation;

    case zarandoklat;
    case kepzes;
    case Kulturalis;
    case kozossegi;
    case szentmise;
    case lelkigyakorlat;
    case dicsoites;
    case imaest;
}