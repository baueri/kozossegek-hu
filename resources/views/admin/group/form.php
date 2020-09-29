@section('header')
    @include('asset_groups.select2')
    @include('asset_groups.editor')
@endsection
@extends('admin')
<form method="post" action="{{ $action }}">
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <label for="name">Közösség neve</label>
                <input type="text" id="name" value='{{ $group->name }}' name="name" class="form-control" autofocus>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="city">Város / település</label>
                        <select name="city" style="width:100%" class="form-control">
                            <option value="{{ $group->city }}">{{ $group->city ?: 'város' }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="institute_id">Intézmény / plébánia</label>
                        <select name="institute_id" style="width:100%" class="form-control">
                            <option value="{{ $institute->id }}">{{ $institute->name ?: 'intézmény' }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="group_leaders">Közösségvezető(k)</label>
                        <input type="text" name="group_leaders" id="group_leaders" class="form-control" value="{{ $group->group_leaders }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="group_leader_email">Telefon</label>
                        <input type="tel" name="group_leader_phone" id="group_leader_phone" value="{{ $group->group_leader_phone }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="group_leader_email">Email cím</label>
                        <input type="email" name="group_leader_email" id="group_leader_email" value="{{ $group->group_leader_email }}" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Leírás</label>
                <textarea name="description" id="description">{{ $group->description }}</textarea>
            </div>
        </div>
        <div class="col-md-3 group-side-content">
            <div class="form-group">
                <label for="status">Állapot</label>
                <select id="status" name="status" class="form-control">
                    @foreach($statuses as $status)
                    <option value="{{ $status->name }}" {{ $group->status == $status->name ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="denomination">Felekezet</label>
                <select class="form-control" name="denomination">
                    @foreach($denominations as $denomination)
                    <option value="{{ $denomination->name }}">{{ $denomination }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="age_group">Korosztály</label>
                <select class="form-control" name="age_group">
                    @foreach($age_groups as $age_group)
                    <option value="{{ $age_group->name }}">{{ $age_group }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="occasion_frequency">Alkalmak gyakorisága</label>
                <select class="form-control" name="occasion_frequency">
                    @foreach($occasion_frequencies as $occasion_frequency)
                    <option value="{{ $occasion_frequency->name }}">{{ $occasion_frequency }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="spiritual_movement">Lelkiségi mozgalom</label>
                <input type="text" name="spiritual_movement" id="spiritual_movement" value="{{ $group->spiritual_movement }}" class="form-control">
            </div>
            <div class="form-group text-center">
                <label for="image" style="cursor: pointer">
                    Fénykép<br>
                    <i class="fa fa-image" style="font-size: 5rem"></i>
                    <input type="file" id="image" name="image" style="display: none">
                </label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
        </div>
    </div>
</form>

<script>
    $(() => {
        $("[name=city]").select2({
            placeholder: "város",
            allowClear: true,
            ajax: {
                url: "{{ route('api.search-city') }}",
                dataType: 'json',
                delay: 300
            }
        });

        $("[name=institute_id]").select2({
            placeholder: "intézmény",
            allowClear: true,
            ajax: {
                url: "{{ route('api.search-institute') }}",
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    params.city = $("[name=city]").val();
                    return params;
                }
            }
        });
        initSummernote('[name=description]');

        $(".group-side-content select").select2();
    });
</script>