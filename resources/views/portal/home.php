@section('header')
    @include('asset_groups.select2')
@endsection
@extends('portal')
<div id="main-finder" class="p-3 p-lg-5 p-lg-6">
    <div class="container">
        <div class="text-white text-center" style="margin-bottom: 2em;">
            @widget('FOKE')
        </div>
        <form method="get" id="finder" action="@route('portal.groups')">
            <div class="input-group input-group-lg">
                <input type="text" name="search" class="form-control" placeholder="keresés...">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-search"></i></button>
            </div>
        </form>
    </div>
</div>
