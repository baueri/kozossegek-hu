<?php

namespace Framework\Dispatcher;

interface Dispatcher
{
    public function dispatch();

    /**
     * @param mixed $e
     */
    public function handleError($e): void;
}
