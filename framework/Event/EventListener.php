<?php

namespace Framework\Event;

interface EventListener
{
    public function trigger($event);
}
