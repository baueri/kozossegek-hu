@section('header')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    @include('asset_groups.select2')
@endsection
@extends('portal')
<div class="container inner">
    <h3 class="text-center" style="margin-bottom: 1em">Fecskedjen tehát egy böldöncöt</h3>
    <p class="text-center"> Mögötte az éppen nem fesítő kurumok, bár szenijük ott is konz - főzik legalább.
        Aztán lassul a kolódásra antár ; folyák, szatkások, margány gugasor raktárnyi tokrával, bons negyedik viteres köhenébe rengeteg varrás.
        Ami szinte azonnal dörnyezik: a kurumok a vigásokkal tárnyolnak, úgy horozják a hajoracsot.
    </p>
    <form method="get" id="finder" action="@route('portal.groups')">
        <div class="form-group">
            <div class="input-group">
                <select name="varos" style="width:200px" class="form-control">
                    <option value="{{ $filter['varos'] }}">{{ $filter['varos'] }}</option>
                </select>
                <input type="text" name="search" value="{{ $filter['search'] }}" class="form-control" placeholder="keresés...">
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
                        @if(in_array($tag['slug'], $selected_tags)) checked @endif
                      > <span>{{ $tag['tag'] }}</span>
                </label>
            @endforeach
            <input type="hidden" name="tags" value="{{ $filter['tags'] }}">
        </div>
        <p class="mt-2 text-right">
            <a href="/kozossegek">Szűrés törlése</a>
        </p>
    </form>
    <hr>
    <p><small>Összes találat: {{ $total }}</small></p>
    <div class="row" style="padding-top:2em">
        @foreach($groups as $i => $group)
            @if($i == 0 || $groups[$i-1]->city != $group->city)
                <div class="col-md-12">
                    <h2>{{ $group->city }}</h2>
                    <hr>
                    @if($filter['varos'] && $group->district && ($i == 0 || $groups[$i-1]->district == $group->district))
                        <h4 style="color:var(--secondary)">{{ $group->district }}</h4>

                    @endif
                </div>
            @endif

            <div class="col-lg-4 col-md-6">
                <a href="{{ $group->url() }}" class="card kozi-box">
                    <img class="card-img-top" src="{{ $group->getThumbnail() }}" />
                    <div class="card-body">
                        <h4>{{ $group->name }}</h4>
                        <div class="description">
                            {{ $group->excerpt() }}
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="kozi-info text-dark">
                            <div><i class="fas fa-map-marker-alt text-danger"></i>
                                <small>{{ $group->city . ($group->district ? '<br><span style="color:#888">' . $group->district . '</span>' : '')  }}</small>
                            </div>
                            <div><i class="fas fa-user-graduate"></i><small>{{ $group->ageGroup() }}</small></div>
                            <div><i class="fas fa-calendar-alt"></i><small>{{ $group->occasionFrequency() }}</small></div>
                        </div>

                    </div>
                </a>
            </div>
        @endforeach
    </div>
    @include('partials.simple-pager', ['route' => 'portal.groups.page','total' => $total,'page' => $page,'perpage' => $perpage,'routeparams' => $filter])
</div>
<script>
    $(()=>{
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

        $(".group-tag").change(function(){
            var val = "";
            $(".group-tag:checked").each(function(){
                val += (val ? "," : "") + $(this).val();
            });

            $("[name=tags]").val(val);
        });

    });
</script>
