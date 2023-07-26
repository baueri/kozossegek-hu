@extends('portal')

<div class="container inner p-4 page">
    <h1>Fiók törlése</h1>
    <p>Amennyiben meg szeretnéd szüntetni honlapunkon a fiókodat, az alábbi lépésekben megteheted azt:</p>
    <ul>
        <li><a href="@route('login')">Lépj be</a> a kozossegek.hu oldalra a fiókoddal</li>
        <li>Menj a <a href="@route('portal.my_profile')">Fiókom</a> oldalra</li>
        <li>Kattints az oldal jobb alsó részén található <b>Fiókom törlése</b> gombra</li>
        <li>A felugró ablakban megjelenő mezőbe írd be a jelenlegi jelstavad szándékod megerősítéseképp, majd kattints az "Ok" gombra</li>
    </ul>

    Ha elakadtál a folyamat valamely szakaszával, kérjük <a href="@route('portal.page', 'rolunk')#contact"><b>lépj velünk kapcsolatba</b></a>!
</div>