<?php

declare(strict_types=1);

namespace App\Admin\Components\DebugBar;

class LoadedViewsTab extends DebugBarTab
{
    protected static array $loadedViews = [];

    public static function addView(string $filePath, string $cachedFilePath): void
    {
        static::$loadedViews[] = [substr($filePath, strlen(root()->path())), substr($cachedFilePath, strlen(root()->path()))];
    }

    public function getTitle(): string
    {
        return 'Views';
    }

    public function getBadge(): ?int
    {
        return count(static::$loadedViews);
    }

    public function icon(): string
    {
        return 'fa fa-code';
    }

    public function render(): string
    {
        if (empty(static::$loadedViews)) {
            return '<p class="dbg-empty">No views loaded.</p>';
        }

        // --- flat list ---
        $flatRows = '';
        foreach (static::$loadedViews as [$src, $cached]) {
            $flatRows .= '<tr>'
                . '<td class="dbg-view-src">' . htmlspecialchars($src) . '</td>'
                . '<td class="dbg-view-arrow">→</td>'
                . '<td class="dbg-view-cache">' . htmlspecialchars($cached) . '</td>'
                . '</tr>';
        }

        // --- grouped by source, sorted by load count desc ---
        $groups = [];
        foreach (static::$loadedViews as [$src, $cached]) {
            if (!isset($groups[$src])) {
                $groups[$src] = ['src' => $src, 'cache' => $cached, 'count' => 0];
            }
            $groups[$src]['count']++;
        }
        usort($groups, fn($a, $b) => $b['count'] <=> $a['count']);

        $groupedRows = '';
        foreach ($groups as $item) {
            $badgeClass = $item['count'] > 1 ? 'dbg-views-count--multi' : '';
            $groupedRows .= '<tr>'
                . '<td class="dbg-view-src">' . htmlspecialchars($item['src']) . '</td>'
                . '<td class="dbg-view-arrow">→</td>'
                . '<td class="dbg-view-cache">' . htmlspecialchars($item['cache']) . '</td>'
                . '<td class="dbg-view-count-cell"><span class="dbg-views-count ' . $badgeClass . '">' . $item['count'] . '×</span></td>'
                . '</tr>';
        }

        $total  = count(static::$loadedViews);
        $unique = count($groups);

        return <<<HTML
            <div class="dbg-views-toolbar">
                <span class="dbg-views-stats">
                    <i class="fa fa-layer-group"></i> {$total} loads &middot; {$unique} unique
                </span>
                <div class="dbg-btn-group">
                    <button class="dbg-btn dbg-btn--active" data-dbg-views="flat">
                        <i class="fa fa-list"></i> Flat
                    </button>
                    <button class="dbg-btn" data-dbg-views="grouped">
                        <i class="fa fa-object-group"></i> Grouped
                    </button>
                </div>
            </div>
            <table class="dbg-table dbg-views-table" id="dbg-views-flat">
                <thead><tr><th>Source</th><th></th><th>Compiled</th></tr></thead>
                <tbody>{$flatRows}</tbody>
            </table>
            <table class="dbg-table dbg-views-table" id="dbg-views-grouped" style="display:none">
                <thead><tr><th>Source</th><th></th><th>Compiled</th><th class="dbg-views-count-head">Loads</th></tr></thead>
                <tbody>{$groupedRows}</tbody>
            </table>
            <script>
                (() => {
                    const panel = document.getElementById('dbg-panel-LoadedViewsTab');
                    const flat    = document.getElementById('dbg-views-flat');
                    const grouped = document.getElementById('dbg-views-grouped');
                    const KEY     = 'dbg_views_mode';

                    function switchMode(mode) {
                        flat.style.display    = mode === 'flat'    ? '' : 'none';
                        grouped.style.display = mode === 'grouped' ? '' : 'none';
                        panel.querySelectorAll('[data-dbg-views]').forEach(btn => {
                            btn.classList.toggle('dbg-btn--active', btn.dataset.dbgViews === mode);
                        });
                        localStorage.setItem(KEY, mode);
                    }

                    panel.querySelectorAll('[data-dbg-views]').forEach(btn => {
                        btn.addEventListener('click', () => switchMode(btn.dataset.dbgViews));
                    });

                    const saved = localStorage.getItem(KEY);
                    if (saved === 'grouped') switchMode('grouped');
                })();
            </script>
        HTML;
    }
}
