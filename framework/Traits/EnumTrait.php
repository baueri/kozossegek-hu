<?php

declare(strict_types=1);

namespace Framework\Traits;

use Closure;
use Framework\Support\Arr;
use Framework\Support\Collection;
use UnitEnum;

/**
 * @template T of UnitEnum|EnumTrait
 * @mixin UnitEnum
 */
trait EnumTrait
{
    /**
     * @return array<string, mixed>
     */
    public static function toArray(): array
    {
        $enums = [];

        foreach (static::cases() as $case) {
            $enums[$case->name] = $case->value();
        }

        return $enums;
    }

    public static function keys(): array
    {
        return array_map(fn ($enum) => $enum->name, static::cases());
    }

    public static function values(): array
    {
        return array_map(fn ($enum) => $enum->value(), static::cases());
    }

    /**
     * @return Collection<T>
     */
    public static function collect(): Collection
    {
        return collect(static::cases());
    }

    public static function map(Closure $callback): Collection
    {
        return static::collect()->map($callback);
    }

    final public function value(): int|string
    {
        return enum_val($this);
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

    public static function from($value): static
    {
        return get_enum(static::class, $value);
        return static::collect()->firstWhere(fn ($enum) => enum_val($enum) === $value);
    }
}
