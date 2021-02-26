<?php

namespace Framework\Event;

/**
 * Description of EventDisptatcher
 *
 * @author ivan
 */
class EventDisptatcher
{
    public static function dispatch(Event $event)
    {
        foreach ($event::getListeners() as $listener) {
            if (is_callable($listener)) {
                $listener($event);
            } else {
                app()->make($listener)->trigger($event);
            }
        }
    }
}
