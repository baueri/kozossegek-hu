<?php

namespace App\Admin\Components\DebugBar;

use App\Services\MileStone;

class MileStoneTab extends DebugBarTab
{
    private ?float $totalLoadTime = null;

    public function getTitle(): string
    {
        return 'Timeline';
    }

    public function icon(): string
    {
        return 'fa fa-stream';
    }

    public function render(): string
    {
        $measures = MileStone::get();
        $total = $this->getTotalLoadTime();
        $out = <<<EOT
            <style>#debug-timeline tr td:first-child {text-align: right; padding: 2px 5px;}</style>
            <code>
            <table id="debug-timeline" class="">
                <tr><td><b>Total load time:</b></td><td>{$total}ms</td></tr>
        EOT;

        /** @var array{title: string, start: float, end: float} $measure */
        foreach ($measures as $name => $measure) {
            $time = $this->roundTime(($measure['end'] ?? microtime(true)) - $measure['start']);
            $title = $measure['title'] ?: $name;
            $out .= "<tr><td><b>{$title}</b>:</td><td>{$time}ms</td></tr>";
        }

        return "{$out}</table></code>";
    }

    public function getTotalLoadTime(): ?float
    {
        if ($this->totalLoadTime) {
            return $this->totalLoadTime;
        }
        $measures = MileStone::get();
        return $this->totalLoadTime =  $this->roundTime((float) microtime(true) - $measures[key($measures)]['start']);
    }

    private function roundTime($time): float
    {
        return round(($time) * 1000, 2);
    }
}