@section('header')
<link rel="canonical" href="@route('portal.groups')" />
<meta name="description" content="Közösséget keresek, keresés, jellemzők, katolikus" />
@include('asset_groups.select2')
@endsection

@section('header_content')
@include('portal.partials.kozosseget_keresek_title')
<div class="pb-0 mb-0">
    <div class="container">
        {{ $filter['korosztaly'] }}
        <form method="get" id="finder" action="@route('portal.groups')">
            <div id="search-group" class="rounded-pill bg-white py-1 px-1">
                <div class="row">
                    <div class="col-lg-7 border-right mb-2 mb-lg-0">
                        <input type="text" class="form-control rounded-pill" placeholder="Milyen közösséget keresel? pl.: Budapest egyetemista..." name="search" value="{{ $filter['search'] }}">
                    </div>
                    <div class="col-lg-3 mb-2 mb-lg-0">
                        <select class="form-control rounded-pill" style="color:#aaa" name="korosztaly">
                            <option value="">-- @lang('age_group') --</option>
                            @foreach($age_groups as $age_group)
                                <option value="{{ $age_group->value }}" @selected($selected_age_group === $age_group->value)>{{ $age_group->translate() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2"><button type="submit" class="btn btn-altblue rounded-pill px-3 w-100"><i class="fa fa-search"></i> Keresés</button> </div>
                </div>
            </div>
            <div class="tag-dropdown-menu text-center">
                <p>
                    <span class="text-white text-shadowed">Közösség jellege</span>
                </p>
                @foreach($tags as $i => $tag)
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
            </p>
        </form>
    </div>
</div>
@endsection

@extends('portal')
<div class="container">
    <div class="bg-white m-0 px-5" style="padding-top: 4em; padding-bottom: 4em;">
        <h4 class="font-italic text-center m-0">A keresés indításához add meg a városod nevét, a korosztályodat vagy olyan kulcsszót, ami azt a közösséget jellemzi, amihez szívesen csatlakoznál!</h4>
    </div>
</div>
<script>
    var onRes = () => {
        if($(window).width() <= 992) {
            $("body").removeClass("vh-100 d-flex flex-column");
            $(".header-outer").removeClass("flex-fill");
        } else {
            $("body").addClass("vh-100 d-flex flex-column");
            $(".header-outer").addClass("flex-fill");
        }
    }

    // $(window).resize(onRes);

    $(() => {
        // onRes();
        $(".group-tag").change(function () {
            var val = "";
            $(".group-tag:checked").each(function () {
                val += (val ? "," : "") + $(this).val();
            });

            $("[name=tags]").val(val);
        });

    });
</script>
