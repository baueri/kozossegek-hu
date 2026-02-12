<?php

namespace Framework\Http\View;

class ViewCache
{
    protected static string $cacheDir = CACHE . 'views' . DS;

    public function cache(string $fileName, ?string $content): bool
    {
        $cachedFileName = $this->getCacheFilename($fileName);
        $fileName = substr($fileName, strlen(ROOT));
        $this->createDirIfNotExists($cachedFileName);

        $content = "<?php /** this is the cache file of @see {$fileName} */ ?>\n" . $content;

        return file_put_contents($cachedFileName, $content) !== false;
    }

    public function getCacheFilename(string $fileName): string
    {
        $hashedFilename = hash('SHA256', $fileName);
        return static::$cacheDir . substr($hashedFilename, 0, 2) . DS . $hashedFilename . '.php';
    }

    private function createDirIfNotExists($cachedFileName): void
    {
        if (!is_dir(dirname($cachedFileName))) {
            mkdir(dirname($cachedFileName), 0775, true);
        }
    }

    public function shouldUpdateFile(string $fileName): bool
    {
        $cacheFilePath = $this->getCacheFilename($fileName);

        return config('app.docache') || !file_exists($cacheFilePath) || filemtime($fileName) > filemtime($cacheFilePath);
    }
}
