@section('header')
    <link rel="canonical" href="@route('portal.groups')" />
    <meta name="description" content="Közösséget keresek, keresés, jellemzők, katolikus" />
    @include('asset_groups.select2')
@endsection

@section('header_content')
    @featuredTitle('Közösséget keresek')
    <div class="pb-3 mb-0">
        <div class="container">
            <form method="get" id="finder" action="@route('portal.groups')">
                <input type="hidden" name="view" value="{{ $template }}">
                <div class="form-group">
                    <div class="input-group">
                        <select name="varos" style="width:150px !important;" class="form-control">
                            <option value="{{ $filter['varos'] }}">{{ $filter['varos'] }}</option>
                        </select>
                        <div class="tag-dropdown">
                            <button type="button" onclick="$('.tag-dropdown-menu').slideToggle();"  class="form-control text-left">Jellemzők <i class="fa fa-caret-down ml-2" style="font-size: 12px;"></i> </button>
                        </div>
                        <select class="form-control" id="korosztaly" name="korosztaly">
                            <option></option>
                            @foreach($age_groups as $age_group)
                            <option value="{{ $age_group->name }}" {{ $age_group->name == $filter['korosztaly'] ? 'selected' : '' }}>{{ $age_group }}</option>
                            @endforeach
                        </select>
                        <select class="form-control" id="rendszeresseg" name="rendszeresseg">
                            <option></option>
                            @foreach($occasion_frequencies as $occasion_frequency)
                            <option value="{{ $occasion_frequency->name }}" {{ $occasion_frequency->name == $filter['rendszeresseg'] ? 'selected' : '' }}>{{ $occasion_frequency }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="search" value="{{ $filter['search'] }}" class="form-control" placeholder="keresés kulcsszavak alapján...">
                    </div>
                </div>
                <div class="tag-dropdown-menu text-center" @if($selected_tags) style="display: block;" @endif>
                    @foreach($tags as $i => $tag)
                    @if($i > 0 && $i % 8 == 0) <br/> @endif
                    <input type="checkbox"
                           class="group-tag"
                           id="tag-{{ $tag['slug'] }}"
                           value="{{ $tag['slug'] }}"
                        @if(in_array($tag['slug'], $selected_tags)) checked @endif
                           style="display: none;">
                    <label for="tag-{{ $tag['slug'] }}" class="mr-1 badge badge-pill badge-light group-tag-badge align-middle">
                        <span class="align-middle">{{ $tag['tag'] }}</span>
                    </label>
                    @endforeach
                    <input type="hidden" name="tags" value="{{ $filter['tags'] }}">
                </div>
                <p class="text-center mt-5">
                    <button type="submit" class="btn btn-darkblue" style="box-shadow: 0 0 5px #333;"><i class="fa fa-search mr-2"></i> keresés indítása</button>
                </p>
                <p class="mt-2 text-right">
                    <a href="/kozossegek" class="text-light text-shadowed">Szűrés törlése</a>
                </p>
            </form>
        </div>
    </div>
@endsection

@extends('portal')
    <div class="container inner">
        @if($total)
            @include('portal.partials.kozossegek-view2')
            @include('partials.simple-pager', ['route' => 'portal.groups.page','total' => $total,'page' => $page,'perpage' => $perpage,'routeparams' => $filter])
        @else
        <h5 class="text-center text-muted"><i>Sajnos nem találtunk ilyen közösséget.</i></h5>
        <h6 class="text-center text-muted"><i>Próbáld meg más keresési feltételekkel.</i></h6>
        @endif
    </div>
<script>
$(() => {
    $("[name=varos]").select2({
        placeholder: "település",
        allowClear: true,
        ajax: {
            url: '/api/v1/search-city',
            dataType: 'json',
            delay: 300
                    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    });
    $("[name=korosztaly]").select2({
        placeholder: "korosztály",
        allowClear: true,
    });

    $("[name=rendszeresseg]").select2({
        placeholder: "rendszeresség",
        allowClear: true,
    });

    $(".group-tag").change(function () {
        var val = "";
        $(".group-tag:checked").each(function () {
            val += (val ? "," : "") + $(this).val();
        });

        $("[name=tags]").val(val);
    });

});
</script>
