<?php

namespace Framework\Dispatcher;

interface Dispatcher
{
    /**
     * @return void
     */
    public function dispatch(): void;

    public function handleError($e);
}
