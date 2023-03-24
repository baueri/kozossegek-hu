<?php

namespace App\Admin\Components\DebugBar;

use App\Services\MileStone;

class MileStoneTab extends DebugBarTab
{
    public function getName(): string
    {
        return 'Timeline';
    }

    public function render(): string
    {
        $measures = MileStone::get();
        $total = microtime(true) - $measures[key($measures)]['start'];
        $out = <<<EOT
            <style>#debug-timeline tr td:first-child {text-align: right; padding: 2px 5px;}</style>
            <code>
            <table id="debug-timeline" class="">
                <tr><td><b>Total load time:</b></td><td>{$this->roundTime($total)}ms</td></tr>
        EOT;

        /** @var array{title: string, start: float, end: float} $measure */
        foreach ($measures as $name => $measure) {
            $time = $this->roundTime($measure['end'] ?? microtime(true) - $measure['start']);
            $title = $measure['title'] ?: $name;
            $out .= "<tr><td><b>{$title}</b>:</td><td>{$time}ms</td></tr>";
        }

        return "{$out}</table></code>";
    }

    private function roundTime($time): float
    {
        return round(($time) * 1000, 2);
    }
}