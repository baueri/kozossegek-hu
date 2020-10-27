@extends('portal')
<div class="container inner">
    <div class="row">
        <div class="col-md-3">
            @include('portal.partials.user-sidemenu')
        </div>
        <div class="col-md-9">
            <h2>Profilom</h2>
            <form method="post" action="{{ $action }}" class="mb-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>E-mail cím</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Név</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control"/>
                        </div>
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
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>