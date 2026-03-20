@extends('portal')
@featuredTitle('Új jelszó igénylése')
<div class="container inner py-5">
    @message()
    <p>
        Add meg a fiókodhoz tartozó email címedet, amire küldünk egy levelet a további lépésekkel kapcsolatban!
    </p>
    <form method="post" class="row" action="@route('portal.reset_password')">
        @csrf()
        <div class="col-md-4">
            <div class="form-group required">
                <label>Email címed</label>
                <input type="email" class="form-control" name="email" required/>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Új jelszó igénylése">
            </div>
        </div>
    </form>
</div>
