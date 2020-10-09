<?php

use App\HttpKernel;
use Framework\Application;
use Framework\Dispatcher\Dispatcher;
use Framework\Dispatcher\HttpDispatcher;
use Framework\Http\Request;

/* @var $application Application */

session_start();

include '../boot.php';

try {
    $application->singleton(Framework\Http\Request::class);
    $application->singleton(\Framework\Http\HttpKernel::class, HttpKernel::class);
    $application->singleton(Dispatcher::class, HttpDispatcher::class);

    /* @var $request Request */
    $request = $application->get(Request::class);

    $application->run($application->get(Dispatcher::class));

} catch (Error|\Exception|\Throwable $e) {
    ob_get_clean();
    $application->handleError($e);
}
