@if(!empty($trashView))
    @alert('danger mt-4')
        <a href="#" onclick="deleteConfirm(function () {}, 'Biztosan üríted a lomtárat?'); return false;" class="btn btn-danger">Lomtár ürítése</a>
    @endalert
@endif
@if($with_pager)
    <div class="mt-4">Összes találat: <b>{{ $total }}</b></div>
    <div class="mb-4">@include('partials.simple-pager')</div>
@endif
<table class="bg-white shadow rounded table table-hover table-responsive-sm">
    <thead>
        <tr>
            @foreach($columns as $key => $column)
                <th class="{{ $column_classes[$key] ?? '' }} {{ in_array($key, $centered_columns) ? 'text-center' : '' }}">
                    @if($order[0] == $key || in_array($key, $sortable_columns))
                        <?php $sort_order = $key != $order[0] || $order[1] == 'asc' ? 'desc' : 'asc'; ?>
                        <a href="?{{ http_build_query(array_merge(request()->except('pg')->all(), ['sort' => $sort_order, 'order_by' => $key])) }}" style="white-space: nowrap">
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
                <tr style="border-bottom: 4px solid var(--light)">
                    @foreach($columns as $key => $column)
                        <td class="{{ in_array($key, $centered_columns) ? 'text-center' : '' }} {{ $column_classes[$key] ?? '' }}">{{ $row[$key] }}</td>
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

@if($with_pager)
    <div class="mt-4">@include('partials.simple-pager')</div>
@endif