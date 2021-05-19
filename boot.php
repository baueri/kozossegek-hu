<?php

use App\Bootstrapper\RegisterDirectives;
use App\Repositories\EventLogRepository;
use App\Services\EventLogger;
use Arrilot\DotEnv\DotEnv;
use Framework\Application;
use Framework\Database\Database;
use Framework\Database\DatabaseConfiguration;
use Framework\Database\PDO\PDOMysqlDatabase;
use Framework\Http\Route\Route;
use Framework\Http\Route\RouteInterface;
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
const CACHE = ROOT . 'cache' . DS;
const LANG = 'hu';
const APP_VERSION = 'v1.2';
const STORAGE_PATH = ROOT . 'storage' . DS;

include 'vendor/autoload.php';

DotEnv::load(ROOT . '.env.php');

if (!_env('DEBUG')) {
    ini_set("log_errors", 1);
    ini_set("error_log", ROOT . "error.log");
} else {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$application = new Application();

$application->bind(RouteInterface::class, Route::class);
$application->bind(ViewInterface::class, View::class);
$application->singleton(Config::class);
$application->singleton(RouterInterface::class, XmlRouter::class);
$application->singleton(EventLogger::class, EventLogRepository::class);
$application->singleton(Database::class, function (Application $app) {
    $settings = $app->config('db');
    $databaseConfiguration = $app->make(
        DatabaseConfiguration::class,
        $settings['host'],
        $settings['user'],
        $settings['password'],
        $settings['database'],
        $settings['charset'],
        $settings['port']
    );
    return $app->make(PDOMysqlDatabase::class, $databaseConfiguration);
});

$application->boot(RegisterDirectives::class);
$application->singleton(App\Repositories\Widgets::class);

include APP . 'macros.php';
