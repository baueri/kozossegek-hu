<?php


namespace App\Admin\Components\DebugBar;


class LoadedViewsTab extends DebugBarTab
{
    protected static $loadedViews = [];

    public static function addView($filePath)
    {
        static::$loadedViews[] = substr($filePath, strlen(ROOT));
    }

    public function getName()
    {
        return 'betöltött template-ek (' . count(static::$loadedViews) . ')';
    }

    public function render()
    {
        $views = '<li>' . collect(static::$loadedViews)->implode('<li></li>') . '</li>';
        return "<ul style='list-style: none;'>$views</ul>";
    }
}