<?php

use App\Admin\Components\DebugBar\DebugBar;
use App\HttpKernel;
use Framework\Dispatcher\Dispatcher;
use Framework\Dispatcher\HttpDispatcher;

include '../vendor/autoload.php';

session_start();

ob_start();

try {
    ob_start();
    app()->singleton(Framework\Http\Request::class);
    app()->singleton(\Framework\Http\HttpKernel::class, HttpKernel::class);
    app()->singleton(Dispatcher::class, HttpDispatcher::class);
    app()->singleton(DebugBar::class);

    app()->run(app()->get(Dispatcher::class));
} catch (Error | Exception | Throwable $e) {
    ob_get_clean();
    app()->handleError($e);
}
