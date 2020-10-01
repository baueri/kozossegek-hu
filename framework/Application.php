<?php


namespace Framework;

use Framework\Bootstrapper\CheckMaintenance;
use Framework\Container\Container;
use Framework\Dispatcher\Dispatcher;
use Framework\Http\View\Bootstrappers\BootDirectives;
use Framework\Support\Config\Config;

class Application extends Container
{
    /**
     * @var static
     */
    protected static $singleton = null;

    /**
     * @var Bootstrapper[]
     */
    protected $bootstrappers = [
        CheckMaintenance::class,
        BootDirectives::class
    ];

    private $locale = LANG;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->singleton(static::class, function() {
            return static::getInstance();
        });

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
     * @param string $key
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
     * @param \Error|\Exception|\Throwable $e
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

    public function down()
    {
        if (!file_exists(ROOT . '.maintenance')) {
            touch(ROOT . '.maintenance');
        }
    }

    public function up()
    {
        if (file_exists(ROOT . '.maintenance')) {
            unlink(ROOT . '.maintenance');
        }
    }
}
