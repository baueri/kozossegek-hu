<?php


namespace Framework\Support\DataFile;


abstract class DataFile
{
    const ROOT = ROOT;

    /**
     * @var null|string
     */
    protected static $basePath = null;

    /**
     * @var null|string
     */
    protected static $extension = null;

    /**
     * @var []
     */
    protected $items;

    /**
     * DataFile constructor.
     * @param string|null $fileName
     */
    public function __construct(string $fileName = null)
    {
        if ($fileName) {
            $this->load($fileName);
        }
    }

    public function get($key, $default = null)
    {
        [$baseName, $index] = static::parseKey($key);

        $fileName = static::getFileName($baseName);

        $this->load($baseName);

        return static::getValue($this->items[$fileName], $index, $default);
    }

    /**
     *
     * @param string $params
     * @return array
     */
    protected static function parseKey($params)
    {
        $parsed = explode('.', $params, 2);
        if (!isset($parsed[1])) {
            $parsed[] = null;
        }
        return $parsed;
    }

    /**
     * @param string $baseName
     * @return string
     */
    protected static function getFileName($baseName)
    {
        $scope = '';

        if (strpos($baseName, '::') !== false) {
            $scope = substr($baseName, 0, strpos($baseName, '::')) . DS;
            $baseName = substr($baseName, strpos($baseName, '::') + 2);
        }

        return static::getDir($scope) . $baseName . (static::$extension ? '.' . static::$extension : '');
    }

    /**
     * @param $scope
     * @return string
     */
    protected static function getDir($scope)
    {
        return self::ROOT . $scope . static::$basePath;
    }

    abstract protected function parse($content);

    /**
     * @param $filename
     * @return false|string
     */
    protected static function getContent($filename)
    {
        return file_get_contents($filename);
    }

    /**
     * @param array $items
     * @param mixed $index
     * @param mixed $default
     * @return mixed
     */
    private static function getValue($items, $index = null, $default = null)
    {
        if (!$index) {
            return $items ?: $default;
        }

        if (isset($items[$index])) {
            return $items[$index];
        }

        $nestedKeys = explode('.', $index);

        foreach ($nestedKeys as $key) {
            if (isset($items[$key])) {
                $items = $items[$key];
            } else {
                $items = null;
            }
        }

        return $items ?: $default;
    }

    /**
     * @param $baseName
     * @return $this
     */
    public function load($baseName)
    {
        $fileName = static::getFileName($baseName);
        if (!isset($this->items[$fileName])) {
            $this->items[$fileName] = static::parse(static::getContent($fileName));
        }

        return $this;
    }

    public function __toString()
    {
        return json_encode($this->items);
    }
}