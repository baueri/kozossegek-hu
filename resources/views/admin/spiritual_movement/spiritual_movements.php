@title('Lelkiségi mozgalmak')
@extends('admin')
@filter_box()
<form>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group"><input type="text" class="form-control" name="name" placeholder="Keresés" value="{{ $name }}"></div>
        </div>
        <div class="col-md-3"><button class="btn btn-primary">@icon('search') Keres</button></div>
    </div>
</form>
@endfilter_box
<div class="row">
    <div class="col-md-7">
        {{ $table }}
    </div>
</div>
