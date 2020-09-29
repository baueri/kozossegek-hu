@section('title')Közösségek@endsection
@section('header')@include('asset_groups.select2')@endsection
@extends('admin')

<form method="get" id="finder">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Keresés névre, leírásra</label>
                <input type="text" name="search" value="{{ $filter['search'] }}" class="form-control" placeholder="keresés...">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Státusz</label>
                <select class="form-control" name="status">
                    <option></option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->name }}" {{ $filter['status'] == $status->name ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="varos">Város</label>
                <select name="varos" id="varos" class="form-control">
                    <option value="{{ $filter['varos'] }}">{{ $filter['varos'] ?: 'város' }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="institute_id">Plébánia / intézmény</label>
                <select name="institute_id" id="institute_id" class="form-control">
                    <option value="{{ $filter['institute_id'] }}">{{ $institute ? $institute->name : 'intézmény' }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="korosztaly">Korosztály</label>
                <select class="form-control" id="korosztaly" name="korosztaly">
                    <option></option>
                    @foreach($age_groups as $age_group)
                    <option value="{{ $age_group->name }}" {{ $age_group->name == $filter['korosztaly'] ? 'selected' : '' }}>{{ $age_group }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="rendszeresseg">Rendszeresség</label>
                <select class="form-control" id="rendszeresseg" name="rendszeresseg">
                    <option></option>
                    @foreach($occasion_frequencies as $occasion_frequency)
                    <option value="{{ $occasion_frequency->name }}" {{ $occasion_frequency->name == $filter['rendszeresseg'] ? 'selected' : '' }}>{{ $occasion_frequency }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label>Rendezés</label>
                <select class="form-control" name="order">
                    <option></option>
                    <option value="created_at">Újak elöl</option>
                    <option value="pending_first">Függőben levők elöl</option>
                    <option value="abc_asc">Név szerint növekvő</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Keresés</button>
        <a href="{{ route('admin.group.list') }}">Szűrés törlése</a>
    </div>
</form>

{{ $table }}

<script>
    $(() => {
        $("[name=varos]").select2({
            placeholder: "város",
            allowClear: true,
            ajax: {
                url: '/api/v1/search-city',
                dataType: 'json',
                delay: 300
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
        $("[name=status]").select2({placeholder: "státusz", allowClear: true});

        $("[name=institute_id]").select2({
            placeholder: "intézmény",
            allowClear: true,
            ajax: {
                url: "{{ route('api.search-institute') }}",
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    var city;
                    if (city = $("[name=varos]").val()) {
                        params.city = city;
                    }
                    return params;
                }
            }
        });
    });
</script>