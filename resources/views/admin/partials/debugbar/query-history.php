<table class="dbg-table" id="dbg-query-history">
    <thead>
        <tr>
            <th>Query <span class="dbg-muted-text">— total {{ $total_time }}s</span></th>
            <th style="width:80px;text-align:right">Time</th>
        </tr>
    </thead>
    <tbody>
        @foreach($queries as $row)
            <tr>
                <td class="dbg-query-sql"><code>{{ $row['sql'] }}</code></td>
                <td class="dbg-query-time" style="text-align:right">
                    @if($row['time'] >= 50000)
                        <span class="dbg-badge dbg-badge-danger">{{ $row['time'] }}<i>μ</i></span>
                    @elseif($row['time'] >= 10000)
                        <span class="dbg-badge dbg-badge-warning">{{ $row['time'] }}<i>μ</i></span>
                    @else
                        <span class="dbg-badge dbg-badge-success">{{ $row['time'] }}<i>μ</i></span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<style>
    .dbg-query-sql code {
        font-family: var(--dbg-font, monospace);
        font-size: 11px;
        color: #e6edf3;
        white-space: pre-wrap;
        word-break: break-word;
    }
    .dbg-muted-text { color: var(--dbg-muted); font-weight: 400; }
</style>
