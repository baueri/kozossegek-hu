<?php use_default_header_bg(); ?>
@section('header_content')
    @featuredTitle('Új jelszó igénylése')
@endsection
@extends('portal')
<div class="container inner p-3">
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