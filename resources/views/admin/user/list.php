@title('Felhasználók')
@extends('admin')
<form method="get">
    @filter_box()
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="keresés névre, email címre" name="search" value="{{ $table->request['search'] }}">
            </div>
            <div class="col-md-2">
                @user_group_selector($selected_user_group)
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" type="submit">keresés</button>
            </div>
        </div>
    @endfilter_box
</form>
{{ $table }}
