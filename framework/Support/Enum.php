<?php


namespace Framework\Support;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

class Enum
{

    private static ?array $constCacheArray = null;

    /**
     * @var string|int
     */
    protected $value;

    protected string $key;

    /**
     * @throws ReflectionException
     */
    private function __construct($value, string $key)
    {
        if (!static::isValid($value)) {
            throw new InvalidArgumentException('invalid enum type');
        }

        $this->value = $value;
        $this->key = $key;
    }

    /**
     * @param string|int $value
     * @return bool
     * @throws ReflectionException
     */
    public static function isValid($value)
    {
        return in_array($value, static::asArray());
    }

    /**
     * @return static[]|\Framework\Support\Collection
     * @throws \ReflectionException
     */
    public static function get(): Collection
    {
        return collect(self::asArray())->map(fn ($value, $key) => new static($value, $key), true);
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function asArray(): array
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

    /**
     * @return mixed
     * @throws ReflectionException
     */
    public static function random()
    {
        return static::values()->random();
    }

    /**
     * @return Collection
     * @throws ReflectionException
     */
    public static function values()
    {
        return collect(static::asArray())->values();
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    public static function valueOf(string $key)
    {
        return static::values()->get($key);
    }

    public static function keyOf($value): ?string
    {
        return array_search($value, static::asArray()) ?? null;
    }

    /**
     * @throws \ReflectionException
     */
    public static function of($value): Enum
    {
        $key = self::keyOf($value);
        return new static($value, $key);
    }
}
