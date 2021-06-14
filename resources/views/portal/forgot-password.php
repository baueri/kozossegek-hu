@extends('portal')
<div class="container inner p-3">
    <h1 class="h2 mb-4">Új jelszó igénylése</h1>
    @message()
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