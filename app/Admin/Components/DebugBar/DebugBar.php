<?php


namespace App\Admin\Components\DebugBar;


class DebugBar
{
    /**
     * @var DebugBarTab[]
     */
    private $tabs = [];

    public function __construct(QueryHistoryTab $queryHistoryTab, ErrorTab $errorTab, LoadedViewsTab $loadedViewsTab)
    {
        $this->tabs = [
            $queryHistoryTab,
            $loadedViewsTab,
            $errorTab,
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
            $name = get_class($tab);
            $headers[$name] = $tab->getName();
            $tab_contents[$name] = $tab->render();
        }

        return view('admin.partials.debugbar', compact('headers', 'tab_contents'));
    }

}