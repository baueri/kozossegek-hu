<?php

namespace App\Admin\Components\DebugBar;

class LoadedViewsTab extends DebugBarTab
{
    protected static array $loadedViews = [];

    public static function addView(string $filePath, string $cachedFilePath)
    {
        static::$loadedViews[] = [substr($filePath, strlen(ROOT)), basename($cachedFilePath)];
    }

    public function getName(): string
    {
        return 'betöltött template-ek (' . count(static::$loadedViews) . ')';
    }

    public function render(): string
    {
        return collect(static::$loadedViews)
            ->map(fn(array $row) => "<code>{$row[0]}</code> --> {$row[1]}<br/>")
            ->implode('');
    }
}
