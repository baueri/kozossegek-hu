<?php

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum OccasionFrequency: string
{
    use HasTranslation;
    use EnumTrait;

    case hetente_tobbszor = 'hetente_tobbszor';
    case hetente = 'hetente';
    case kethetente = 'kethetente';
    case havonta = 'havonta';
    case egyeb = 'egyeb';
}
