<?php

use App\Bootstrapper\RegisterTitleDirective;
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

define('ROOT', __DIR__ . DS);
define('APP', ROOT . 'app' . DS);
define('RESOURCES', ROOT . 'resources' . DS);
define('CACHE', ROOT . 'cache' . DS);
define('LANG', 'hu');
define('APP_VERSION', 'v0.2 pre-alpha');
define('STORAGE_PATH', ROOT . 'storage' . DS);

include 'vendor/autoload.php';

DotEnv::load(ROOT . '.env.php');

if (!_env('DEBUG')) {
    ini_set("log_errors", 1);
    ini_set("error_log", ROOT . "error.log");
}

$application = new Application();

$application->bind(RouteInterface::class, Route::class);
$application->bind(ViewInterface::class, View::class);
$application->singleton(Config::class);
$application->singleton(RouterInterface::class, XmlRouter::class);
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

$application->boot(RegisterTitleDirective::class);
$application->singleton(App\Repositories\Widgets::class);

include APP . 'macros.php';

