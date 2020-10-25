@extends('portal')
<div class="container inner">
    <h2>Profilom</h2>
    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item"><a href="">Profilom</a></li>
                <li class="list-group-item"><a href="">Közösségem</a></li>
                <li class="list-group-item"><a href="@route('logout')" class="text-danger">Kijelentkezés</a></li>

            </ul>
        </div>
        <div class="col-md-9">
            <form method="post" action="{{ $action }}" class="mb-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Felhasználónév</label>
                            <input type="text" name="username" value="{{ $user->username }}" class="form-control disabled" disabled/>
                        </div>
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