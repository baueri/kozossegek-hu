<form  method="get" action="@route('portal.groups')" class="position-relative search-form">
    <input type="text" class="form-control rounded-pill api-group-search"
           placeholder="keresés" name="search" autocomplete="off"
           value="{{ $filter['search'] ?? '' }}" aria-label="Keresőszó" data-url="@route('api.search_group')" style="height: 30px; z-index: 1"/>
    <button type="submit" class="btn p-0" style="right: 10px; top: 2px; position:absolute; z-index: 2;" aria-label="Keresés">@icon('search', additionalClass: 'p-1')</button>
    <div class="search-results shadow"><span class="close small" style="cursor:pointer;">@icon('times')</span><div class="search-results-inner"></div></div>
</form>
