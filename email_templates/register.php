@extends('mail.wrapper')
<div>

    <p><strong>Kedves {{ $name }}!</strong></p>
    <p>Sikeres regisztráció a kozossegek.hu oldalon!</p>
    <p>A regisztráció befejezéséhez kérjük kattints az alábbi linkre!</p>
    <a href="{{ $token_url }}" style="padding: .4em .8em; border-radius: 4px; display: inline-block; background: #dc3545; color: #fff; text-decoration: none; font-family: sans-serif">regisztráció megerősítése</a></div><div><br></div><div>Ha nem tudsz a fenti gombra kattintani, akkor másold be ezt a linket a böngésződbe:</div><div>{{ $token_url }}</div>
