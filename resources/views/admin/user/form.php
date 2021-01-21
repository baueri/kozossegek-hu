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
                <label>Felhasználói jogkör</label>
                <select name="user_group" class="form-control">
                    <option value="GROUP_LEADER" {{ $user->user_group == 'GROUP_LEADER' ? 'selected' : '' }}>Közösségvezető</option>
                    <option value="SUPER_ADMIN" {{ $user->user_group == 'SUPER_ADMIN' ? 'selected' : '' }}>Super admin</option>
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
