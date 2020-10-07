@title('Közösségek')

@section('title')
    <a href="@route('admin.group.create')" class="btn btn-create"><i class="fa fa-plus"></i> Új közösség</a>
    <div class="btn-group btn-group-sm btn-shadow ml-4">
        <a class="btn {{ $current_page == 'all' ? 'active btn-primary' : 'btn-default' }}" href="@route('admin.group.list')">Összes</a>
        <a class="btn {{ $current_page == 'pending' ? 'active btn-primary' : 'btn-default' }}" href="@route('admin.group.list', ['status' => 'pending'])">
            Függőben @if($pending_groups)
             ({{ $pending_groups }})
             @endif
        </a>
        <a class="btn {{ $current_page == 'inactive' ? 'active btn-primary' : 'btn-default' }}" href="@route('admin.group.list', ['status' => 'inactive'])">Inaktív</a>
        <a class="btn {{ $current_page == 'trash' ? 'active btn-primary' : 'btn-default' }}" href="@route('admin.group.trash')">Lomtár</a>
    </div>
@endsection
@section('header')@include('asset_groups.select2')@endsection
@extends('admin')

<form method="get" id="finder">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <input type="text" name="search" value="{{ $filter['search'] }}" class="form-control" placeholder="keresés névre, leírásra...">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <select name="varos" id="varos" class="form-control">
                    <option value="{{ $filter['varos'] }}">{{ $filter['varos'] ?: 'város' }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <select name="institute_id" id="institute_id" class="form-control">
                    <option value="{{ $filter['institute_id'] }}">{{ $institute ?? 'intézmény' }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <select class="form-control" id="korosztaly" name="korosztaly">
                    <option></option>
                    @foreach($age_groups as $age_group)
                    <option value="{{ $age_group->name }}" {{ $age_group->name == $filter['korosztaly'] ? 'selected' : '' }}>{{ $age_group }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <button type="submit" class="btn btn-primary btn-sm">Keresés</button>
            <a class="btn btn-default btn-sm" href="@route('admin.group.list')">Alapállapot</a>
        </div>
    </div>
</form>

{{ $table }}

<script>
    $(() => {
        $("[name=order]").select2({ placeholder: "rendezés" });
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

        $("[name=institute_id]").select2({
            placeholder: "plébánia / intézmény",
            allowClear: true,
            ajax: {
                url: "@route('api.search-institute')",
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
