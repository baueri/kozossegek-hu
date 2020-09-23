<?php


namespace Framework;

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
        $this->boot();
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
     * @param \Exception $e
     */
    public function handleError(\Exception $e)
    {
        $this->get(Dispatcher::class)->handleError($e);
    }

    private function boot()
    {
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->boot();
        }
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($lang)
    {
        $this->locale = $lang;
    }

    public function up()
    {
        touch('');
    }

    public function down()
    {

    }
}