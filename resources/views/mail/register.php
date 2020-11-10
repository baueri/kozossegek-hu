@extends('mail.wrapper')
<div>
    <div style="text-align: center"><img src="https://kozossegek.hu/images/logo.png" style="max-width: 200px;"></div>
    <p><strong>Kedves {{ $user->name }}!</strong></p>
    <p>Sikeres regisztráció a kozossegek.hu oldalon!</p>
    <p>A regisztráció befejezéséhez kérjük kattints az alábbi linkre!</p>
    <a href="{{ $password_reset->getUrl() }}" style="padding: .4em .8em; border-radius: 4px; display: inline-block; background: #dc3545; color: #fff; text-decoration: none; font-family: sans-serif">regisztráció megerősítése</a>
</div>
