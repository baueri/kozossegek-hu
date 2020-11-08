@section('header')
    @include('asset_groups.select2')
@endsection
@extends('portal')

@featuredTitle('Közösséget keresek')
<div class="container inner">
    @widget('KOKE')
    <form method="get" id="finder" action="@route('portal.groups')">
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
                <input type="text" name="search" value="{{ $filter['search'] }}" class="form-control" placeholder="keresés közösség nevére, jellemzőre, városra stb...">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </div>
        </div>

        <div class="form-group">
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
    <p><small>Összes találat: {{ $total }}</small></p>
    <div class="row row-cols-xxs-1 row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4" style="padding-top:2em">
        @foreach($groups as $i => $group)
        <div class="col mb-4">
            <a href="{{ $group->url() }}" class="card h-100 kozi-box">
                <img class="card-img-top" src="{{ $group->getThumbnail() }}" />
                <div class="card-body">
                    <h2 class="mb-1 h6 font-weight-bold">{{ $group->name }}</h2>
                        <i class="fas fa-map-marker-alt text-danger"></i><span class="text-gray"> {{ $group->city . ($group->district ? '<br>' . $group->district . '</span>' : '')  }}<br>
                        <i class="fas fa-user-graduate"></i> <span class="text-gray">{{ $group->ageGroup() }}</span><br>
                        <i class="fas fa-calendar-alt"></i> <span class="text-gray">{{ $group->occasionFrequency() }}</span>
                </div>
<!--                <div class="card-footer">-->
<!--                    <div class="kozi-info text-dark">-->
<!--                        <div>-->
<!--                            <small></small>-->
<!--                        </div>-->
<!--                        <div></div>-->
<!--                    </div>-->
<!--                </div>-->
            </a>
        </div>
        @endforeach
    </div>
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
