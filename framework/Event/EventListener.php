<?php


namespace Framework\Event;


interface EventListener
{
    /**
     * @param Event $event
     */
    public function trigger($event);
}