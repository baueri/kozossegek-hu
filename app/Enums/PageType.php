<?php

declare(strict_types=1);

namespace App\Enums;

enum PageType
{
    use EnumTrait;

    case page;
    case blog;
    case announcement;

    public function translate(): string
    {
        return match ($this) {
            self::page => 'Oldal',
            self::announcement => 'Hirdetmény',
            self::blog => 'Hír',
        };
    }
}
