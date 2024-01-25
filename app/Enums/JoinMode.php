<?php

declare(strict_types=1);

namespace App\Enums;

enum JoinMode:string
{
    use EnumTrait;
    use HasTranslation;

    case egyeni = 'egyeni';
    case folyamatos = 'folyamatos';
    case idoszakos = 'idoszakos';
}
