<?php

declare(strict_types=1);

namespace Framework\File\Enums;

enum SizeUnit
{
    case B;
    case KB;
    case MB;
    case GB;
    case TB;

    public function exponent(): int
    {
        return match ($this) {
            self::B => 0,
            self::KB => 1,
            self::MB => 2,
            self::GB => 3,
            self::TB => 4,
        };
    }

    public function convert(int $size, int $precision = 5): float
    {
        return round($size / pow(1024, $this->exponent()), $precision);
    }
}
