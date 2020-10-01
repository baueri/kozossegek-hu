@title('Oldalak')
@section('title')
    <div class="btn-group btn-group-sm ml-4 btn-shadow">
        <a class="btn {{ !$is_trash ? 'active btn-primary' : 'btn-default' }}" href="@route('admin.page.list')">Közzétett oldalak</a>
        <a class="btn {{ $is_trash ? 'active btn-primary' : 'btn-default' }}" href="@route('admin.page.trash')">Lomtár ({{ $trash_count }})</a>
    </div>
    <form method="get" class="input-group ml-auto float-right input-group-sm mr-4"  style="width: 300px;">
        <input type="text" name="search" class="form-control" value="{{ $filter['search'] }}" />
        @if($is_trash)<input type="hidden" name="deleted" value="1">@endif
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Keresés</button>
        </div>
    </form>
@endsection
@extends('admin')

@if($is_trash)
    <p>
        <a href="@route('admin.page.empty_trash')" class="text-danger">lomtár ürítése</a>
    </p>
@endif


{{ $table }}
