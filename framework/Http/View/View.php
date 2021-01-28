<?php

namespace Framework\Http\View;

use Exception;
use Framework\Event\EventDisptatcher;
use Framework\Http\View\Exception\ViewNotFoundException;

class View implements ViewInterface
{

    /**
     * @var array
     */
    protected static array $envVariables = [];

    /**
     * @var ViewCache
     */
    private ViewCache $cacheDriver;

    /**
     * @var Section
     */
    private Section $section;

    /**
     * @var ViewParser
     */
    private ViewParser $parser;

    /**
     * View constructor.
     * @param ViewCache $cacheDriver
     * @param Section $section
     * @param ViewParser $parser
     */
    public function __construct(ViewCache $cacheDriver, Section $section, ViewParser $parser)
    {
        $this->cacheDriver = $cacheDriver;
        $this->section = $section;
        $this->parser = $parser;
    }


    /**
     * @param string $view
     * @param array $args
     * @param array $additional_args
     * @return string
     * @throws ViewNotFoundException
     * @throws Exception
     */
    public function view(string $view, array $args = [], array $additional_args = [])
    {
        $filePath = $this->getPath($view);

        if (!file_exists($filePath)) {
            throw new ViewNotFoundException('view file not found: ' . $filePath);
        }

        EventDisptatcher::dispatch(new ViewLoaded($filePath));

        return $this->getContentAndDoCache($filePath, array_merge($additional_args, $args));
    }

    public function exists(string $view): bool
    {
        return file_exists($this->getPath($view));
    }

    /**
     * @param string $view
     * @return string
     *
     */
    public function getPath(string $view): string
    {
        $viewPath = str_replace('.', DS, $view);

        if (strpos($view, ':') !== false) {
            [$dirPath, $viewPath] = explode(':', $viewPath);
            $dirPath = rtrim(config("view.view_sources.{$dirPath}"), DS) . DS;
        } else {
            $dirPath = config('app.views_dir', RESOURCES . 'views' . DS);
        }

        return $dirPath . $viewPath . '.php';
    }

    /**
     * @param $filePath
     * @param $args
     * @return false|string
     * @throws Exception
     */
    protected function getContentAndDoCache($filePath, $args)
    {
        if ($this->cacheDriver->shouldUpdateFile($filePath)) {
            $content = $this->parser->parse(file_get_contents($filePath));
            $this->cacheDriver->cache($filePath, $content);
        }

        $args['__env'] = $this;

        $args = array_merge(static::$envVariables, $args);

        extract($args);

        ob_start();

        include $this->cacheDriver->getCacheFilename($filePath);

        return ob_get_clean();
    }

    /**
     * @return Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param $key
     * @param $value
     */
    public static function setVariable($key, $value)
    {
        static::$envVariables[$key] = $value;
    }

    public static function component($component, $expression)
    {
        return "<?php echo app()->make({$component}::class)->render($expression); ?>";
    }
}
