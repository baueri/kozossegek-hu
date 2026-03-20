<?php

namespace Framework\Event;

/**
 * @phpstan-template T of EventListener
 */
abstract class Event
{
    /**
     * @phpstan-var class-string<T>[]
     */
    protected static array $listeners = [];

    /**
     * @phpstan-return class-string<T>[]
     */
    public static function getListeners(): array
    {
        return static::$listeners;
    }

    public static function listen($listenerClassName): void
    {
        static::$listeners[] = $listenerClassName;
    }
}
