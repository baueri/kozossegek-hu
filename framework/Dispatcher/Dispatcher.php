<?php

namespace Framework\Dispatcher;

interface Dispatcher
{
    public function dispatch(): void;

    /**
     * @param mixed $e
     */
    public function handleError($e): void;
}
