<?php

declare(strict_types=1);

namespace App\Admin\Components\DebugBar;

class LoadedViewsTab extends DebugBarTab
{
    protected static array $loadedViews = [];

    public static function addView(string $filePath, string $cachedFilePath): void
    {
        static::$loadedViews[] = [substr($filePath, strlen(app()->root())), basename($cachedFilePath)];
    }

    public function getTitle(): string
    {
        return 'betöltött template-ek (' . count(static::$loadedViews) . ')';
    }

    public function icon(): string
    {
        return 'fa fa-code';
    }

    public function render(): string
    {
        return collect(static::$loadedViews)
            ->map(fn(array $row) => "<code>{$row[0]}</code> --> {$row[1]}<br/>")
            ->implode('');
    }
}
