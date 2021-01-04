@section('header')
    @include('asset_groups.select2')
@endsection

@section('header_content')
    @featuredTitle('Közösséget keresek')
    <div class="pt-4 pb-4 mb-0">
        <div class="container">
            @widget('KOKE')
            <form method="get" id="finder" action="@route('portal.groups')">
                <input type="hidden" name="view" value="{{ $template }}">
                <div class="form-group">
                    <div class="input-group">
                        <select name="varos" style="width:150px !important;" class="form-control">
                            <option value="{{ $filter['varos'] }}">{{ $filter['varos'] }}</option>
                        </select>
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
                        <button type="submit" class="btn btn-darkblue">keresés indítása</button>
                    </div>
                </div>
                <div class="form-group text-center mt-5">
                    @foreach($tags as $i => $tag)
                    @if($i > 0 && $i % 8 == 0) <br/> @endif
                    <input type="checkbox"
                           class="group-tag"
                           id="tag-{{ $tag['slug'] }}"
                           value="{{ $tag['slug'] }}"
                           <?php if (in_array($tag['slug'], $selected_tags)): ?> checked <?php endif; ?>
                           style="display: none;">
                    <label for="tag-{{ $tag['slug'] }}" class="mr-1 badge badge-pill badge-light group-tag-badge align-middle" title="{{ $tag['tag'] }}">
                        <span class="align-middle">{{ $tag['tag'] }}</span>
                    </label>

                    @endforeach
                    <input type="hidden" name="tags" value="{{ $filter['tags'] }}">
                </div>
                <p class="mt-2 text-right">
                    <a href="/kozossegek" class="text-light">Szűrés törlése</a>
                </p>
            </form>
        </div>
    </div>
@endsection

@extends('portal')
    <div class="container inner">
<!--    <div class="row row-cols-xxs-1 row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4" style="padding-top:2em">-->
    @include('portal.partials.kozossegek-view2')
    @include('partials.simple-pager', ['route' => 'portal.groups.page','total' => $total,'page' => $page,'perpage' => $perpage,'routeparams' => $filter])
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
