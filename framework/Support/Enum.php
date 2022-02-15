<?php

namespace Framework\Support;

use InvalidArgumentException;
use ReflectionClass;

class Enum
{
    private static ?array $constCacheArray = null;

    final private function __construct(public readonly mixed $value, public readonly string $key)
    {
        if (!static::isValid($value)) {
            throw new InvalidArgumentException('invalid enum type');
        }
    }

    public static function isValid($value): bool
    {
        return in_array($value, static::asArray());
    }

    /**
     * @return Collection<string, static>
     */
    public static function get(): Collection
    {
        return collect(self::asArray())->map(fn ($value, $key) => new static($value, $key), true);
    }

    final public static function asArray(): array
    {
        if (self::$constCacheArray == null) {
            self::$constCacheArray = [];
        }

        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return self::$constCacheArray[$calledClass];
    }

    public static function values(): Collection
    {
        return collect(static::asArray())->values();
    }

    public function value()
    {
        return $this->value;
    }

    public static function valueOf(string $key)
    {
        return static::values()->get($key);
    }

    private static function keyOf($value): ?string
    {
        return array_search($value, static::asArray()) ?? null;
    }

    public static function of($value): static
    {
        $key = self::keyOf($value);
        return new static($value, $key);
    }
}
