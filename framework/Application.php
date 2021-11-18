<?php

namespace Framework;

use Error;
use Exception;
use Framework\Container\Container;
use Framework\Database\BootListeners;
use Framework\Database\QueryHistory;
use Framework\Dispatcher\Dispatcher;
use Framework\Http\View\Bootstrappers\BootDirectives;
use Framework\Support\Config\Config;
use Throwable;

class Application extends Container
{
    /**
     * @var Application|null
     */
    protected static ?Application $singleton = null;

    /**
     * @var Bootstrapper[]|string[]
     */
    protected array $bootstrappers = [
        BootDirectives::class,
        BootListeners::class,
    ];

    private string $locale;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->locale = 'hu';
        $this->singleton(static::class, function () {
            return static::getInstance();
        });

        $this->singleton(QueryHistory::class);
        static::$singleton = $this;
    }

    public static function getInstance()
    {
        if (is_null(static::$singleton)) {
            static::$singleton = new static();
        }

        return static::$singleton;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function run(Dispatcher $dispatcher)
    {
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->boot();
        }

        $dispatcher->dispatch();
    }

    /**
     * @param string|null $key
     * @param mixed $default
     * @return Config|mixed
     */
    public function config(string $key = null, $default = null)
    {
        if (!$key) {
            return $this->get(Config::class);
        }

        return $this->get(Config::class)->get($key, $default);
    }

    /**
     * @param Error|Exception|Throwable $e
     */
    public function handleError($e)
    {
        $this->get(Dispatcher::class)->handleError($e);
    }

    public function boot($bootstrapper)
    {
        $this->bootstrappers[] = $bootstrapper;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($lang)
    {
        $this->locale = $lang;
    }

    public function envIs(string $env): bool
    {
        return $this->getEnvironment() === $env;
    }

    public function getEnvironment(): string
    {
        return config('app.environment');
    }

    public function isTest(): bool
    {
        return $this->envIs('test');
    }
}
