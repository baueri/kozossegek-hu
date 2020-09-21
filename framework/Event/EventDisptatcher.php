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
            app()->make($listener)->trigger($event);
        }
    }
}
