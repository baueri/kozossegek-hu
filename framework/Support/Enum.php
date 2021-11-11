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

    final private function __construct($value, string $key)
    {
        if (!static::isValid($value)) {
            throw new InvalidArgumentException('invalid enum type');
        }

        $this->value = $value;
        $this->key = $key;
    }

    /**
     * @param string|int $value
     */
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

    /**
     * @return mixed
     * @throws ReflectionException
     */
    public static function random()
    {
        return static::values()->random();
    }

    public static function values(): Collection
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

    private static function keyOf($value)
    {
        $search = array_search($value, static::asArray());
        if (is_numeric($search)) {
            return $search;
        }
        return null;
    }

    public static function of($value): self
    {
        $key = self::keyOf($value);
        return new static($value, $key);
    }
}
