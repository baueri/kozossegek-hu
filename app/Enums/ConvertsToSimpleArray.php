<?php

namespace App\Enums;

use BackedEnum;

trait ConvertsToSimpleArray
{
    /**
     * @return array<string, mixed>
     */
    public static function toArray(): array
    {
        $enums = [];

        foreach (static::cases() as $case) {
            $enums[$case->name] = static::getVal($case);
        }

        return $enums;
    }

    public function keys(): array
    {
        return array_map(fn ($enum) => $enum->name, static::cases());
    }

    public function values(): array
    {
        return array_map(fn ($enum) => static::getVal($enum), static::cases());
    }

    private static function getVal($case): string
    {
        if ($case instanceof BackedEnum) {
            return $case->value;
        }

        return $case->name;
    }
}