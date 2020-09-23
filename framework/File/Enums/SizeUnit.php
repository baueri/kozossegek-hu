<?php

namespace Framework\File\Enums;

use Framework\Support\Enum;

class SizeUnit extends Enum
{
    const B = 'B';
    const KB = 'KB';
    const MB = 'MB';
    const GB = 'GB';
    const TB = 'TB';

    public static function getSizeUnits()
    {
        return [
            self::KB => 1,
            self::MB => 2,
            self::GB => 3,
            self::TB => 4
        ];
    }
}
