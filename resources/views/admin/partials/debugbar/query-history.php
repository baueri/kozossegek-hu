<table class="table table-sm" id="query-history">
    <thead>
        <tr>
            <th>lekérdezés (total: {{ $total_time }}s)</th>
            <th>idő</th>
        </tr>
    </thead>
    @foreach($queries as $row)
        <tr>
            <td><code>{{ $row[0] }}</code></td>
            <td><code><b>{{ $row[2] }}</b><i>μ</i></code></td>
        </tr>
    @endforeach
</table>
