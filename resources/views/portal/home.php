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
            <div class="input-group">
                <select name="varos" style="width:200px" class="form-control">
                    <option></option>
                </select>
                <input type="text" name="search" class="form-control" placeholder="keresés...">
                <select class="form-control" id="korosztaly" name="korosztaly">
                    <option></option>
                    @foreach($age_groups as $age_group)
                    <option value="{{ $age_group->name }}">{{ $age_group }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </div>
        </form>
    </div>
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

    });
</script>
