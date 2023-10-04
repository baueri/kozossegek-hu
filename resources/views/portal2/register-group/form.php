@extends('portal2.main')
<section id="register-group-title" class="section section-with-bg is-bold" xmlns:com="http://www.w3.org/1999/html">
    <div class="container pt-6 pb-6">
        <h1 class="title has-text-white">Közösség regisztráció</h1>
    </div>
</section>
<section id="register-notification" class="section">
    <div class="container">
        @notification('warning')
            <i class="fa fa-exclamation-triangle mr-2"></i>
            Fontos számunkra, hogy az oldalon valóban keresztény értékeket közvetítő közösségeket hirdessünk.<br/> Mielőtt kitöltenéd a regisztrációs űrlapot, kérjük, hogy mindenképp olvasd el az <a href="@route('portal.page', ['slug' => 'iranyelveink'])">irányelveinket</a>.
        @endnotification
    </div>
</section>
<section>
    <div class="container">
        <fieldset class="box is-always-shady" style="border: 1px solid #ddd">
            <legend class="is-size-4 pr-2 pl-2">Felhasználó adatai</legend>
            <com:horizontal-input type="text" label="Neved" required="1" name="user_name"/>
            <com:horizontal-input type="email" label="Email címed" required="1" name="email" />
            <com:horizontal-input type="tel" label="Telefonszám" name="phone_number" info="Nem kötelező, de a könnyebb kapcsolattartás érdekében megadhatod a telefonszámodat is" />
            <com:horizontal-input type="password" label="Jelszó" required="1" name="password" />
            <com:horizontal-input type="password" label="Jelszó még egyszer" required="1" name="password_again" />
        </fieldset>
        <fieldset class="box is-always-shady" style="border: 1px solid #ddd">
            <legend class="is-size-4 pr-2 pl-2">Általános adatok</legend>
            <com:horizontal-input type="text" label="Közösség neve" required="1" name="user_name"/>
            <com:horizontal-input type="text" label="Közösségvezető(k) neve(i)" required="1" name="email" />
            <com:horizontal-input type="text" label="Intézmény / plébánia" name="phone_number" size=""/>
            <com:horizontal-input type="password" label="Jelszó" required="1" name="password" />
            <com:horizontal-input type="password" label="Jelszó még egyszer" required="1" name="password_again" />
        </fieldset>
    </div>
</section>