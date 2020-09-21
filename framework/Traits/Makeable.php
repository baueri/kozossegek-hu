<?php


namespace Framework\Traits;


trait Makeable
{
    /**
     * @return static
     */
    public static function make()
    {
        return app()->make(static::class);
    }
}