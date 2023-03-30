<?php

namespace Framework\Event;

abstract class Event
{
    protected static array $listeners = [];

    public static function getListeners(): array
    {
        return static::$listeners;
    }

    public static function listen($listenerClassName): void
    {
        static::$listeners[] = $listenerClassName;
    }
}
