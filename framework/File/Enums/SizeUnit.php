<?php

namespace Framework\File\Enums;

use Framework\Support\Enum;

class SizeUnit extends Enum
{
    public const B = 'B';
    public const KB = 'KB';
    public const MB = 'MB';
    public const GB = 'GB';
    public const TB = 'TB';

    public static function getSizeUnits(): array
    {
        return [
            self::KB => 1,
            self::MB => 2,
            self::GB => 3,
            self::TB => 4
        ];
    }
}
