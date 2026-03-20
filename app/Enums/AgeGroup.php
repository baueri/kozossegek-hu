<?php

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum AgeGroup: string
{
    use HasTranslation;
    use EnumTrait;

    case tinedzser = 'tinedzser';
    case fiatal_felnott = 'fiatal_felnott';
    case kozepkoru = 'kozepkoru';
    case nyugdijas = 'nyugdijas';
}
