<?php

namespace App\Admin\Components\DebugBar;

use Framework\Support\StringHelper;

class DebugBar
{
    /**
     * @var DebugBarTab[]
     */
    private $tabs = [];

    public function __construct(FrameworkInfoTab $frameworkInfoTab, QueryHistoryTab $queryHistoryTab, LoadedViewsTab $loadedViewsTab)
    {
        $this->tabs = [
            $frameworkInfoTab,
            $queryHistoryTab,
            $loadedViewsTab,
        ];
    }

    /**
     * @param $tabClassName
     * @return DebugBarTab
     */
    public function tab($tabClassName)
    {
        foreach ($this->tabs as $tab) {
            if (get_class($tab) == $tabClassName) {
                return $tab;
            }
        }

        throw new \InvalidArgumentException("tab $tabClassName does not exists");
    }

    public function render()
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
}
