@extends('mail.wrapper')
<h4>Kedves {{ $user_name }}!</h4>
<p>Üdvözlünk a kozossegek.hu oldalán</p>
<p>Azért kaptad ezt a levelet, mert nemrég regisztráltad a közösségedet a honlapunkon. A közösség jóváhagyása folyamatban van, addig is kérjük, hogy aktiváld a fiókodat az alábbi linkre kattintva:</p><p>{{ $token_url }}</p>
