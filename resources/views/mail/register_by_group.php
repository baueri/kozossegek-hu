@extends('mail.wrapper')
<div style="text-align: justify;">
    <p><strong>Kedves {{ $user->name }}!</strong></p>
    <p>Üdvözlünk a közösségek.hu oldalán!</p>
    <p>Azért kaptad ezt az emailt, mert a te, vagy a plébánosod jóváhagyásával a jelenleg általad vezetett közösség adait <b>({{ $group_name_with_city }})</b> bevittük a https://kozossegek.hu oldal rendszerébe.</p>
    <p><b>Ha még nem sokat hallottál az új kezdeményezésünkről:</b> A kozossegek.hu szegedi katolikus fiatal felnőttek alulról szerveződő önkéntes kezdeményezése, amely keresztény közösségeket gyűjt össze szerte az országban, melyekre honlapunkon keresztül rá lehet keresni és érdeklődés esetén fel lehet venni a közösségvezetőkkel a kapcsolatot csatlakozás céljából.</p>
    
    <p>A weboldalon létrehoztunk neked egy felhasználói fiókot, amivel a lenti linken keresztüli aktiválás után megtekintheted illetve szerkesztheted az általad vezetett közösség adatait.</p>
    <p>A regisztráció befejezéséhez kérjük kattints az alábbi linkre!</p>
    <p style="text-align: center;">
        <a href="{{ $password_reset->getUrl() }}" style="padding: .4em .8em; border-radius: 4px; display: inline-block; background: #dc3545; color: #fff; text-decoration: none; font-family: sans-serif">regisztráció megerősítése</a>
    </p>
</div>
