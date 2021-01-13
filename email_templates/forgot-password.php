@extends('mail.wrapper')

<p>
    <b>Jelszó visszállítási kérelem.</b><br> Amennyiben nem te igényelted a jelszavad visszaállítását, kérjük hagyd figyelmen kívül ezt az emailt.
</p>
<p><b>Kedves {{ $name }}!</b></p>
<p>
    A jelszavad megváltoztatásához kattints erre a linkre:<br>
    {{ $user_token }}
</p>
<p>A fenti link 24 óráig érvényes.</p>
