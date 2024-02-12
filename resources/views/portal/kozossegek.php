@section('header')
    <link rel="canonical" href="@route('portal.groups')" />
    <meta name="description" content="Közösséget keresek, keresés, jellemzők, katolikus" />
@endsection
@section('footer')
    @include('asset_groups.select2')
@endsection

@section('header_content')
    @include('portal.partials.kozosseget_keresek_title')
    <div class="pb-0 mb-0">
        <div class="container">
            <form method="get" id="finder" action="@route('portal.groups')">
                @include('portal.partials.search_box')
                <div class="tag-dropdown-menu text-center">
                    <p>
                        <span class="text-white text-shadowed">Közösség jellege</span>
                    </p>
                    @foreach($tags as $i => $tag)
                    @if($i > 0 && $i % 9 == 0) <br/> @endif
                    <input type="checkbox"
                           class="group-tag"
                           id="tag-{{ $tag->value }}"
                           value="{{ $tag->value }}"
                        @if(in_array($tag->value, $selected_tags)) checked @endif
                           style="display: none;">
                    <label for="tag-{{ $tag->value }}" class="mr-1 badge badge-pill badge-light group-tag-badge align-middle" aria-label="{{ $tag->translate() }}">
                        <span class="align-middle">{{ $tag->translate() }}</span>
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
        {{ $breadcrumb }}
        @yield('templom_title')
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
