<?php

use App\Admin\Components\DebugBar\DebugBar;
use App\Auth\Auth;
use App\Mailable\ThrowableCriticalErrorEmail;
use App\Middleware\AdminMiddleware;
use App\Models\User;
use App\Services\EventLogger;
use Carbon\Carbon;
use Framework\Application;
use Framework\Database\Builder;
use Framework\Database\Database;
use Framework\Database\DatabaseHelper;
use Framework\Database\QueryHistory;
use Framework\Enums\Environment;
use Framework\Http\ApiResponse;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Route\RouteInterface;
use Framework\Http\Route\RouterInterface;
use Framework\Http\Session;
use Framework\Http\View\View;
use Framework\Http\View\ViewInterface;
use Framework\Mail\Mailable;
use Framework\Mail\Mailer;
use Framework\Model\Entity;
use Framework\Model\Model;
use Framework\Support\Collection;
use Framework\Support\StringHelper;
use Framework\Translation\Translator;

/**
 * @return Application|null|mixed
 * @psalm-template T
 * @psalm-param class-string<T> $abstract
 * @psalm-return T|Application
 */
function app(string $abstract = null)
{
    if ($abstract) {
        return Application::getInstance()->get($abstract);
    }

    return Application::getInstance();
}

function is_cli(): bool
{
    return PHP_SAPI == 'cli';
}

function lang(string $key = null, ?string $lang = null): string
{
    $translator = app()->get(Translator::class);

    $lang = $lang ?: getLang();

    return $translator->translate($key, $lang);
}

function lang_f($key, ...$args): string
{
    return app()->get(Translator::class)->setDefaultLang(getLang())->translateF($key, ...$args);
}

function getLang(): string
{
    return app()->getLocale();
}

function db(): Database
{
    return app()->get(Database::class);
}

function builder(?string $table = null): Builder
{
    return app()->get(Builder::class)->from($table);
}

/**
 * @param string $route
 * @param array|string|Model|Entity $args
 * @return string
 */
function route(string $route, mixed $args = null): string
{
    return resolve(RouterInterface::class)->route($route, $args);
}

function route_is(string $routeName): bool
{
    return current_route()->getAs() === $routeName;
}

function redirect(string $uri): never
{
    if ($uri === 'self') {
        $uri = request()->uri;
    }
    header("Location: $uri");
    exit;
}

function redirect_route(string $route, $args = null): never
{
    redirect(route($route, $args));
}

function collect($values = []): Collection
{
    return new Collection($values);
}

function collect_file($file): Collection
{
    return collect(array_filter(explode(PHP_EOL, file_get_contents($file))));
}

function now($tz = null): Carbon
{
    return Carbon::now($tz);
}

function view(string $view, array $args = []): string
{
    return app()->get(ViewInterface::class)->view($view, $args);
}

function view_path(string $view): string
{
    return View::getPath($view);
}

function current_route(): RouteInterface
{
    return app()->get(RouterInterface::class)->getCurrentRoute();
}

function get_site_url(): string
{
    return config('app.site_url');
}

function config($key, $default = null)
{
    return app()->config($key, $default);
}

function make($abstraction, $values = [])
{
    return app()->make($abstraction, ...$values);
}

function is_prod(): bool
{
    return app()->envIs(Environment::production);
}

function debugbar(): DebugBar
{
    return app()->get(DebugBar::class);
}

function is_home(): bool
{
    return !trim(app()->get(Request::class)->uri, '/');
}

function request(): Request
{
    return app()->get(Request::class);
}

function is_admin(): bool
{
    return in_array(AdminMiddleware::class, current_route()->getMiddleware());
}

function mb_ucfirst($string, $encoding = 'utf-8'): string
{
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, null, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}

function raise_error_page(int $code, string $message = '', string $message2 = ''): never
{
    echo view('portal.error', compact('code', 'message', 'message2'));
    exit();
}

function raise_500(string $message = ''): never
{
    raise_error_page(500, $message);
}

function raise_404($message = 'A keresett oldal nem található', $message2 = '<i class="text-muted">De azért ne adjátok fel.<br/> Keressetek, és előbb, vagy utóbb találtok ;-)</i>'): never
{
    raise_error_page(404, $message, $message2);
}

function raise_403($message = '', $message2 = 'Nincs jogosultsága a tartalom megtekintéséhez!'): never
{
    raise_error_page(403, $message, $message2);
}

function process_error($e): void
{
    if (!env('DEBUG')) {
        report($e);
    } else {
        dd($e, request()->all(), request()->route);
    }
}

function is_loggedin(): bool
{
    return Auth::loggedIn();
}

function rrmdir($dir): bool
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

// Copy files and non-empty directories
function rcopy($src, $dst, $excludeSymlinks = false): void
{
    if ($excludeSymlinks && is_link($src)) {
        return;
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
    }
    if (file_exists($src)) {
        copy($src, $dst);
    }
}

function set_header_bg(string $bg): void
{
    View::setVariable('header_background', $bg);
}

function use_default_header_bg(): void
{
    set_header_bg('/images/main.webp');
}

function get_class_name(string|object $class): string
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
    return app()->get(EventLogger::class);
}

function log_event(string $type, array $data = [], ?User $user = null): void
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

function report($exception): void
{
    if ($exception instanceof Throwable && $exception->getCode() == '404') {
        return;
    }
    $mailer = new Mailer();
    $mailer->to(config('app.error_email'));

    if ($exception instanceof Throwable) {
        $mail = new ThrowableCriticalErrorEmail($exception);
    } else {
        $mail = new Mailable();
        $mail->setMessage($exception)->subject(get_site_url() . ' - report');
    }

    $mailer->send($mail);
}

/**
 * @phpstan-template T
 * @phpstan class-string<T> $class
 * @phpstan-return T
 */
function resolve($class, $args = null)
{
    return app()->get($class, $args);
}

function tap($value, $callback)
{
    $callback($value);
    return $value;
}

function selected($expression): string
{
    return attr('selected')($expression);
}

function attr(string $name): Closure
{
    return fn ($expression) => $expression ? $name : '';
}

function query_history(): Collection
{
    return resolve(QueryHistory::class)->queryHistory;
}

function query_history_bound(): array
{
    return query_history()->map(function ($query) {
        return DatabaseHelper::getQueryWithBindings($query[0], $query[1]);
    })->all();
}

function namespace_split(string $class): array
{
    $classPos = strrpos($class, '\\');
    if ($classPos === false) {
        return ['', $class];
    }

    return [substr($class, 0, $classPos), substr($class, $classPos + 1)];
}

function csrf_token(): string
{
    return Session::token();
}

function memory_usage_format(): string
{
    $conv = fn ($size) => @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.array('b','kb','mb','gb','tb','pb')[$i];
    return $conv(memory_get_usage());
}

function enum_val(UnitEnum $enum) {
    return $enum instanceof BackedEnum ? $enum->value : $enum->name;
}

function class_uses_trait($object_or_class, string $trait): bool
{
    return in_array($trait, class_uses_recursive($object_or_class));
}

function class_uses_recursive($object_or_class): array
{
    $uses = [];
    foreach (array_reverse(class_parents($object_or_class)) as $class) {
        $uses = array_merge($uses, class_uses($class));
    }

    $className = is_object($object_or_class) ? get_class($object_or_class) : $object_or_class;
    $uses = array_merge($uses, class_uses($className));

    return array_unique($uses);
}

function diff(array|Collection $old, array|Collection $new): array
{
    $diff = [];

    foreach ($old as $key => $value) {
        if ($value != (string) $new[$key]) {
            $diff[$key] = ['old' => $value, 'new' => (string) $new[$key]];
        }
    }

    return $diff;
}

function str_more(string $text, int $numberOfWords, string $moreText = ''): string
{
    return StringHelper::more($text, $numberOfWords, $moreText);
}