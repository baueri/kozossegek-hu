<?php

use App\Admin\Components\DebugBar\DebugBar;
use App\Repositories\Widgets;
use Arrilot\DotEnv\DotEnv;
use Framework\Application;
use Framework\Database\Builder;
use Framework\Database\Database;
use Framework\Dispatcher\Dispatcher;
use Framework\Dispatcher\HttpDispatcher;
use Framework\Http\Response;
use Framework\Http\Route\RouteInterface;
use Framework\Http\Route\RouterInterface;
use Framework\Http\View\ViewInterface;
use Framework\Support\Collection;
use Framework\Translator;

/**
 * @return Application|null
 */
function app()
{
    return Application::getInstance();
}

/**
 * @return bool
 */
function is_cli()
{
    return PHP_SAPI == 'cli';
}

function d(...$data)
{
    if (!Response::contentTypeIsJson() && !is_cli()) {
        // print "<pre style='white-space: pre-line'>";
        print "<pre>";
    }
    foreach ($data as $toDump) {
        if (is_bool($toDump)) {
            print_r($toDump ? 'true' : 'false');
        } elseif (is_null($toDump)) {
            print_r('null');
        } else {
            print_r($toDump);
        }
        print("\n");
    }
    $bt = debug_backtrace()[0];
    print("\ndumped at: " . $bt['file'] . ' on line ' . $bt['line']);
    print("\n----------------------------------------------------");
    if (!Response::contentTypeIsJson() && !is_cli()) {
        print "</pre>";
    }
    if (is_cli()) {
        print PHP_EOL;
    }
}

function dd(...$data)
{
    d(...$data);
    exit;
}

/**
 * @param string|null $key
 * @param null $lang
 * @return string|Translator
 */
function lang($key = null, $lang = null)
{
    $translator = app()->get(Translator::class);

    $lang = $lang ?: getLang();

    if (is_null($key)) {
        return $translator;
    }

    return app()->get(Translator::class)->translate($key, $lang);
}

/**
 * @param $key
 * @param mixed ...$args
 * @return string
 */
function lang_f($key, ...$args)
{
    return lang()->setDefaultLang(getLang())->translate_f($key, ...$args);
}

/**
 * @return string
 */
function getLang()
{
    return app()->getLocale();
}

/**
 * @param null $connection
 * @return Database
 */
function db($connection = null)
{
    if (!$connection) {
        return app()->get(Database::class);
    }

    return app()->make(Database::class);
}

/**
 * @return Builder
 */
function builder($table = null)
{
    return app()->make(Builder::class)->table($table);
}

/**
 * @param $route
 * @param array|string $args
 * @return string
 */
function route($route, $args = [])
{
    return app()->get(RouterInterface::class)->route($route, $args);
}

/**
 * @var string $uri
 */
function redirect($uri)
{
    header("Location: $uri");
    exit;
}

/**
 * @param string $route
 * @param array $args
 */
function redirect_route($route, $args = [])
{
    redirect(route($route, $args));
}

/**
 * @param $values
 * @return Collection
 */
function collect($values = [])
{
    return Collection::create($values);
}

function collect_file($file)
{
    return collect(array_filter(explode(PHP_EOL, file_get_contents($file))));
}

/**
 * @param $key
 * @param null $default
 * @return mixed|null
 */
function _env($key, $default = null)
{
    return DotEnv::get($key, $default);
}

/**
 * @param string $view
 * @param array $args
 * @return string
 */
function view($view, array $args = [])
{
    return app()->make(ViewInterface::class)->view($view, $args);
}

/**
 * @return RouteInterface
 */
function current_route()
{
    return app()->make(HttpDispatcher::class)->getCurrentRoute();
}

/**
 * @return Dispatcher
 */
function dispatcher()
{
    return app()->make(Dispatcher::class);
}

/**
 * @return string
 */
function get_site_url()
{
    return $_SERVER['HTTP_HOST'];
}

/**
 *
 * @param mixed $key
 * @param mixed $default
 * @return mixed
 */
function config($key, $default = null)
{
    return app()->config($key, $default);
}

function make($abstraction, $values = [])
{
    return app()->make($abstraction, ...$values);
}

function image_with_watermark($imgPath)
{
    $stamp = imagecreatefrompng(ROOT . 'resources/watermark.png');
    $img = imagecreatefromjpeg($imgPath);

    $marge_right = 10;
    $marge_bottom = 10;
    $sx = imagesx($stamp);
    $sy = imagesy($stamp);

    imagecopy($img, $stamp, imagesx($img) - $sx - $marge_right, imagesy($img) - $sy - $marge_bottom, 0, 0, $sx, $sy);

    ob_start();
    imagejpeg($img);

    $mime_type = mime_content_type($imgPath);
    header('Content-Type: '.$mime_type);
}

function widget($uniqid)
{

    return app()->get(Widgets::class)->getByUniqId($uniqid);
}

function is_prod()
{
    return config('app.environment') === 'production';
}

/**
 * @return DebugBar
 */
function debugbar()
{
    return app()->get(DebugBar::class);
}