<?php

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

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS);
define('APP', ROOT . 'app' . DS);
define('RESOURCES', ROOT . 'resources' . DS);
define('CACHE', ROOT . 'cache' . DS);
define('LANG', 'hu');

include 'vendor/autoload.php';

DotEnv::load(ROOT . '.env.php');

$application = new Application();

$application->up();

$application->bind(RouteInterface::class, Route::class);
$application->bind(ViewInterface::class, View::class);
$application->singleton(Config::class);
$application->singleton(RouterInterface::class, XmlRouter::class);
$application->singleton(Database::class, function (Application $app) {
    $settings = $app->config('db');
    $databaseConfiguration = $app->make(DatabaseConfiguration::class,
        $settings['host'],
        $settings['user'],
        $settings['password'],
        $settings['database'],
        $settings['charset'],
        $settings['port']
    );
    return $app->make(PDOMysqlDatabase::class, $databaseConfiguration);
});

$application->bind(Model::class, function(Request $request, $entity) {
    dd($entity);
});