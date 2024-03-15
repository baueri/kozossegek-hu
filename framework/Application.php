<?php

namespace Framework;

use Closure;
use Exception;
use Framework\Container\Container;
use Framework\Database\BootListeners;
use Framework\Database\QueryLog;
use Framework\Enums\Environment;
use Framework\Http\View\Bootstrappers\BootDirectives;
use Framework\Support\Config\Config;
use Throwable;

class Application extends Container
{
    protected static Application $singleton;

    /**
     * @var array<class-string<Bootstrapper>>
     */
    protected array $bootstrappers = [
        BootDirectives::class,
        BootListeners::class,
    ];

    private string $locale;

    private array $events = [
        'booting' => [],
        'booted' => [],
        'terminated' => []
    ];

    /**
     * @throws Exception
     */
    public function __construct(public readonly string $root)
    {
        $this->locale = 'hu';
        $this->singleton(static::class, function () {
            return static::getInstance();
        });

        $this->singleton(QueryLog::class);
        static::$singleton = $this;
    }

    public static function getInstance(): Application
    {
        return static::$singleton;
    }

    public function boot(): void
    {
        $this->runEvents('booting');
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->boot();
        }
        $this->runEvents('booted');
    }

    public function config(string $key = null, $default = null): mixed
    {
        if (!$key) {
            return $this->get(Config::class);
        }

        return $this->get(Config::class)->get($key, $default);
    }

    public function handleError(Throwable $e): void
    {
        $errorHandler = $this->get('errorHandler');

        if (is_callable($errorHandler)) {
            $errorHandler($e);
            return;
        }

        $errorHandler->handle($e);
    }

    /**
     * @phpstan-param class-string<Bootstrapper> $bootstrapper
     */
    public function bootWith($bootstrapper): void
    {
        $this->bootstrappers[] = $bootstrapper;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale($lang): void
    {
        $this->locale = $lang;
    }

    public function envIs(Environment $env): bool
    {
        return $this->getEnvironment() === $env->name;
    }

    public function getEnvironment(): string
    {
        return config('app.environment');
    }

    public function isTest(): bool
    {
        return $this->envIs(Environment::test);
    }

    public function debug(): bool
    {
        return config('app.debug') && !$this->envIs(Environment::production);
    }

    public function on(string $event, Closure $callback): void
    {
        $this->events[$event][] = $callback;
    }

    public function root(string $path = ''): string
    {
        return $this->root . ltrim($path, DS);
    }

    public function pub_path(string $path): string
    {
        return $this->root('public' . DS . ltrim($path, DS));
    }

    public function __destruct()
    {
        $this->runEvents('terminated');
    }

    private function runEvents(string $event): void
    {
        array_walk($this->events[$event], fn ($callback) => $callback());
    }
}
