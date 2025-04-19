@title('Felhasználók')
@extends('admin')
<form method="get">
    @filter_box()
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Keresés névre, email címre</label>
                    <input type="text" class="form-control" name="search" value="{{ $table->request['search'] }}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Jogosultság</label>
                    @user_role_selector($selected_user_role)
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Online</label>
                    @component('base_selector', [
                        'name' => 'online',
                        'placeholder' => '-- Mind --',
                        'values' => ['logged_in' => 'Belépve'],
                        'selected_value' => $online
                    ])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary" type="submit">keresés</button>
            </div>
        </div>
    @endfilter_box
</form>
{{ $table }}
