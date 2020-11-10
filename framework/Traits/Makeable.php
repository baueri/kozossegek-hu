<?php


namespace Framework\Traits;


trait Makeable
{
    /**
     * @param mixed ...$args
     * @return static
     */
    public static function make(...$args)
    {
        return app()->make(static::class, ...$args);
    }
}