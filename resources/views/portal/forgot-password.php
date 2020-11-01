@extends('portal')
@featuredTitle('Új jelszó igénylése')
<div class="container p-3">
    <p>
        Add meg a fiókodhoz tartozó email címedet, amire küldünk egy levelet a további lépésekkel kapcsolatban!
    </p>
    <form method="post" class="row" action="@route('portal.reset_password')">

        <div class="col-md-4">
            <div class="form-group">
                <label>Email címed</label>
                <input type="email" class="form-control" name="email"/>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Jelszó visszaállítása">
            </div>
        </div>
    </form>
</div>