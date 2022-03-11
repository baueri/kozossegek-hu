<?php

namespace App\Enums;

enum AgeGroup: string
{
    use HasTranslation;
    use EnumTrait;

    case tinedzser = 'tinedzser';
    case fiatal_felnott = 'fiatal_felnott';
    case kozepkoru = 'kozepkoru';
    case nyugdijas = 'nyugdijas';
}
