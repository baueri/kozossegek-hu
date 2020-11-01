@extends('mail.wrapper')

<p>
    <b>Jelszó visszállítási kérelem.</b> Amennyiben nem te igényelted a jelszavad visszaállítását, kérjük hagyd figyelmen kívül ezt az emailt.
</p>
<p><b>Kedves {{ $user->name }}!</b></p>
<p>
    Az új jelszavad megadásához kattints erre a linkre:<br>
    {{ $url }}
</p>
<p>A fenti link 24 óráig érvényes.</p>
