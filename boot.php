<?php

declare(strict_types=1);

use App\Admin\Components\DebugBar\DebugBar;
use App\Auth\Auth;
use App\Auth\AuthUser;
use App\Bootstrapper\RegisterDirectives;
use App\Enums\PageStatus;
use App\Enums\PageType;
use App\Models\Page;
use App\Models\User;
use App\Portal\Services\Search\SearchRepository;
use App\QueryBuilders\Pages;
use App\QueryBuilders\Users;
use App\Repositories\EventLogs;
use App\Services\EventLogger;
use App\Services\MeiliSearch\MeiliSearchAdapter;
use App\Services\MileStone;
use App\Services\User\Announcer;
use Dotenv\Dotenv;
use Framework\Application;
use Framework\Console\ConsoleKernel;
use Framework\Database\Database;
use Framework\Database\PDO\PDOMysqlDatabaseFactory;
use Framework\Database\Repository\Events\ModelCreated;
use Framework\Http\HttpKernel;
use Framework\Http\Request;
use Framework\Http\Route\RouterInterface;
use Framework\Http\Route\XmlRouter;
use Framework\Http\View\View;
use Framework\Http\View\ViewInterface;
use Framework\Support\Config\Config;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

const ROOT = __DIR__ . DS;
const APP = ROOT . 'app' . DS;
const RESOURCES = ROOT . 'resources' . DS;
const VIEWS = RESOURCES . 'views' . DS;
const CACHE = ROOT . 'cache' . DS;
const APP_VERSION = 'v4.0';

// Config constants for faster development
const APP_CFG_LEGAL_NOTICE_VERSION = 'app.legal_notice_version';
const APP_CFG_LEGAL_NOTICE_DATE = 'app.legal_notice_date';

include "framework/functions.php";
include "app/helpers.php";

$_ENV['ROOT'] = ROOT;

$dotenv = Dotenv::createMutable(__DIR__);
$dotenv->load();

date_default_timezone_set(env('APP_TIMEZONE', 'Europe/Budapest'));

ini_set("log_errors", 1);

if (!env('DEBUG') && !is_cli()) {
    ini_set("error_log", ROOT . "error.log");
} else {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$application = new Application(ROOT);

MileStone::measure('init', 'Initialize');

$application->singleton([
    ConsoleKernel::class => ConsoleKernel::class,
    HttpKernel::class => HttpKernel::class,
    ViewInterface::class => View::class,
    Config::class => Config::class,
    RouterInterface::class => fn (Application $app, Request $request) => new XmlRouter($request, config('route_sources')),
    EventLogger::class => EventLogs::class,
    Database::class => fn () => PDOMysqlDatabaseFactory::create(),
    Request::class => Request::class,
    MeiliSearchAdapter::class => MeiliSearchAdapter::class,
    DebugBar::class => DebugBar::class,
]);

$application->bind([
    'errorHandler' => fn () => fn ($error) => throw $error,
    AuthUser::class => fn () => Auth::user(),
    SearchRepository::class => fn () => $application->get(config('app.search_drivers')[config('app.selected_search_driver')]),
]);

$application->on('booting', function () { MileStone::measure('bootstrap'); });
$application->on('booted', function () { MileStone::endMeasure('bootstrap'); });

$application->bootWith(RegisterDirectives::class);

$application->boot();

ModelCreated::listen(function (ModelCreated $event) {
    if ($event->model instanceof Page && $event->model->page_type == PageType::announcement->value()) {
        (new Announcer())->announce($event->model, Users::query()->notDeleted());
    }
});

MileStone::endMeasure('init');

include APP . 'macros.php';
