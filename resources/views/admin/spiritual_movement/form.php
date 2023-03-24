@section('header')
    @include('asset_groups.editor')
@endsection
@extends('admin')
<form method="post" action="{{ $action }}">
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label >Név</label>
                <input type="text" class="form-control" name="name" value="{{ $spiritualMovement->name }}"/>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Típus</label>
                <select class="form-control" name="type">
                    <option value="spiritual_movement" @selected($spiritualMovement->type === 'spiritual_movement')>Lelkiségi mozgalom</option>
                    <option value="monastic_community" @selected($spiritualMovement->type === 'monastic_community')>Szerzetesrend</option>
                </select>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label>Keresőbarát url</label>
                <input type="text" class="form-control" value="{{ $spiritualMovement->slug }}" disabled/>
            </div>
            <div>
                <label>Leírás</label>
                <textarea name="description">{{ $spiritualMovement->description }}</textarea>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Kiemelt</label>
                <div class="switch yesno" style="width:100px;">
                    <input type="radio" id="highlighted-0" name="highlighted" value="1" @if($spiritualMovement->highlighted) checked @endif>
                    <input type="radio" id="highlighted-1" name="highlighted" value="0" @if(!$spiritualMovement->highlighted) checked @endif>
                    <label for="highlighted-1">igen</label>
                    <label for="highlighted-0">nem</label>
                </div>
            </div>
            <div class="form-group">
                <label>Weboldal</label>
                <input type="text" name="website" class="form-control" value="{{ $spiritualMovement->website }}">
            </div>
            <div class="form-group">
                <label>Kép, logó</label><br/>
                <button type="button" class="btn btn-secondary set-image mb-2">@icon('image') Kép kiválasztása</button>
                <div class="selected-image">
                    @if($spiritualMovement->image_url)
                    <img src="{{ $spiritualMovement->image_url }}" class="mb-2"/>
                    @endif
                </div>
                <input type="text" class="form-control image-url" name="image_url" value="{{ $spiritualMovement->image_url }}"/>
            </div>
            <button type="submit" class="btn btn-primary">@icon('save') Mentés</button>
        </div>
    </div>
</form>
<script>
    $(() => {
        initSummernote('[name=description]');

        $(".set-image").click(() => {
            selectImageFromMediaLibrary({
                onSelect: (selected) => {
                    $(".selected-image").html("<img src='" + selected.src + "'/>");
                    $(".image-url").val(selected.src);
                }
            });
        })
    });
</script>