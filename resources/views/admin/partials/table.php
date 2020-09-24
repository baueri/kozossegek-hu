
<div class="mb-4">@include('partials.simple-pager')</div>

<table class="table table-striped table-hover table-sm">
    <thead>
        <tr>
            @foreach($columns as $key => $column)
                <th>{{ $column }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
            <tr>
                @foreach($columns as $key => $column)
                    <td {{ in_array($key, $centered_columns) ? 'class="text-center"' : '' }}>{{ $row[$key] }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">@include('partials.simple-pager')</div>
