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
        $total = $this->getTotalLoadTime() ?: 1;

        $rows = '<tr><td class="dbg-tl-label"><strong>Total</strong></td>'
            . '<td class="dbg-tl-bar"><div class="dbg-bar-wrap"><div class="dbg-bar-fill" style="width:100%"></div></div></td>'
            . '<td class="dbg-tl-time"><strong>' . $total . 'ms</strong></td></tr>';

        foreach ($measures as $name => $measure) {
            $time = $this->roundTime(($measure['end'] ?? microtime(true)) - $measure['start']);
            $pct = min(100, round($time / $total * 100));
            $title = htmlspecialchars($measure['title'] ?: $name);

            $barClass = match (true) {
                $pct >= 60 => 'dbg-bar-danger',
                $pct >= 30 => 'dbg-bar-warning',
                default    => 'dbg-bar-ok',
            };

            $rows .= '<tr>'
                . "<td class=\"dbg-tl-label\">{$title}</td>"
                . "<td class=\"dbg-tl-bar\"><div class=\"dbg-bar-wrap\"><div class=\"dbg-bar-fill {$barClass}\" style=\"width:{$pct}%\"></div></div></td>"
                . "<td class=\"dbg-tl-time\">{$time}ms</td>"
                . '</tr>';
        }

        return '<table class="dbg-table dbg-timeline">'
            . '<thead><tr><th style="width:160px">Milestone</th><th>Timeline</th><th style="width:80px">Time</th></tr></thead>'
            . '<tbody>' . $rows . '</tbody></table>';
    }

    public function getTotalLoadTime(): ?float
    {
        if ($this->totalLoadTime) {
            return $this->totalLoadTime;
        }
        $measures = MileStone::get();
        return $this->totalLoadTime = $this->roundTime((float) microtime(true) - $measures[key($measures)]['start']);
    }

    private function roundTime(float $time): float
    {
        return round($time * 1000, 2);
    }
}
