
@extends('admin')

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
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
</form>

@if($my_profile)
    <form method="post" id="change_password" action="@route('admin.user.profile.change_password')"  autocomplete="off">
        <div class="row"><div class="col-md-3">
            <h6>Jelszó</h6>
            <hr>
            <div class="form-group">
                <label>Régi jelszó</label>
                <input type="password" name="old_password" class="form-control" autocomplete="off"/>
            </div>
            <div class="form-group">
                <label>Új jelszó</label>
                <input type="password" name="new_password" id="new_password" class="form-control" autocomplete="new-password"/>
            </div>
            <div class="form-group">
                <label>Jelszó még egyszer</label>
                <input type="password" name="new_password_again" id="new_password_again" class="form-control" autocomplete="new-password"/>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-key"></i> Jelszócsere</button>
        </div></div>
    </form>
    <script>
        $(()=>{
            $("#change_password").submit(function(e){
                $("input", $(this)).removeClass("is-valid is-invalid");
                var new_password = $("#new_password").val();
                var new_password_again = $("#new_password_again").val();
                if (!new_password || !new_password_again || new_password != new_password_again) {
                    $("#new_password, #new_password_again", $(this)).addClass("is-invalid");
                    e.preventDefault();
                }
            });
        })
    </script>
@endif
