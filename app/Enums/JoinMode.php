<?php

declare(strict_types=1);

namespace App\Enums;

enum JoinMode
{
    use EnumTrait;
    use HasTranslation;

    case egyeni;
    case folyamatos;
    case idoszakos;
}