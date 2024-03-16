<div class="btn-group btn-group-sm ml-4 btn-shadow">
    <a class="btn {{ empty($is_trash) && $page_type === 'page' ? 'active btn-primary' : 'btn-default' }}" href="@route('admin.page.list')">@icon('file-alt') Oldalak</a>
    <a class="btn {{ empty($is_trash) && $page_type === 'announcement' ? 'active btn-primary' : 'btn-default' }}" href="@route('admin.page.list', ['page_type' => 'announcement'])">@icon('bullhorn') Hirdetmények</a>
    <a class="btn {{ !empty($is_trash) ? 'active btn-primary' : 'btn-default' }}" href="@route('admin.page.trash')">Lomtár ({{ $trash_count }})</a>
</div>
<form method="get" class="input-group ml-auto float-right input-group-sm mr-4"  style="width: 300px;">
    <input type="text" name="search" class="form-control" value="{{ $filter['search'] ?? '' }}" />
    @if(!empty($is_trash))<input type="hidden" name="deleted" value="1">@endif
    <div class="input-group-append">
        <button type="submit" class="btn btn-primary">Keresés</button>
    </div>
</form>