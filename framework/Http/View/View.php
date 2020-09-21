<?php


namespace Framework\Http\View;


use Exception;
use Framework\Http\View\Exception\ViewNotFoundException;

class View
{

    /**
     * @var array
     */
    protected static $envVariables = [];

    /**
     * @var ViewCache
     */
    private $cacheDriver;

    /**
     * @var Section
     */
    private $section;

    /**
     * View constructor.
     * @param ViewCache $cacheDriver
     * @param Section $section
     */
    public function __construct(ViewCache $cacheDriver, Section $section)
    {
        $this->cacheDriver = $cacheDriver;
        $this->section = $section;
    }


    /**
     * @param string $view
     * @param array $args
     * @return string
     * @throws ViewNotFoundException
     * @throws Exception
     */
    public function view(string $view, array $args = [])
    {
        $filePath = $this->getPath($view);

        if (!file_exists($filePath)) {
            throw new ViewNotFoundException('view file not found: ' . $filePath);
        }

        return $this->getContentAndDoCache($filePath, $args);
    }

    /**
     * @param string $view
     * @return string
     *
     */
    public function getPath(string $view): string
    {
        $viewPath = str_replace('.', DS, $view);

        if (strpos($view, '::') !== false) {
            list($dirPath, $viewPath) = explode('::', $viewPath);
            $dirPath = APP . $dirPath . DS . 'Views' . DS;

        } else {
            $dirPath = app()->config('app.views_dir', RESOURCES . 'views' . DS);
        };

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
            $content = ViewParser::parse(file_get_contents($filePath));
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
     * @param $name
     * @param $value
     */
    public static function addGlobalVariable($name, $value)
    {
        static::$envVariables[$name] = $value;
    }

    /**
     * @return Sectionv
     */
    public function getSection()
    {
        return $this->section;
    }
}