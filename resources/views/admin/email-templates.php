@title('Email sablonok')
@extends('admin')
<table class="table table-sm table-striped table-responsive">
    <thead>
    <tr>
        <th>Név</th>
        <th>Leírás</th>
    </tr>
    </thead>
    <tr>
        <td><a href="@route('admin.email_template.registration')">Fiók regisztrációs sablon</a></td>
        <td>Admin felületen manuálisan létrehozott felhasználók kapják meg.</td>
    </tr>
    <tr>
        <td><a href="@route('admin.email_template.register_by_group')">Fiók regisztrációs sablon (Közösség kapcsolattartó alapján)</a></td>
        <td>Admin felületen adott közösségnek a Kapcsolattartó neve és a Kapcsolattartó email címe alapján létrehozott felhasználóknak küldjük ki.</td>
    </tr>
    <tr>
        <td><a href="@route('admin.email_template.reset_password')">Elfelejtett jelszó</a></td>
        <td>Felhasználói oldalon az elfelejtett jelszó űrlapon kiküldött email</td>
    </tr>
    <tr>
        <td><a href="@route('admin.email_template.group_contact')">Közösségvezetői kapcsolatfelvételi sablon</a></td>
        <td>Felhasználói oldalon, adott közösség adatlapján kitöltött kapcsolatfvelvevő űrlap alapján küljük ki a közösségvezetőnek.</td>
    </tr>
    <tr>
        <td><a href="@route('admin.email_template.created_group_with_new_user')">Új közösség létrehozása (új fiókkal)</a></td>
        <td>Felhasználói oldaon sikeres közösség regisztrálása után küldük ki akkor, ha a felhasználói fiók is akkor jött létre.</td>
    </tr>
    <tr>
        <td><a href="@route('admin.email_template.created_group')">Új közösség létrehozása (létező fiókkal)</a></td>
        <td>Felhasználói oldalon a már regisztrált és belépett felhasználónak küldjük ki, amikor új közösséget regisztrál</td>
    </tr>
</table>
