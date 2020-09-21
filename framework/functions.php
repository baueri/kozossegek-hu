<?php

use Framework\Application;
use Framework\Database\Builder;
use Framework\Database\Database;
use Framework\Http\Auth\Auth;
use Framework\Http\Response;
use Framework\Http\Route\RouterInterface;
use Framework\Support\Collection;
use Framework\Translator;

/**
 * @return Application|null
 */
function app() {
    return Application::getInstance();
}

function is_cli()
{
    return PHP_SAPI == 'cli';
}

function d(...$data) {
    if(!Response::contentTypeIsJson() && !is_cli()) {
        print "<pre style='white-space: pre-line'>";
    }
    foreach ($data as $toDump) {
        print_r(is_bool($toDump) ? ($toDump ? 'true' : 'false') : $toDump);
        print("\n");
    }
    $bt = debug_backtrace()[0];
    print("\ndumped at: " . $bt['file'] . ' on line ' . $bt['line']);
    print("\n----------------------------------------------------");
    if(!Response::contentTypeIsJson() && !is_cli()) {
        print "</pre>";
    }
}

function dd(...$data) {
    d(...$data);
    exit;
}

/**
 * @param string $key
 * @param string $lang
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

function route($route, array $args = [])
{
    return app()->get(RouterInterface::class)->route($route, $args);
}

function redirect($route, $args = [])
{
    $uri = route($route, $args);
    header("Location: $uri");
    exit;
}

/**
 * @return bool
 */
function logged_in() {
    return !is_null(Auth::user());
}

/**
 * @param $values
 * @return Collection
 */
function collect($values) {
    return Collection::create($values);
}