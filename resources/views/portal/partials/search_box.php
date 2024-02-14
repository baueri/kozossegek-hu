<div id="search-group" class="bg-white py-1 px-1">
    <div class="row">
        <div class="col-lg-3 mb-2 mb-lg-0">
            <select class="form-control" style="color:#aaa" name="korosztaly" aria-label="@lang('age_group')">
                <option value="">-- @lang('age_group') --</option>
                @foreach($age_groups as $age_group)
                <option value="{{ $age_group->value }}" @selected($selected_age_group === $age_group->value)>{{ $age_group->translate() }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-7 border-right mb-2 mb-lg-0">
            <input type="text" class="form-control"
                   placeholder="Milyen közösséget keresel? pl.: Budapest egyetemista..." name="search"
                   value="{{ $filter['search'] ?? '' }}" aria-label="Keresőszó" data-url="@route('api.search_group')"/>
        </div>

        <div class="col-lg-2"><button type="submit" class="btn btn-altblue px-3 w-100" aria-label="Keresés indítása"><i class="fa fa-search"></i> Keresés</button> </div>
    </div>
    <div class="search-results shadow"><span class="close small" style="cursor:pointer;">@icon('times')</span><div class="search-results-inner"></div></div>
</div>