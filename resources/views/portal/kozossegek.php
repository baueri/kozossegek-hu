@section('header')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endsection
@extends('portal')
<div class="container inner">
    <h3 class="text-center" style="margin-bottom: 1em">Fecskedjen tehát egy böldöncöt</h3>
    <p class="text-center"> Mögötte az éppen nem fesítő kurumok, bár szenijük ott is konz - főzik legalább.
        Aztán lassul a kolódásra antár ; folyák, szatkások, margány gugasor raktárnyi tokrával, bons negyedik viteres köhenébe rengeteg varrás.
        Ami szinte azonnal dörnyezik: a kurumok a vigásokkal tárnyolnak, úgy horozják a hajoracsot.
    </p>
    <form method="get" id="finder">
        <div class="input-group">
            <select name="varos" style="width:200px" class="form-control">
                <option value="{{ $filter['varos'] }}">{{ $filter['varos'] ?: '-- város --' }}</option>
            </select>
            <input type="text" name="search" value="{{ $filter['search'] }}" class="form-control" placeholder="Keresés...">
            <select class="form-control" id="korosztaly" name="korosztaly">
                @foreach($age_groups as $age_group)
                    <option value="{{ $age_group->name }}" {{ $age_group->name == $age_group ? 'selected' : '' }}>{{ $age_group }}</option>
                @endforeach
            </select>
            <select class="form-control" id="rendszeresseg" name="rendszeresseg">
                <option></option>
                @foreach($occasion_frequencies as $occasion_frequency)
                    <option value="{{ $occasion_frequency->name }}" {{ $occasion_frequency->name==$occasion_frequency ? 'selected' : '' }}>{{ $occasion_frequency }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
        </div>
        <p class="mt-15 text-right">
            <a href="/kozossegek">Szűrés törlése</a>
        </p>
    </form>
    <hr>
    <p><small>Összes találat: {{ $total }}</small></p>
    <div class="row" style="padding-top:2em">
        @foreach($groups as $groupid => $group)
            <div class="col-lg-4 col-md-6">
                <a href="{{ $group->url() }}" class="card kozi-box">
                    <img class="card-img-top" src="https://picsum.photos/400/250?random={{ $groupid }}" />
                    <div class="card-body">
                        <h4>{{ $group->name }}</h4>
                        {{ $group->excerpt() }}
                    </div>
                    <div class="card-footer">
                        <div class="kozi-info text-dark">
                            <div><i class="fas fa-map-marker-alt text-danger"></i><small>{{ $group->city }}</small></div>
                            <div><i class="fas fa-user-graduate"></i><small>{{ $group->ageGroup() }}</small></div>
                            <div><i class="fas fa-calendar-alt"></i><small>{{ $group->occasionFrequency() }}</small></div>
                        </div>

                    </div>
                </a>
            </div>
        @endforeach
    </div>
    
    @include('partials.simple-pager')
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
    });
</script>