@section('header')
    <link href="https://fonts.googleapis.com/css?family=Cardo:400,700|Oswald" rel="stylesheet">
    @include('asset_groups.select2')
@endsection
@section('header_content')
    <div id="main-finder" class="p-4 p-lg-5">
        <div class="container">
            <div class="text-white text-center" style="margin: auto">
                <img src="/images/logo_only_md.png" style="max-width: 240px; filter: brightness(0) invert(1);" class="mb-4"/>
                <h1>TALÁLD MEG A KÖZÖSSÉGED!</h1>
            </div>
            <form method="get" id="finder" class="mt-5" action="@route('portal.groups')">
                <input type="text" name="search" class="mb-5" placeholder="pl.: Budapest antiochia egyetemista">
                <button type="submit" class="btn btn-primary btn-lg">Keresés</button>
            </form>
        </div>
    </div>
@endsection
@extends('portal')
{{ $content }}
