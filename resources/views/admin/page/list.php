@title('Oldalak')
@extends('admin')

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ !$is_trash ? 'active' : '' }}" href="{{ route('admin.page.list') }}">Közzétett oldalak</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $is_trash ? 'active' : '' }}" href="{{ route('admin.page.trash') }}">Lomtár ({{ $trash_count }})</a>
    </li>
</ul>

<form method="get" class="mt-4 mb-4">
    <div class="row">
        <div class="col-md-4 offset-8">
            <div class="input-group">
                <input type="text" name="search" class="form-control" value="{{ $filter['search'] }}" />
                @if($is_trash)<input type="hidden" name="deleted" value="1">@endif
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Keresés</button>
                </div>
            </div>
        </div>
    </div>
</form>

{{ $table }}

@if($is_trash)
<p>
    <a href="{{ route('admin.page.empty_trash') }}" class="text-danger">lomtár ürítése</a>
</p>
@endif