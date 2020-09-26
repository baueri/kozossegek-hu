<?php

use Arrilot\DotEnv\DotEnv;
use Framework\Application;
use Framework\Database\Builder;
use Framework\Database\Database;
use Framework\Dispatcher\HttpDispatcher;
use Framework\Http\Request;
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
        print "<pre style='white-space: pre-line'>";
    }
    foreach ($data as $toDump) {
        if (is_bool($toDump)) {
            print_r($toDump ? 'true' : 'false');
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
function builder()
{
    return app()->make(Builder::class);
}

/**
 * @param $route
 * @param array $args
 * @return string
 */
function route($route, array $args = [])
{
    return app()->get(RouterInterface::class)->route($route, $args);
}

/**
 * @param $route
 * @param array $args
 */
function redirect($route, $args = [])
{
    $uri = route($route, $args);
    header("Location: $uri");
    exit;
}

/**
 * @param $values
 * @return Collection
 */
function collect($values)
{
    return Collection::create($values);
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