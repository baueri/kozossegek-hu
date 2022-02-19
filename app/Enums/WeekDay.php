<?php

namespace App\Enums;

enum WeekDay: string
{
    use HasTranslation;

    case he = 'he';
    case ke = 'ke';
    case sze = 'sze';
    case csut = 'csut';
    case pe = 'pe';
    case szo = 'szo';
    case vas = 'vas';
}