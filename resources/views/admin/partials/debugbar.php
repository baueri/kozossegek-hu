<div id="dbg">
    <div id="dbg-bar">
        <div id="dbg-resize"></div>
        <div id="dbg-header">
            <nav id="dbg-tabs">
                @foreach($tabs as $id => $tab)
                    <button class="dbg-tab-btn" data-tab="{{ $id }}" title="{{ strip_tags($tab['title']) }}">
                        {{ $tab['icon'] }}
                        <span class="dbg-tab-title">{{ $tab['title'] }}</span>
                        @if($tab['badge'] !== null)
                            <span class="dbg-badge-count">{{ $tab['badge'] }}</span>
                        @endif
                    </button>
                @endforeach
            </nav>
            <div id="dbg-stats">
                <span title="Memory usage"><i class="fa fa-microchip"></i> {{ $memory_usage }}</span>
                <span title="Total query time"><i class="fa fa-database"></i> {{ $query_time }}</span>
                <span title="Page load time"><i class="fa fa-stopwatch"></i> {{ $total_load_time }}ms</span>
                <button id="dbg-close" title="Minimise">&times;</button>
            </div>
        </div>
        <div id="dbg-content">
            @foreach($tabs as $id => $tab)
                <div class="dbg-panel" id="dbg-panel-{{ $id }}">
                    {{ $tab['content'] }}
                </div>
            @endforeach
        </div>
    </div>
    <button id="dbg-pill" title="Open debugbar">
        <i class="fa fa-terminal"></i>
        <span>Debug</span>
    </button>
</div>
<style>
    #dbg {
        --dbg-bg:        #0d1117;
        --dbg-header-bg: #161b22;
        --dbg-border:    #30363d;
        --dbg-text:      #c9d1d9;
        --dbg-muted:     #8b949e;
        --dbg-accent:    #58a6ff;
        --dbg-active-bg: #21262d;
        --dbg-hover:     #1c2128;
        --dbg-success:   #3fb950;
        --dbg-warning:   #d29922;
        --dbg-danger:    #f85149;
        --dbg-code-bg:   #161b22;
        --dbg-font:      'JetBrains Mono','Fira Code','Cascadia Code',Consolas,'Courier New',monospace;

        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 99999;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 12px;
        line-height: 1.5;
        color: var(--dbg-text);
    }

    /* ── bar ─────────────────────────────────────── */
    #dbg-bar {
        background: var(--dbg-bg);
        border-top: 1px solid var(--dbg-border);
        box-shadow: 0 -4px 24px rgba(0,0,0,0.5);
    }

    #dbg.dbg-collapsed #dbg-bar { display: none; }

    /* ── resize handle ───────────────────────────── */
    #dbg-resize {
        height: 4px;
        cursor: ns-resize;
        background: transparent;
        transition: background 0.15s;
    }
    #dbg-resize:hover { background: var(--dbg-accent); }

    /* ── header ──────────────────────────────────── */
    #dbg-header {
        display: flex;
        align-items: stretch;
        background: var(--dbg-header-bg);
        border-bottom: 1px solid var(--dbg-border);
        height: 36px;
        overflow: hidden;
    }

    /* ── tabs ────────────────────────────────────── */
    #dbg-tabs {
        display: flex;
        align-items: stretch;
        flex: 1;
        overflow-x: auto;
        scrollbar-width: none;
    }
    #dbg-tabs::-webkit-scrollbar { display: none; }

    .dbg-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0 14px;
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        color: var(--dbg-muted);
        cursor: pointer;
        font-size: 11px;
        font-family: inherit;
        white-space: nowrap;
        transition: color 0.15s, border-color 0.15s, background 0.15s;
    }
    .dbg-tab-btn:hover {
        color: var(--dbg-text);
        background: var(--dbg-hover);
    }
    .dbg-tab-btn.active {
        color: var(--dbg-accent);
        border-bottom-color: var(--dbg-accent);
        background: var(--dbg-active-bg);
    }
    .dbg-tab-btn i { font-size: 11px; }
    .dbg-tab-title { display: none; }
    @media (min-width: 768px) { .dbg-tab-title { display: inline; } }

    .dbg-badge-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 16px;
        height: 16px;
        padding: 0 4px;
        border-radius: 8px;
        background: var(--dbg-active-bg);
        color: var(--dbg-muted);
        font-size: 10px;
        font-weight: 600;
        border: 1px solid var(--dbg-border);
    }
    .dbg-tab-btn.active .dbg-badge-count {
        background: rgba(88,166,255,0.15);
        color: var(--dbg-accent);
        border-color: rgba(88,166,255,0.3);
    }

    /* ── stats bar ───────────────────────────────── */
    #dbg-stats {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 12px;
        border-left: 1px solid var(--dbg-border);
        color: var(--dbg-muted);
        font-size: 11px;
        white-space: nowrap;
    }
    #dbg-stats span { display: flex; align-items: center; gap: 4px; }
    #dbg-stats i { font-size: 10px; }
    #dbg-close {
        background: none;
        border: none;
        color: var(--dbg-muted);
        cursor: pointer;
        font-size: 16px;
        line-height: 1;
        padding: 2px 4px;
        border-radius: 3px;
        transition: color 0.15s, background 0.15s;
    }
    #dbg-close:hover { color: var(--dbg-danger); background: rgba(248,81,73,0.1); }

    /* ── content ─────────────────────────────────── */
    #dbg-content {
        overflow-y: auto;
        height: 280px;
        min-height: 60px;
    }

    .dbg-panel {
        display: none;
        padding: 12px 16px;
    }
    .dbg-panel.active { display: block; }

    /* ── pill (collapsed trigger) ────────────────── */
    #dbg-pill {
        display: none;
        position: fixed;
        bottom: 8px;
        right: 16px;
        background: var(--dbg-header-bg);
        border: 1px solid var(--dbg-border);
        color: var(--dbg-muted);
        border-radius: 20px;
        padding: 5px 14px;
        cursor: pointer;
        font-size: 11px;
        font-family: inherit;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.5);
        transition: background 0.15s, color 0.15s;
        z-index: 99999;
    }
    #dbg-pill:hover { background: var(--dbg-active-bg); color: var(--dbg-text); }
    #dbg.dbg-collapsed #dbg-pill { display: inline-flex; }

    /* ────────────────────────────────────────────── */
    /*  Shared content styles                         */
    /* ────────────────────────────────────────────── */

    #dbg * { box-sizing: border-box; }

    #dbg a { color: var(--dbg-accent); text-decoration: none; }
    #dbg a:hover { text-decoration: underline; }

    .dbg-empty {
        color: var(--dbg-muted);
        font-style: italic;
        margin: 0;
        padding: 8px 0;
    }

    /* key-value list */
    .dbg-kv { display: table; border-collapse: collapse; width: 100%; margin: 0; padding: 0; }
    .dbg-kv > div { display: table-row; }
    .dbg-kv > div:hover { background: var(--dbg-hover); }
    .dbg-kv dt, .dbg-kv dd {
        display: table-cell;
        padding: 4px 8px;
        border-bottom: 1px solid var(--dbg-border);
        vertical-align: top;
    }
    .dbg-kv dt {
        font-weight: 600;
        color: var(--dbg-muted);
        width: 140px;
        white-space: nowrap;
    }
    .dbg-kv dd {
        font-family: var(--dbg-font);
        color: var(--dbg-text);
        word-break: break-all;
        margin: 0;
    }

    /* tables */
    .dbg-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }
    .dbg-table th {
        text-align: left;
        padding: 4px 8px;
        font-weight: 600;
        color: var(--dbg-muted);
        border-bottom: 1px solid var(--dbg-border);
        background: var(--dbg-code-bg);
        position: sticky;
        top: 0;
    }
    .dbg-table td {
        padding: 5px 8px;
        border-bottom: 1px solid rgba(48,54,61,0.5);
        vertical-align: top;
    }
    .dbg-table tr:hover td { background: var(--dbg-hover); }
    .dbg-table tr:last-child td { border-bottom: none; }

    /* views table */
    .dbg-views-table .dbg-view-src { font-family: var(--dbg-font); color: var(--dbg-text); }
    .dbg-views-table .dbg-view-arrow { color: var(--dbg-muted); padding: 5px 4px; width: 20px; }
    .dbg-views-table .dbg-view-cache { font-family: var(--dbg-font); color: var(--dbg-muted); }

    /* timeline */
    .dbg-timeline .dbg-tl-label { color: var(--dbg-text); width: 160px; white-space: nowrap; font-family: var(--dbg-font); }
    .dbg-timeline .dbg-tl-time { color: var(--dbg-muted); width: 70px; text-align: right; font-family: var(--dbg-font); }
    .dbg-tl-bar { width: 100%; padding: 5px 8px; }
    .dbg-bar-wrap { background: var(--dbg-active-bg); border-radius: 3px; height: 8px; overflow: hidden; }
    .dbg-bar-fill { height: 100%; border-radius: 3px; background: var(--dbg-accent); transition: width 0.4s ease; }
    .dbg-bar-fill.dbg-bar-warning { background: var(--dbg-warning); }
    .dbg-bar-fill.dbg-bar-danger  { background: var(--dbg-danger); }
    .dbg-bar-fill.dbg-bar-ok      { background: var(--dbg-success); }

    /* badges */
    .dbg-badge {
        display: inline-block;
        padding: 1px 7px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .dbg-badge-success { background: rgba(63,185,80,0.15); color: var(--dbg-success); border: 1px solid rgba(63,185,80,0.3); }
    .dbg-badge-warning { background: rgba(210,153,34,0.15); color: var(--dbg-warning); border: 1px solid rgba(210,153,34,0.3); }
    .dbg-badge-danger  { background: rgba(248,81,73,0.15);  color: var(--dbg-danger);  border: 1px solid rgba(248,81,73,0.3); }
    .dbg-badge-accent  { background: rgba(88,166,255,0.15); color: var(--dbg-accent);  border: 1px solid rgba(88,166,255,0.3); }
    .dbg-badge-muted   { background: var(--dbg-active-bg);  color: var(--dbg-muted);   border: 1px solid var(--dbg-border); }

    /* details/summary */
    .dbg-details {
        border: 1px solid var(--dbg-border);
        border-radius: 4px;
        margin-bottom: 6px;
        overflow: hidden;
    }
    .dbg-details summary {
        padding: 6px 10px;
        cursor: pointer;
        background: var(--dbg-code-bg);
        color: var(--dbg-muted);
        font-weight: 600;
        list-style: none;
        user-select: none;
        transition: background 0.15s;
    }
    .dbg-details summary:hover { background: var(--dbg-hover); color: var(--dbg-text); }
    .dbg-details summary::-webkit-details-marker { display: none; }
    .dbg-details summary::before { content: '▶ '; font-size: 9px; }
    .dbg-details[open] summary::before { content: '▼ '; }

    /* pre */
    .dbg-pre {
        margin: 0;
        padding: 10px;
        font-family: var(--dbg-font);
        font-size: 11px;
        color: var(--dbg-text);
        background: var(--dbg-bg);
        overflow-x: auto;
        white-space: pre;
    }

    /* messages */
    .dbg-message-list { list-style: none; margin: 0; padding: 0; }
    .dbg-message-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 6px 0;
        border-bottom: 1px solid var(--dbg-border);
        font-family: var(--dbg-font);
    }
    .dbg-message-item:last-child { border-bottom: none; }
    .dbg-msg-dot { color: var(--dbg-accent); font-size: 6px; margin-top: 5px; flex-shrink: 0; }

    /* sql keyword highlight */
    .dbg-sql-kw { color: #ff7b72; font-weight: 700; }

    /* views toolbar */
    .dbg-views-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        gap: 12px;
    }
    .dbg-views-stats {
        color: var(--dbg-muted);
        font-size: 11px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .dbg-views-stats i { font-size: 10px; }

    /* button group */
    .dbg-btn-group { display: inline-flex; border-radius: 5px; overflow: hidden; border: 1px solid var(--dbg-border); }
    .dbg-btn {
        background: var(--dbg-code-bg);
        border: none;
        border-right: 1px solid var(--dbg-border);
        color: var(--dbg-muted);
        padding: 4px 10px;
        font-size: 11px;
        font-family: inherit;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: background 0.15s, color 0.15s;
    }
    .dbg-btn:last-child { border-right: none; }
    .dbg-btn:hover { background: var(--dbg-hover); color: var(--dbg-text); }
    .dbg-btn--active { background: var(--dbg-active-bg); color: var(--dbg-accent); }
    .dbg-btn i { font-size: 10px; }

    /* views count badge */
    .dbg-view-count-cell { width: 52px; text-align: center; }
    .dbg-views-count {
        display: inline-block;
        padding: 1px 6px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 700;
        background: var(--dbg-active-bg);
        color: var(--dbg-muted);
        border: 1px solid var(--dbg-border);
        font-family: var(--dbg-font);
    }
    .dbg-views-count--multi {
        background: rgba(88,166,255,0.12);
        color: var(--dbg-accent);
        border-color: rgba(88,166,255,0.3);
    }
    .dbg-views-count-head { width: 52px; text-align: center; }
</style>
<script>
    (() => {
        const dbg      = document.getElementById('dbg');
        const content  = document.getElementById('dbg-content');
        const resizer  = document.getElementById('dbg-resize');
        const closeBtn = document.getElementById('dbg-close');
        const pill     = document.getElementById('dbg-pill');

        const STORAGE_OPEN   = 'dbg_open';
        const STORAGE_TAB    = 'dbg_tab';
        const STORAGE_HEIGHT = 'dbg_height';

        /* ── restore height ─────────────────────── */
        const savedHeight = parseInt(localStorage.getItem(STORAGE_HEIGHT) || '0');
        if (savedHeight > 60) content.style.height = savedHeight + 'px';

        /* ── restore state ──────────────────────── */
        const isOpen   = localStorage.getItem(STORAGE_OPEN) !== '0';
        const savedTab = localStorage.getItem(STORAGE_TAB);

        if (!isOpen) dbg.classList.add('dbg-collapsed');

        /* ── tab switching ──────────────────────── */
        function activateTab(id, save = true) {
            document.querySelectorAll('.dbg-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.dbg-panel').forEach(p => p.classList.remove('active'));

            const btn   = document.querySelector(`.dbg-tab-btn[data-tab="${id}"]`);
            const panel = document.getElementById('dbg-panel-' + id);
            if (btn)   btn.classList.add('active');
            if (panel) panel.classList.add('active');
            if (save) localStorage.setItem(STORAGE_TAB, id);

            if (save && dbg.classList.contains('dbg-collapsed')) expand();
        }

        function expand() {
            dbg.classList.remove('dbg-collapsed');
            localStorage.setItem(STORAGE_OPEN, '1');
        }

        function collapse() {
            dbg.classList.add('dbg-collapsed');
            localStorage.setItem(STORAGE_OPEN, '0');
        }

        /* bind tab buttons */
        document.querySelectorAll('.dbg-tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.tab;
                /* click active tab while open = collapse */
                if (btn.classList.contains('active') && !dbg.classList.contains('dbg-collapsed')) {
                    collapse();
                } else {
                    activateTab(id);
                }
            });
        });

        closeBtn.addEventListener('click', collapse);
        pill.addEventListener('click', () => {
            expand();
            const lastTab = localStorage.getItem(STORAGE_TAB);
            if (lastTab) activateTab(lastTab, false);
        });

        /* restore last tab */
        if (savedTab && document.querySelector(`.dbg-tab-btn[data-tab="${savedTab}"]`)) {
            activateTab(savedTab, false);
        } else {
            /* open first tab by default */
            const first = document.querySelector('.dbg-tab-btn');
            if (first) activateTab(first.dataset.tab, false);
        }

        /* ── resize ─────────────────────────────── */
        let resizing = false, startY = 0, startH = 0;

        resizer.addEventListener('mousedown', e => {
            resizing = true;
            startY = e.clientY;
            startH = content.offsetHeight;
            document.body.style.userSelect = 'none';
            e.preventDefault();
        });

        document.addEventListener('mousemove', e => {
            if (!resizing) return;
            const delta = startY - e.clientY;
            const headerH = document.getElementById('dbg-header').offsetHeight;
            const newH  = Math.max(60, Math.min(window.innerHeight - headerH - 8, startH + delta));
            content.style.height = newH + 'px';
            localStorage.setItem(STORAGE_HEIGHT, Math.round(newH));
        });

        document.addEventListener('mouseup', () => {
            if (resizing) { resizing = false; document.body.style.userSelect = ''; }
        });
    })();
</script>
