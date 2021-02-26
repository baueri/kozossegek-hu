<?php

use App\Admin\Components\DebugBar\DebugBar;
use App\HttpKernel;
use Framework\Application;
use Framework\Dispatcher\Dispatcher;
use Framework\Dispatcher\HttpDispatcher;
use Framework\Http\Request;

/* @var $application Application */

session_start();

ob_start();

include '../boot.php';

try {
    ob_start();
    $application->singleton(Framework\Http\Request::class);
    $application->singleton(\Framework\Http\HttpKernel::class, HttpKernel::class);
    $application->singleton(Dispatcher::class, HttpDispatcher::class);
    $application->singleton(DebugBar::class);

    $application->run($application->get(Dispatcher::class));
} catch (Error | \Exception | \Throwable $e) {
    ob_get_clean();
    $application->handleError($e);
}
