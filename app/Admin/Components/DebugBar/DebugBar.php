<?php

namespace App\Admin\Components\DebugBar;

use Framework\Support\StringHelper;

class DebugBar
{
    /**
     * @var DebugBarTab[]
     */
    public readonly array $tabs;

    public function __construct(
        FrameworkInfoTab $frameworkInfoTab,
        QueryHistoryTab $queryHistoryTab,
        LoadedViewsTab $loadedViewsTab,
        MileStoneTab $timeLineTab
    ) {
        $this->tabs = [
            FrameworkInfoTab::class => $frameworkInfoTab,
            QueryHistoryTab::class => $queryHistoryTab,
            LoadedViewsTab::class => $loadedViewsTab,
            MileStoneTab::class => $timeLineTab
        ];
    }

    public function render(): string
    {
        if (!$this->enabled()) {
            return '';
        }

        $headers = [];
        $tab_contents = [];
        foreach ($this->tabs as $tab) {
            $name = get_class_name($tab);
            $headers[$name] = $tab->getName();
            $tab_contents[$name] = $tab->render();
        }

        return StringHelper::sanitize(view('admin.partials.debugbar', compact('headers', 'tab_contents')));
    }

    public function enabled(): bool
    {
        return _env('DEBUG') && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'xmlhttprequest';
    }
}
