<?php

namespace App\Middleware;

use App\Admin\Components\DebugBar\LoadedViewsTab;
use App\EventListeners\LoadViewToDebugBar;
use Baueri\Mint\View as MintView;
use Framework\Http\View\View;
use Framework\Http\View\ViewLoaded;
use Framework\Middleware\Before;

class DebugBarMiddleware implements Before
{
    public function __construct(
        protected readonly MintView $mint
    ) {
    }

    public function before(): void
    {
        View::setVariable('show_debugbar', app()->debug());
        ViewLoaded::listen(LoadViewToDebugBar::class);
        $this->mint->onBeforeRender(function (string $template, string $compiledPath) {
            LoadedViewsTab::addView($this->mint->viewsPath . '/' . $template, $compiledPath);
        });
    }
}
