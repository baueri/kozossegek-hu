<?php

use Framework\Application;
use Framework\Database\Database;
use Framework\Database\DatabaseConfiguration;
use Framework\Database\PDO\PDOMysqlDatabase;
use Framework\Http\Auth\Auth;
use Framework\Http\Route\Route;
use Framework\Http\Route\RouteInterface;
use Framework\Http\Route\RouterInterface;
use Framework\Http\Route\XmlRouter;
use Framework\Support\Config\Config;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS);
define('APP', ROOT . 'app' . DS);
define('RESOURCES', ROOT . 'resources' . DS);
define('CACHE', ROOT . 'cache' . DS);
define('DEBUG', true);
define('LANG', 'hu');

include 'vendor/autoload.php';

$application = new Application();

$application->bind(RouteInterface::class, Route::class);
$application->singleton(Auth::class);
$application->singleton(Config::class);
$application->singleton(RouterInterface::class, XmlRouter::class);
$application->singleton(Database::class, function (Application $app) {
    $settings = $app->config('db');
    $databaseConfiguration = $app->make(DatabaseConfiguration::class,
        $settings['host'],
        $settings['name'],
        $settings['password'],
        $settings['database'],
        $settings['charset']
    );
    return $app->make(PDOMysqlDatabase::class, $databaseConfiguration);
});