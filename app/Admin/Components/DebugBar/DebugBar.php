<?php

namespace App\Admin\Components\DebugBar;

use Framework\Support\StringHelper;
use InvalidArgumentException;

class DebugBar
{
    /**
     * @var DebugBarTab[]
     */
    private array $tabs;

    public function __construct(FrameworkInfoTab $frameworkInfoTab, QueryHistoryTab $queryHistoryTab, LoadedViewsTab $loadedViewsTab)
    {
        $this->tabs = [
            $frameworkInfoTab,
            $queryHistoryTab,
            $loadedViewsTab,
        ];
    }

    public function tab(string $tabClassName): DebugBarTab
    {
        foreach ($this->tabs as $tab) {
            if (get_class($tab) == $tabClassName) {
                return $tab;
            }
        }

        throw new InvalidArgumentException("tab $tabClassName does not exists");
    }

    public function render(): string
    {
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
        return (bool) _env('DEBUG');
    }
}
