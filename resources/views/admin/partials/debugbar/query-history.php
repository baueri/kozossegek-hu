<table class="table table-sm" id="query-history">
    <thead>
        <tr>
            <th>lekérdezés (total: {{ $total_time }}s)</th>
            <th>idő</th>
        </tr>
    </thead>
    @foreach($queries as $row)
        <tr>
            <td>{{ $row[0] }}</td>
            <td>{{ round($row[2], 3) }}</td>
        </tr>
    @endforeach
</table>

<style>
    #query-history {
        color: var(--red);
    }
</style>
