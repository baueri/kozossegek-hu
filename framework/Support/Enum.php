<?php


namespace Framework\Support;


use ReflectionClass;

class Enum
{

    private static $constCacheArray = NULL;

    public static function all() {
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
    
    public static function values()
    {
        return array_values(static::all());
    }
    
    public static function random()
    {
        $values = static::values();
        
        return $values[array_rand($values)];
    }
}