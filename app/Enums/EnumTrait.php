<?php

namespace App\Enums;

use BackedEnum;
use Framework\Support\Arr;
use Framework\Support\Collection;
use UnitEnum;

trait EnumTrait
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

    public static function keys(): array
    {
        return array_map(fn ($enum) => $enum->name, static::cases());
    }

    public static function values(): array
    {
        return array_map(fn ($enum) => static::getVal($enum), static::cases());
    }

    /**
     * @return Collection<static>
     */
    public static function collect(): Collection
    {
        return collect(static::cases());
    }

    final public function value()
    {
        return static::getVal($this);
    }

    private static function getVal(UnitEnum $case)
    {
        if ($case instanceof BackedEnum) {
            return $case->value;
        }

        return $case->name;
    }

    public static function random(): static
    {
        return static::collect()->random();
    }

    /**
     * @return Collection<static>
     */
    public static function fromList(null|string|array|Collection $items, ?string $separator = null): Collection
    {
        if (is_null($items)) {
            return collect();
        }

        if (is_string($items)) {
            $items = Arr::fromList($items, $separator);
        }

        return collect($items)->as(static::class);
    }

    public static function from($value)
    {
        return static::collect()->filter(fn ($enum) => $enum->value() === $value)->first();
    }
}
