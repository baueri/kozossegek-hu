@extends('portal2026.portal')
@featuredTitle('Új jelszó igénylése')
<div class="container inner py-5">
    @message()
    <p>
        Add meg a fiókodhoz tartozó email címedet, amire küldünk egy levelet a további lépésekkel kapcsolatban!
    </p>
    <form method="post" class="row" action="@route('portal.reset_password')">
        @csrf()
        <div class="col-md-4">
            <div class="mb-3 required">
                <label>Email címed</label>
                <input type="email" class="form-control" name="email" required/>
            </div>

            <div class="mb-3">
                <input type="submit" class="btn btn-orange px-4 rounded-pill" value="Új jelszó igénylése">
            </div>
        </div>
    </form>
</div>
