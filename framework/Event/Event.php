<?php

namespace Framework\Event;

abstract class Event
{
    /**
     * @var EventListener[]
     */
    protected static array $listeners = [];

    /*
     * @return EventListener[]
     */
    public static function getListeners()
    {
        return static::$listeners;
    }

    public static function listen($listenerClassName)
    {
        static::$listeners[] = $listenerClassName;
    }
}
