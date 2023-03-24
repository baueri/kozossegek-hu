@title('Felhasználó szerkesztése')
@extends('admin')
<div class="btn-group btn-group-sm btn-shadow mb-4">
    <a class="btn active btn-primary" href="@route('admin.user.edit', $user)">Adatlap</a>
    <a class="btn btn-default" href="@route('admin.user.managed_groups', $user)">Kezelt közösségek</a>
</div>
@include('admin.user.form')