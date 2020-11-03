<?php


namespace App\Admin\Components\DebugBar;


class DebugBar
{
    /**
     * @var DebugBarTab[]
     */
    private $tabs = [];

    public function __construct(QueryHistoryTab $queryHistoryTab)
    {
        $this->tabs[] = $queryHistoryTab;
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