@section('header')
    <link rel="canonical" href="@route('portal.groups')" />
    <meta name="description" content="Közösséget keresek, keresés, jellemzők, katolikus" />
    @include('asset_groups.select2')
@endsection

@section('header_content')
    @include('portal.partials.kozosseget_keresek_title')
    <div class="pb-0 mb-0">
        <div class="container">
            <form method="get" id="finder" action="@route('portal.groups_korosztaly', ['korosztaly' => $filter['korosztaly']])">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" name="varos" value="{{ $filter['varos'] }}" placeholder="település" style="max-width: 150px;">
<!--                        <select name="varos" style="width:150px !important;" class="form-control">-->
<!--                            <option value="{{ $filter['varos'] }}">{{ $filter['varos'] }}</option>-->
<!--                        </select>-->
<!--                        <input type="hidden" name="korosztaly" value="{{ $filter['korosztaly'] }}"/>-->
                        <input type="text" name="search" value="{{ $filter['search'] }}" class="form-control" placeholder="keresés kulcsszavak alapján...">
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
                <p class="text-center mt-5">
                    <button type="submit" class="btn btn-darkblue" style="box-shadow: 0 0 5px #333;"><i class="fa fa-search mr-2"></i> keresés indítása</button>
                </p>
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
