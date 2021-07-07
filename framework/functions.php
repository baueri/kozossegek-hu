<?php

use App\Admin\Components\DebugBar\DebugBar;
use App\Auth\Auth;
use App\Mailable\CriticalErrorEmail;
use App\Middleware\AdminMiddleware;
use App\Models\User;
use App\Repositories\EventLogRepository;
use App\Repositories\Widgets;
use App\Services\EventLogger;
use Arrilot\DotEnv\DotEnv;
use Carbon\Carbon;
use Framework\Application;
use Framework\Database\Builder;
use Framework\Database\Database;
use Framework\Dispatcher\Dispatcher;
use Framework\Dispatcher\HttpDispatcher;
use Framework\Http\ApiResponse;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Route\RouteInterface;
use Framework\Http\Route\RouterInterface;
use Framework\Http\View\View;
use Framework\Http\View\ViewInterface;
use Framework\Mail\Mailer;
use Framework\Model\Model;
use Framework\Support\Collection;
use Framework\Translator;
use PHPDeploy\PHPDeploy;

/**
 * @return Application|null|mixed
 * @psalm-template T
 * @psalm-param class-string<T> $abstract
 * @psalm-return T
 */
function app(string $abstract = null)
{
    if ($abstract) {
        return Application::getInstance()->get($abstract);
    }

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
    $bt = debug_backtrace();
    if (isset($bt[1]) && $bt[1]['function'] === 'dd') {
        $trace = $bt[1];
    } else {
        $trace = $bt[0];
    }
    print("\ndumped at: {$trace['file']} on line {$trace['line']}");
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
 * @param string|null $table
 * @return Builder
 */
function builder(?string $table = null)
{
    return app()->make(Builder::class)->table($table);
}

/**
 * @param string $route
 * @param array|string|Model|Entity $args
 * @return string
 */
function route(string $route, $args = [])
{
    return app()->get(RouterInterface::class)->route($route, $args);
}

function route_is(string $routeName): bool
{
    return current_route()->getAs() === $routeName;
}

function redirect(string $uri)
{
    if ($uri === 'self') {
        $uri = request()->uri;
    }
    header("Location: $uri");
    exit;
}

/**
 * @param string $route
 * @param array $args
 */
function redirect_route(string $route, $args = [])
{
    redirect(route($route, $args));
}

/**
 * @param $values
 * @return Collection
 */
function collect(?array $values = [])
{
    return Collection::create($values);
}

function collect_file($file)
{
    return collect(array_filter(explode(PHP_EOL, file_get_contents($file))));
}

/**
 * @param $key
 * @param mixed|null $default
 * @return mixed|null
 */
function _env($key, $default = null)
{
    return DotEnv::get($key, $default);
}

function now($tz = null): Carbon
{
    return Carbon::now($tz);
}

/**
 * @param string|null $view
 * @param array $args
 * @return string|View
 * @throws \ReflectionException
 */
function view(string $view = null, array $args = [])
{
    if ($view) {
        return app()->make(ViewInterface::class)->view($view, $args);
    }

    return app()->make(ViewInterface::class);
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
    return config('app.site_url');
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
    header("Content-Type: {$mime_type}");
}

function widget($uniqid)
{
    return app()->get(Widgets::class)->getByUniqId($uniqid);
}

function is_prod()
{
    return app()->envIs('production');
}

/**
 * @return DebugBar
 */
function debugbar()
{
    return app()->get(DebugBar::class);
}

function is_home()
{
    return !trim(app()->get(Request::class)->uri, '/');
}

function request(): Request
{
    return app()->get(Request::class);
}

function is_admin()
{
    return in_array(AdminMiddleware::class, current_route()->getMiddleware());
}

function mb_ucfirst($string, $encoding = 'utf-8')
{
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, null, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}

function raise_error_page(int $code, string $message, string $message2)
{
    echo view('portal.error', compact('code', 'message', 'message2'));
    exit();
}

function raise_500(string $message = '', string $message2 = 'Nincs jogosultsága az oldal megtekintéséhez')
{
    raise_error_page(500, $message, $message2);
}

function raise_404($message = 'A keresett oldal nem található', $message2 = '<i class="text-muted">De azért ne adjátok fel.<br/> Keressetek, és előbb, vagy utóbb találtok ;-)</i>')
{
    raise_error_page(404, $message, $message2);
}

function raise_403($message = '', $message2 = 'Nincs jogosultsága a tartalom megtekintéséhez!')
{
    raise_error_page(403, $message, $message2);
}

function process_error($e)
{
    if (!_env('DEBUG')) {
        $errorEmail = new CriticalErrorEmail($e);
        (new Mailer())->to(config('app.error_email'))->send($errorEmail);
    } else {
        dd($e, request()->all(), request()->route);
    }
}

function is_loggedin()
{
    return Auth::loggedIn();
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . DS . $object) && !is_link($dir . "/" . $object)) {
                    rrmdir($dir . DS . $object);
                } else {
                    unlink($dir . DS . $object);
                }
            }
        }
        return rmdir($dir);
    }

    return true;
}

function api(): ApiResponse
{
    return new ApiResponse();
}

// copies files and non-empty directories
function rcopy($src, $dst, $excludeSymlinks = false)
{
    if ($excludeSymlinks && is_link($src)) {
        return null;
    }

    if (file_exists($dst)) {
        rrmdir($dst);
    }
    if (is_dir($src)) {
        mkdir($dst, 0777, true);
        $files = scandir($src);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                rcopy("$src/$file", "$dst/$file");
            }
        }
    } elseif (file_exists($src)) {
        return copy($src, $dst);
    }
}

/**
 * @param $env
 * @param null $cwd
 * @return PHPDeploy
 * @throws Exception
 */
function php_deploy($env, $cwd = null)
{
    return new PHPDeploy($env, $cwd);
}

function set_header_bg(string $bg)
{
    View::setVariable('header_background', $bg);
}

function use_default_header_bg()
{
    set_header_bg('/images/main.jpg');
}

/**
 * @param string|object $class
 * @return mixed|string
 */
function get_class_name($class)
{
    if (is_object($class)) {
        $path = explode('\\', get_class($class));
    } else {
        $path = explode('\\', $class);
    }

    return array_pop($path);
}

function site_has_error_logs(): bool
{
    return file_exists(ROOT . 'error.log') && filesize(ROOT . 'error.log');
}

function event_logger(): EventLogger
{
    return app()->get(EventLogRepository::class);
}

function log_event(string $type, array $data = [], ?User $user = null)
{
    event_logger()->logEvent($type, $data, $user);
}

function site_name(): string
{
    return config('app.site_name');
}

function flash(): ?array
{
    return Message::flash();
}
