<?php


namespace Framework\Support;


use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

class Enum
{

    /**
     * @var null|array
     */
    private static $constCacheArray = NULL;

    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     * @throws ReflectionException
     */
    public function __construct(string $value)
    {
        if (!static::isValid($value)) {
            throw new InvalidArgumentException('invalid enum type');
        }

        $this->value = $value;
    }

    /**
     * @param string $value
     * @return bool
     * @throws ReflectionException
     */
    public static function isValid(string $value)
    {
        return in_array($value, static::all());
    }

    /**
     * @return array|mixed
     * @throws ReflectionException
     */
    public static function all()
    {
        if (self::$constCacheArray == NULL) {
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
        return collect(static::all())->values();
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

}