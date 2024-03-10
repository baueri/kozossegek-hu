<?php

declare(strict_types=1);

namespace App\Admin\Components\DebugBar;

use Exception;
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
        MileStoneTab $timeLineTab,
        MessageTab $messageTab,
        RequestTab $requestTab
    ) {
        $this->tabs = [
            'info' => $frameworkInfoTab,
            'query_history' => $queryHistoryTab,
            'views' => $loadedViewsTab,
            'timeline' => $timeLineTab,
            'message' => $messageTab,
            'request' => $requestTab
        ];
    }

    /**
     * @throws Exception
     */
    public function render(): string
    {
        if (!$this->enabled()) {
            return '';
        }

        $headers = [];
        $tab_contents = [];
        foreach ($this->tabs as $tab) {
            $name = get_class_name($tab);
            $headers[$name] = "{$tab->generateIcon()}<span class='d-none d-lg-inline'>{$tab->getTitle()}</span>";
            $tab_contents[$name] = $tab->render();
        }

        $query_time = $this->getTab(QueryHistoryTab::class)->getTotalTime() . 's';
        $memory_usage = memory_usage_format();
        $total_load_time = $this->getTab(MileStoneTab::class)->getTotalLoadTime();

        return StringHelper::sanitize(view('admin.partials.debugbar', compact('headers', 'tab_contents', 'query_time', 'memory_usage', 'total_load_time')));
    }

    public function enabled(): bool
    {
        return env('DEBUG') && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'xmlhttprequest';
    }

    /**
     * @phpstan-template T of DebugBarTab
     * @phpstan-param class-string<T> $tabName
     * @phpstan-return T
     * @throws Exception
     */
    public function getTab(string $tabName): DebugBarTab
    {
        foreach ($this->tabs as $tab) {
            if ($tab instanceof $tabName) {
                return $tab;
            }
        }

        throw new Exception("no debugbar tab found: {$tabName}");
    }
}
