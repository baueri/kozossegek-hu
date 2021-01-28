
<div class="mt-4">Összes találat: <b>{{ $total }}</b></div>
<div class="mb-4">@include('partials.simple-pager')</div>

<table class="table table-striped table-hover table-sm table-responsive-sm">
    <thead>
        <tr>
            @foreach($columns as $key => $column)
                <th {{ in_array($key, $centered_columns) ? 'class="text-center"' : '' }}>
                    @if($order[0] == $key || in_array($key, $sortable_columns))
                        <?php $sort_order = $key != $order[0] || $order[1] == 'asc' ? 'desc' : 'asc'; ?>
                        <a href="?{{ http_build_query(array_merge($_REQUEST, ['sort' => $sort_order, 'order_by' => $key])) }}" style="white-space: nowrap">
                            {{ $column }}
                            @if($key == $order[0])
                                <i class="fa fa-{{ $order[1] == 'asc' ? 'sort-alpha-down' : 'sort-alpha-up' }}" style="font-size: 16px;"></i>
                            @endif
                        </a>
                    @else
                        {{ $column }}
                    @endif
                </th>
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
