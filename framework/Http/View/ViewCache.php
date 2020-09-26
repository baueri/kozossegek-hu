<?php


namespace Framework\Http\View;


class ViewCache
{
    /**
     * @var string
     */
    protected static $cacheDir = CACHE . 'views' . DS;

    /**
     * @param string $fileName
     * @param string $content
     * @return bool|int
     */
    public function cache(string $fileName, ?string $content)
    {
        $this->createDirIfNotExists();

        $content = $content = "<?php //this is the cache file of " . $fileName . " ?>\n" . $content;

        return file_put_contents($this->getCacheFilename($fileName), $content);
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getCacheFilename(string $fileName)
    {
        return static::$cacheDir . md5($fileName) . '.php';
    }

    private function createDirIfNotExists()
    {
        if (!is_dir(static::$cacheDir)) {
            mkdir(static::$cacheDir, 0775, true);
        }
    }

    /**
     * @return bool
     */
    public function shouldUpdateFile($fileName): bool
    {
        $cacheFilePath = $this->getCacheFilename($fileName);

        return config('app.docache') || !file_exists($cacheFilePath) || filemtime($fileName) > filemtime($cacheFilePath);
    }
}