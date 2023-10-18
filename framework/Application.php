<?php

namespace Framework;

use Closure;
use Error;
use Exception;
use Framework\Container\Container;
use Framework\Database\BootListeners;
use Framework\Database\QueryHistory;
use Framework\Dispatcher\Dispatcher;
use Framework\Enums\Environment;
use Framework\Http\View\Bootstrappers\BootDirectives;
use Framework\Support\Config\Config;
use Throwable;

class Application extends Container
{
    protected static Application $singleton;

    /**
     * @var Bootstrapper[]|string[]
     */
    protected array $bootstrappers = [
        BootDirectives::class,
        BootListeners::class,
    ];

    private string $locale;

    private array $eventCallbacks = [
        'booting' => [],
        'booted' => [],
        'terminated' => []
    ];

    /**
     * @throws Exception
     */
    public function __construct(public readonly string $root)
    {
        $this->locale = env('APP_LOCALE', 'en');
        $this->singleton(static::class, function () {
            return static::getInstance();
        });

        $this->singleton(QueryHistory::class);
        static::$singleton = $this;
    }

    public static function getInstance(): Application
    {
        return static::$singleton ??= new static();
    }

    public function run(Dispatcher $dispatcher)
    {
        $this->runEvents('booting');
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->boot();
        }
        $this->runEvents('booted');

        $dispatcher->dispatch();
    }

    public function config(string $key = null, $default = null): mixed
    {
        if (!$key) {
            return $this->get(Config::class);
        }

        return $this->get(Config::class)->get($key, $default);
    }

    /**
     * @param Error|Exception|Throwable $e
     */
    public function handleError($e): void
    {
        $this->get(Dispatcher::class)->handleError($e);
    }

    public function boot($bootstrapper): void
    {
        $this->bootstrappers[] = $bootstrapper;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale($lang)
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

    public function on(string $event, Closure $callback)
    {
        $this->eventCallbacks[$event][] = $callback;
    }

    public function __destruct()
    {
        $this->runEvents('terminated');
    }

    private function runEvents(string $event): void
    {
        array_walk($this->eventCallbacks[$event], fn ($callback) => $callback());
    }
}
