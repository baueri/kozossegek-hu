<?php

namespace Framework\Dispatcher;

interface Dispatcher
{
    public function dispatch();

    public function handleError($e): void;
}
