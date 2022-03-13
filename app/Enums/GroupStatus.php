<?php

namespace App\Enums;

enum GroupStatus: string
{
    use HasTranslation;
    use EnumTrait;

    case active = 'active';
    case inactive = 'inactive';

    public function class(): string
    {
        return match($this) {
            self::active => 'fa fa-check-circle text-success',
            self::inactive => 'fa fa-moon text-muted',
        };
    }
}
