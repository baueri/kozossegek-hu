<?php

namespace App\Enums;

enum Denomination: string
{
    use HasTranslation;
    use EnumTrait;

    case katolikus = 'katolikus';
}
