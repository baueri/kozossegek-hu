<?php

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum Denomination: string
{
    use HasTranslation;
    use EnumTrait;

    case katolikus = 'katolikus';
}
