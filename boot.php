<?php

use App\Bootstrapper\RegisterDirectives;
use App\Repositories\EventLogRepository;
use App\Services\EventLogger;
use App\Services\MileStone;
use Dotenv\Dotenv;
use Framework\Application;
use Framework\Database\Database;
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
const VIEWS = RESOURCES . 'views' . DS;
const CACHE = ROOT . 'cache' . DS;
const APP_VERSION = 'v4.0';

// Config constants for faster development
const APP_CFG_LEGAL_NOTICE_VERSION = 'app.legal_notice_version';
const APP_CFG_LEGAL_NOTICE_DATE = 'app.legal_notice_date';

include "framework/functions.php";

$_ENV['ROOT'] = ROOT;

$dotenv = Dotenv::createImmutable(__DIR__);
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

$application->bind(RouteInterface::class, Route::class);
$application->singleton(ViewInterface::class, View::class);
$application->singleton(Config::class);
$application->singleton(RouterInterface::class, XmlRouter::class);
$application->singleton(EventLogger::class, EventLogRepository::class);
$application->on('booting', function () { MileStone::measure('bootstrap'); });
$application->on('booted', function () { MileStone::endMeasure('bootstrap'); });

$application->singleton(Database::class, function () {
    $configuration = config('db');

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s;port=%s',
        $configuration['host'],
        $configuration['database'],
        $configuration['charset'],
        $configuration['port']
    );

    $pdo = new PDO($dsn, $configuration['user'], $configuration['password'], [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    ]);

    return new PDOMysqlDatabase($pdo);
});

$application->boot(RegisterDirectives::class);

MileStone::endMeasure('init');

include APP . 'macros.php';
