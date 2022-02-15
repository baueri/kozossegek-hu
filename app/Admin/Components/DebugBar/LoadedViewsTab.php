<?php

namespace App\Admin\Components\DebugBar;

class LoadedViewsTab extends DebugBarTab
{
    protected static $loadedViews = [];

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
        $views = '<li>' . collect(static::$loadedViews)
                ->map(fn(array $row) => "{$row[0]} --> {$row[1]}")
                ->implode('<li></li>') . '</li>';
        return "<ul style='list-style: none;'>$views</ul>";
    }
}
