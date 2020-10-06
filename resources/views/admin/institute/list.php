@title('Intézmények')
@header('')
    @include('asset_groups.select2')

    <style>
        .select2-container {
            width: 140px !important;
            max-width: 140px !important;
        }
    </style>
@endheader
@section('title')
    <form method="get" class="input-group ml-auto float-right input-group-sm mr-4"  style="max-width: 600px;">
        <select name="city" class="form-control" style="width: 140px;">
            <option>{{ $city }}</option>
        </select>
        <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="intézmény neve"/>
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Keresés</button>
        </div>
    </form>
@endsection
@extends('admin')

{{ $table }}
<script>
    $(() => {
        $("[name=city]").citySelect();
    });
</script>
