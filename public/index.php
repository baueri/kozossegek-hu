<?php

use App\Admin\Components\DebugBar\DebugBar;
use App\Auth\Auth;
use App\Auth\AuthUser;
use App\Http\ErrorHandler;
use App\Middleware\DebugBarMiddleware;
use App\Portal\Services\Search\SearchRepository;
use App\Providers\AppServiceProvider;
use Framework\Http\HttpKernel;
use Framework\Http\Request;
use Framework\Http\Session;
use Framework\Middleware\AuthMiddleware;
use Framework\Middleware\BaseAuthMiddleware;
use Framework\Middleware\Translation;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

if (file_exists('../.maintenance')) {
    include '../resources/views/maintenance.php';
    exit;
}

include '../vendor/autoload.php';

Session::start();

ob_start();

$app = app();

try {
    $app->bind('errorHandler', ErrorHandler::class, true);

    $kernel = $app->get(HttpKernel::class);

    $kernel->middleware(function() {
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->register();
    })->middleware(BaseAuthMiddleware::class)
        ->middleware(DebugBarMiddleware::class)
        ->middleware(Translation::class)
        ->middleware(AuthMiddleware::class)
        ->middleware(AppServiceProvider::class);

    $kernel->handle();
} catch (Error | Exception | Throwable $e) {
    ob_get_clean();
    $app->handleError($e);
}
