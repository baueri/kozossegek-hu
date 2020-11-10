@title('Új felhasználó')
@extends('admin')
<div class="alert alert-warning">
    Az új felhasználónak küldünk egy fiók aktiváló linket tartalmazó email-t, amire kattintva be tudja állítani a jelszavát.
</div>
@include('admin.user.form')