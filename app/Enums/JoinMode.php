<?php


namespace App\Enums;


use Framework\Support\Enum;

class JoinMode extends Enum
{
    public const EGYENI_MEGBESZELES = 'egyeni';

    public const FOLYAMATOS = 'folyamatos';

    public const IDOSZAKOS = 'idoszakos';

    public static function getModesWithName()
    {
        return [
            self::EGYENI_MEGBESZELES => 'Egyéni megbeszélés alapján',
            self::FOLYAMATOS => 'Folyamatos csatlakozási lehetőség',
            self::IDOSZAKOS => 'Időszakos csatlakozás'
        ];
    }
}
