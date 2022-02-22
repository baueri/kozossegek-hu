<?php

namespace App\Enums;

enum OccasionFrequency: string
{
    use HasTranslation;
    use ConvertsToSimpleArray;

    case hetente_tobbszor = 'hetente-tobbszor';
    case hetente = 'hetente';
    case kethetente = 'kethetente';
    case havonta = 'havonta';
    case egyeb = 'egyeb';
}
