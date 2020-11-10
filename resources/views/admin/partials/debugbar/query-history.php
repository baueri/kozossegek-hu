<div class="text-dark font-weight-bold pl-3">
    Összes lekérdezés: {{ count($queries) }}<br>
    Lekérdezések ideje: {{ $total_time }}ms
</div>
<table class="table table-sm" id="query-history">
    <thead>
        <tr>
            <th>lekérdezés</th>
            <th>idő</th>
        </tr>
    </thead>
    @foreach($queries as $row)
        <tr>
            <td>{{ $row[0] }}</td>
            <td>{{ round($row[2], 4) }}</td>
        </tr>
    @endforeach
</table>

<style>
    #query-history {
        color: var(--red);
    }
</style>