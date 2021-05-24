<?php

namespace App\Enums;

use Framework\Support\Arr;
use Framework\Support\Enum;

final class JoinMode extends Enum
{
    public const EGYENI_MEGBESZELES = 'egyeni';

    public const FOLYAMATOS = 'folyamatos';

    public const IDOSZAKOS = 'idoszakos';

    final public static function getText(?string $joinMode): ?string
    {
        return Arr::get(self::getModesWithName(), $joinMode);
    }

    final public static function getModesWithName(): array
    {
        return [
            self::EGYENI_MEGBESZELES => 'Egyéni megbeszélés alapján',
            self::FOLYAMATOS => 'Folyamatos csatlakozási lehetőség',
            self::IDOSZAKOS => 'Időszakos csatlakozás'
        ];
    }
}
