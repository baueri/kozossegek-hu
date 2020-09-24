@section('title')Közösségek@endsection
@extends('admin')

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
        <a href="{{ route('admin.group.list') }}">Szűrés törlése</a>
    </p>
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