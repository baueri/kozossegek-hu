<?php


namespace App\EventListeners;


use App\Admin\Components\DebugBar\LoadedViewsTab;
use Framework\Event\EventListener;
use Framework\Http\View\ViewLoaded;

class LoadViewToDebugBar implements EventListener
{
    /**
     * @param ViewLoaded $event
     */
    public function trigger($event)
    {
        if ($event->filePath) {
            LoadedViewsTab::addView($event->filePath);
        }
    }
}