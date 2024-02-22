<?php

use App\Admin\Components\DebugBar\DebugBar;
use App\Auth\Auth;
use App\Auth\AuthUser;
use App\HttpKernel;
use Framework\Http\Request;
use Framework\Http\Session;
use Framework\Kernel;

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

    $app->singleton(Request::class);
    $app->singleton(Kernel::class, HttpKernel::class);
    $app->singleton(DebugBar::class);
    $app->bind(AuthUser::class, function () {
        return Auth::user();
    });

    $kernel = $app->get(HttpKernel::class);

    $kernel->middleware(function () {
        dd('alma');
    });

    $kernel->handle();

} catch (Error | Exception | Throwable $e) {
    ob_get_clean();
    $app->handleError($e);
}
