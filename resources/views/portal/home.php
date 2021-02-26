@section('header')
    <link href="https://fonts.googleapis.com/css?family=Cardo:400,700|Oswald" rel="stylesheet">
    @include('asset_groups.select2')
@endsection
@section('header_content')
    <div id="main-finder" class="p-4 p-lg-5">
        <div class="container">
            <div class="text-white text-center" style="margin: auto">
                <img src="/images/logo_only_md.png" class="mb-4"/>
                <h1>TALÁLD MEG A KÖZÖSSÉGED!</h1>
            </div>
            <form method="get" id="finder" class="mt-5 text-center" action="@route('portal.groups')">
                <input type="text" name="search" class="mb-5" placeholder="pl.: Budapest antiochia egyetemista">
                <button type="submit" class="btn btn-darkblue btn-lg"><i class="fa fa-search mr-2"></i> Keresés</button>
            </form>
        </div>
    </div>
@endsection
@extends('portal')
{{ $content }}
