@section('subtitle', 'Katolikus Lelkiségi mozgalmak - ')
@section('header_content')
    @featuredTitle('Katolikus Lelkiségi mozgalmak')
@endsection
@extends('portal')
<div class="container inner">
    <div class="text-center text-muted font-italic mb-5">
        <p>
            Egy katolikus lelkiségi mozgalom olyan közösség, amely a római katolikus egyház tanítását követve, a szerzetesrendek és a különféle társulatok, egyesületek mellett újabb alternatívát kínálnak a katolikus hit megélésének területén.
        </p>
        <p>
            A régebbi korokban elsősorban a szerzetesrendek és a különféle társulatok, egyesületek jelentettek alternatívát a hit megélésének területén. A mai idők hasonló új kezdeményezéseit általában „mozgalmak és lelkiségek” néven foglaljuk össze. A régebbi kezdeményezések az idők során jórészt intézményesültek, alkalmazkodtak a plébániarendszer által meghatározott szervezeti struktúrához. Egyes nagy múltra visszatekintő szervezetek eredeti lendületüket fölelevenítve maguk is az újonnan induló áramlatok mellé sorakoznak fel.
        </p>
    </div>
    <div>
        @foreach($spiritualMovements as $spiritualMovement)
        <div class="card mb-3 shadow-sm rounded">
            <div class="no-gutters d-md-flex flex-row">
                <div style="max-width: 150px; margin: auto;" class="w-100 p-2"><img src="{{ $spiritualMovement->image_url }}"></div>
                <div class="p-3 flex-grow-1 text-center text-md-left">
                    <h5 class="card-title"><a href="@route('portal.spiritual_movement.view', ['slug' => $spiritualMovement->slug])">{{ $spiritualMovement->name }}</a></h5>
                    <div class="card-text">{{ $spiritualMovement->excerpt() }}</div>
                    <p class="card-text">
                        <small class="text-muted">Regisztrált közösségek: {{ $spiritualMovement->group_count ?? 0 }}</small>
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>