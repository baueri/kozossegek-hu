@title('Lelkiségi mozgalmak')
@extends('admin')
@filter_box()
<form>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group"><input type="text" class="form-control" name="name" placeholder="Keresés" value="{{ $name }}"></div>
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-md-4">
            <div class="form-group form-inline">
                <input type="radio" class="form-check-input" id="type_sm"
                       name="type" value="spiritual_movement" @checked($type === 'spiritual_movement')>
                <label class="form-check-label mr-3" for="type_sm">Lelkiségi mozgalmak</label>
                <input type="radio" class="form-check-input" id="type_mc"
                       name="type" value="monastic_community" @checked($type === 'monastic_community')>
                <label class="form-check-label" for="type_mc">Szerzetesrendek</label>
            </div>
        </div>
    </div>

    <button class="btn btn-primary">@icon('search') Keres</button>
</form>
@endfilter_box
<div class="row">
    <div class="col-md-7">
        {{ $table }}
    </div>
</div>
