@section('header')
    @include('asset_groups.select2')
    @include('asset_groups.editor')
@endsection
@extends('portal.group.create-steps.create-wrapper')
<h2>Közösség adatainak megadása</h2>
<div class="alert alert-warning">
    Fontos számunkra, hogy az oldalon valóban keresztény értékeket közvetítő közösségeket hirdessünk. Mielőtt kitöltenéd a regisztrációs űrlapot, kérjük, hogy mindenképp olvasd el az
    <a href="">irányelveinket</a>, és miután megbizonyosodtál, hogy...
</div>
<form method="post" id="group-form">
    <input type="hidden" name="next_step" value="3">
    <div class="form-group required">
        <label for="name">Közösség neve</label>
        <input type="text" id="name" value='' name="name" class="form-control" required="">
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group required">
                <label for="institute_id">Intézmény / plébánia</label>
                <select name="institute_id" style="width:100%" class="form-control" required>
                    <option value="{{ $group->institute_id }}">
                        {{ $group->institute_id ? $group->institute_name . ' (' . $group->city . ')' : 'intézmény' }}
                    </option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group required">
                <label for="age_group">Korosztály <small>(legalább egyet adj meg)</small></label>
                @age_group_selector($age_group_array)
                
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group required">
                <label for="occasion_frequency">Alkalmak gyakorisága</label>
                @occasion_frequency_selector($group->occasion_frequency)
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="on_days">Mely napo(ko)n</label>
                @on_days_selector($group_days)
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="spiritual_movement_id">Lelkiségi mozgalom</label>
        @spiritual_movement_selector($group->spiritual_movement_id)
    </div>
    <div class="form-group">
        <label>A közösség jellemzői</label>
        <div>
            @foreach($tags as $tag)
            <label class="mr-2" for="tag-{{ $tag['id'] }}">
                <input type="checkbox"
                       name="tags[]"
                       id="tag-{{$tag['id']}}"
                       value="{{ $tag['slug'] }}"
                       @if(in_array($tag['slug'], $group_tags)) checked @endif
                       > {{ $tag['tag'] }}
            </label>
            @endforeach
        </div>
    </div>

    <div class="form-group required">
        <label for="description">Leírás</label>
        <textarea name="description" id="description">{{ $group->description }}</textarea>
    </div>

    <h3 class="h4 mt-3">Közösségvezető(k) adatai</h3>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group required">
                <label for="group_leaders">Közösségvezető(k) neve(i)</label>
                <input type="text" name="group_leaders" id="group_leaders" class="form-control" value="{{ $group->group_leaders ?: $user->name }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="group_leader_phone">Elérhetőség (Telefon)</label>
                <input type="tel" name="group_leader_phone" id="group_leader_phone" value="{{ $group->group_leader_phone }}" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required">
                <label for="group_leader_email">Elérhetőség (Email cím)</label>
                <input type="email" name="group_leader_email" id="group_leader_email" value="{{ $group->group_leader_email ?: $user->email }}" class="form-control">
            </div>
        </div>
    </div>
    <div class="row group-images">
        <div class="col-md-12">
            <div class="form-group">
                <label>Fotó a közösségről <small>(Ha ezt nem adod meg, akkor az intézmény fotója jelenik meg)</small></label>
                <div class="group-image">
                    <img src="{{ $images ? $images[0] . '?' . time() : '' }}" id="image" width="300">
                </div>
                <label for="image-upload" class="btn btn-primary">
                    <i class="fa fa-upload"></i> Kép feltöltése
                    <input type="file" onchange="loadFile(event, this);" data-target="temp-image" id="image-upload">
                </label>
                <div style="display: none"><img id="temp-image" /></div>
                <input type="hidden" name="image">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success">Tovább</button>
</form>
<script>
    $(() => {
        $("[name=institute_id]").instituteSelect();
        
        $("[name=spiritual_movement_id]").select2({
            placeholder: "lelkiségi mozgalom",
            allowClear: true,
        });

        $("[name=institute_id]").instituteSelect();

        initSummernote('[name=description]', {
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['help']]
            ]
        });

    });
</script>