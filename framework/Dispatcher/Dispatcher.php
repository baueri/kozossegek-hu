<?php

namespace Framework\Dispatcher;

interface Dispatcher
{
    public function dispatch(): void;

    public function handleError($e): void;
}
