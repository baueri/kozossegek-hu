<?php

namespace App\Enums;

enum WeekDay: string
{
    use HasTranslation;
    use EnumTrait;

    case he = 'he';
    case ke = 'ke';
    case sze = 'sze';
    case csu = 'csu';
    case pe = 'pe';
    case szo = 'szo';
    case vas = 'vas';
}
