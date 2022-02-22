<?php

namespace App\Enums;

enum Denomination: string
{
    use HasTranslation;

    case katolikus = 'katolikus';
}
