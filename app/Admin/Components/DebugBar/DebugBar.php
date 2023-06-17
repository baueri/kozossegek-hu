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
            'info' => $frameworkInfoTab,
            'query_history' => $queryHistoryTab,
            'views' => $loadedViewsTab,
            'timeline' => $timeLineTab
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
            $headers[$name] = $tab->generateIcon() . $tab->getTitle();
            $tab_contents[$name] = $tab->render();
        }

        $query_time = $this->tabs['query_history']->getTotalTime() . 's';
        $memory_usage = memory_usage_format();
        $total_load_time = $this->tabs['timeline']->getTotalLoadTime();

        return StringHelper::sanitize(view('admin.partials.debugbar', compact('headers', 'tab_contents', 'query_time', 'memory_usage', 'total_load_time')));
    }

    public function enabled(): bool
    {
        return env('DEBUG') && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'xmlhttprequest';
    }
}
