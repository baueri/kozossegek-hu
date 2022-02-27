<?php

namespace Framework\Event;

class EventDisptatcher
{
    public static function dispatch(Event $event): void
    {
        foreach ($event::getListeners() as $listener) {
            if (is_callable($listener)) {
                $listener($event);
                return;
            }

            app()->make($listener)->trigger($event);
        }
    }
}
