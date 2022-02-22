@section('header')
    <link rel="canonical" href="@route('portal.groups')" />
    <meta name="description" content="Közösséget keresek, keresés, jellemzők, katolikus" />
    @include('asset_groups.select2')
@endsection

@section('header_content')
    @include('portal.partials.kozosseget_keresek_title')
    <div class="pb-0 mb-0">
        <div class="container">
            <form method="get" id="finder" action="@route('portal.groups')">
                <div id="search-group" class="rounded-pill bg-white py-1 px-1">
                    <div class="row">
                        <div class="col-lg-4 border-right mb-2 mb-lg-0">
                            <input type="text" class="form-control rounded-pill" placeholder="kulcsszó, pl.:  egyetemista..." name="search" value="{{ $filter['search'] }}">
                        </div>
                        <div class="col-lg-3 border-right mb-2 mb-lg-0">
                            <input type="text" class="form-control rounded-pill" placeholder="város" name="varos" value="{{ $filter['varos'] }}">
                        </div>
                        <div class="col-lg-3 mb-2 mb-lg-0">
                            <select class="form-control rounded-pill" style="color:#aaa" name="korosztaly">
                                <option value="">-- korosztály --</option>
                                @foreach($age_groups as $age_group)
                                    <option value="{{ $age_group->value }}" @selected($selected_age_group === $age_group->value)>{{ $age_group->translate() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2"><button type="submit" class="btn btn-darkblue rounded-pill px-3 w-100"><i class="fa fa-search"></i> Keresés</button> </div>
                    </div>
                </div>
                <div class="tag-dropdown-menu text-center">
                    <p>
                        <span class="text-white text-shadowed">Közösség jellege</span>
                    </p>
                    @foreach($tags as $i => $tag)
                    @if($i > 0 && $i % 9 == 0) <br/> @endif
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
                <p class="mt-2 text-center">
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

    $(".group-tag").change(function () {
        var val = "";
        $(".group-tag:checked").each(function () {
            val += (val ? "," : "") + $(this).val();
        });

        $("[name=tags]").val(val);
    });

});
</script>
