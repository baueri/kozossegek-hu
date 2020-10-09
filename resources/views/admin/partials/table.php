
<div class="mt-4">Összes találat: <b>{{ $total }}</b></div>
<div class="mb-4">@include('partials.simple-pager')</div>

<table class="table table-striped table-hover table-sm table-responsive-sm">
    <thead>
        <tr>
            @foreach($columns as $key => $column)
                <th>{{ $column }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @if(isset($rows) && $rows)
            @foreach($rows as $row)
                <tr>
                    @foreach($columns as $key => $column)
                        <td {{ in_array($key, $centered_columns) ? 'class="text-center"' : '' }}>{{ $row[$key] }}</td>
                    @endforeach
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="{{ count($columns) }}"><h6 class="text-center p-3" style="color:#444; font-weight: 300;"><i>Nincs megjeleníthető adat</i></h6></td>
            </tr>
        @endif
    </tbody>
</table>

<div class="mt-4">@include('partials.simple-pager')</div>
