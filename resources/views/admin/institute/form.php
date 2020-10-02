@section('header')
    @include('asset_groups.select2')
@endsection
@extends('admin')
<form method="post" class="row" action="{{ $action }}">
    <div class="col-md-4">
        <div class="form-group">
            <label>Intézmény / plébánia neve</label>
            <input type="text" name="name" class="form-control" value="{{ $institute->name }}">
        </div>
        <div class="form-group">
            <label>Város</label>
            <select name="city" class="form-control">
                <option value="{{ $institute->city }}">{{ $institute->city }}</option>
            </select>
        </div>
        <div class="form-group">
            <label>Városrész</label>
            <select name="district" class="form-control">
                <option value="{{ $institute->district }}">{{ $institute->district }}</option>
            </select>
        </div>
        <div class="form-group">
            <label>Cím</label>
            <input type="text" name="address" class="form-control" value="{{ $institute->address }}">
        </div>
        <div class="form-group">
            <label>Intézményvezető / plébános neve</label>
            <input type="text" name="leader_name" class="form-control" value="{{ $institute->leader_name }}">
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
    </div>
</form>
<script>
    $(() => {
        $("[name=city]").citySelect();
        $("[name=district]").districtSelect({city_selector: "[name=city]"});
    });
</script>
