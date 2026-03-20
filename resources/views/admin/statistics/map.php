@title('Lefedettség - Térkép')
@section('header')
<link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
      integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
      crossorigin="" media="all" @preload_css()/>
@endsection
@section('footer')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
@endsection
@extends('admin')
@alert('warning')
A térkép a közösségek lefedettségét mutatja. <a data-toggle="collapse" href="#collapse_more" role="button" aria-expanded="false" aria-controls="collapse_more">további információ @icon('angle-down')</a>
<div class="collapse" id="collapse_more">
    <p>
        <b>Jelmagyarázat:</b><br/>
        <img src="/images/marker_blue.png" style="width: 30px"> a városban van közösség, de nem kerestek még rá<br/>
        <img src="/images/marker_red.png" style="width: 30px"> a városban több, mint <b>{{ $interaction_weight }}</b> interakció (<b>keresés, közi megtekintés, kapcsolatfelvétel</b>) történt<br/>
        <img src="/images/marker_green.png" style="width: 30px"> van átfedés: van közösség a városban és volt interakció<br/>
    </p>
</div>

@endalert
@component('open_street_map', ['types' => ['city_stat'], 'height' => 650])
