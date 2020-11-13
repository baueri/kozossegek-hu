@section('header')
    @include('asset_groups.select2')
@endsection
@extends('portal')
@featuredTitle('Közösséget keresek')
<div class="jumbotron pt-4 pb-4 mb-0">
<div class="container">
    @widget('KOKE')
    <form method="get" id="finder" action="@route('portal.groups')">
        <input type="hidden" name="view" value="{{ $template }}">
        <div class="form-group">
            <div class="input-group">
                <select name="varos" style="width:200px !important;" class="form-control">
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
                <button type="submit" class="btn btn-primary">keresés indítása</button>
            </div>
        </div><div class="form-group">
            <label>Közösség jellemzői:</label><br>
            @foreach($tags as $tag)
            <label for="tag-{{ $tag['slug'] }}" class="mr-1">
                <input type="checkbox"
                       class="group-tag"
                       id="tag-{{ $tag['slug'] }}"
                       value="{{ $tag['slug'] }}"
                    <?php if (in_array($tag['slug'], $selected_tags)): ?> checked <?php endif; ?>
                > <span>{{ $tag['tag'] }}</span>
            </label>
            @endforeach
            <input type="hidden" name="tags" value="{{ $filter['tags'] }}">
        </div>
        <p class="mt-2 text-right">
            <a href="/kozossegek">Szűrés törlése</a>
        </p>
    </form>
</div>
</div>
    <div class="container inner">

    <div class="mb-3">
        <small>Összes találat: {{ $total }}</small>
        <div class="float-right" style="font-size: 1.4em;">
            <a href="?<?php echo http_build_query(array_merge($_REQUEST, ['view' => 'grid1'])); ?>" class="{{ $template == 'grid1' ? 'text-dark' : 'text-lightgray' }}"><i class="fa fa-th-large"></i></a>
            <a href="?<?php echo http_build_query(array_merge($_REQUEST, ['view' => 'grid2'])); ?>" class="{{ $template == 'grid2' ? 'text-dark' : 'text-lightgray' }}"><i class="fa fa-th"></i></a>
        </div>
    </div>
<!--    <div class="row row-cols-xxs-1 row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4" style="padding-top:2em">-->
    @if($template == 'grid1')
        @include('portal.partials.kozossegek-view1')
    @else
        @include('portal.partials.kozossegek-view2')
    @endif

    @include('partials.simple-pager', ['route' => 'portal.groups.page','total' => $total,'page' => $page,'perpage' => $perpage,'routeparams' => $filter])
</div>
<script>
$(() => {
    $("[name=varos]").select2({
        placeholder: "város",
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
