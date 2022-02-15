@if($user->deleted_at)
    @alert('danger')
        <b>Törölt fiók!</b>
    @endalert
@endif
<form method="post" action="{{ $action }}" class="mb-4">
    <div class="row">
        <div class="col-sm-4 col-lg-3">
            <div class="form-group">
                <label>Név</label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control"/>
            </div>
            <div class="form-group">
                <label>Felhasználónév</label>
                <input type="text" name="username" value="{{ $user->username }}" class="form-control"/>
            </div>
            <div class="form-group">
                <label>E-mail cím</label>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control"/>
            </div>
            <div class="form-group">
                <label for="phone_number">Telefon</label>
                <input type="tel" name="phone_number" id="phone_number" value="{{ $user->phone_number }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Felhasználói jogkör</label>
                <select name="user_group" class="form-control">
                    @foreach($groups as $group => $group_text)
                        <option value="{{ $group }}" {{ $group === $user->user_group ? 'selected' : '' }}>{{ $group_text }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="spiritual_movement_id">Lelkiségi mozgalom</label>
                <select id="spiritual_movement_id" name="spiritual_movement_id" class="form-control" data-allow-clear="1" data-placeholder="Nincs megadva">
                    <option></option>
                    @foreach($spiritual_movements as $spiritual_movement)
                    <option value="{{ $spiritual_movement['id'] }}" @if($user_movement == $spiritual_movement['id']) selected @endif>
                        {{ $spiritual_movement['name'] }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            @if($user->exists())
                <h6>Jelszó</h6>
                <hr>
                <div class="form-group">
                    <label>Új jelszó</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" autocomplete="new-password"/>
                </div>
                <div class="form-group">
                    <label>Jelszó még egyszer</label>
                    <input type="password" name="new_password_again" id="new_password_again" class="form-control" autocomplete="new-password"/>
                </div>
            @endif
        </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
</form>
