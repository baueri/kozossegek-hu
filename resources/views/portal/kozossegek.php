@extends('portal')
<div class="container inner">
    <h3 class="text-center" style="margin-bottom: 1em">Fecskedjen tehát egy böldöncöt</h3>
    <p class="text-center"> Mögötte az éppen nem fesítő kurumok, bár szenijük ott is konz - főzik legalább.
        Aztán lassul a kolódásra antár ; folyák, szatkások, margány gugasor raktárnyi tokrával, bons negyedik viteres köhenébe rengeteg varrás.
        Ami szinte azonnal dörnyezik: a kurumok a vigásokkal tárnyolnak, úgy horozják a hajoracsot.
    </p>
    <form method="get" id="finder">
        
    </form>
    <small>Összes találat: {{ $total }}</small>
    <div class="row" style="padding-top:2em">
        @foreach($groups['rows'] as $groupid => $group)
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
        $("[name=city], [name=age_group]").select2();
        $("[name=occasion_frequency]").select2({
            placeholder: "-- rendszeresség --"
        });
    });
</script>