<?php

use App\Admin\Components\DebugBar\DebugBar;
use App\Auth\Auth;
use App\Auth\AuthUser;
use App\HttpKernel;
use App\Services\MileStone;
use Framework\Dispatcher\Dispatcher;
use Framework\Dispatcher\HttpDispatcher;
use Framework\Http\Session;

if (file_exists('../.maintenance')) {
    include '../resources/views/maintenance.php';
    exit;
}

include '../vendor/autoload.php';

Session::start();

ob_start();
$app = app();

try {
    ob_start();

    $app->singleton(Framework\Http\Request::class);
    $app->singleton(\Framework\Http\HttpKernel::class, HttpKernel::class);
    $app->singleton(HttpDispatcher::class);
    $app->singleton(Dispatcher::class, HttpDispatcher::class);
    $app->singleton(DebugBar::class);
    $app->bind(AuthUser::class, function () {
        return Auth::user();
    });

    $app->run($app->get(HttpDispatcher::class));
} catch (Error | Exception | Throwable $e) {
    ob_get_clean();
    $app->handleError($e);
}
