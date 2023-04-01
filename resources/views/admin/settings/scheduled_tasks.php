@title('Háttérfolyamatok')
@extends('admin')
<div class="row">
    <div class="col-10">
        @alert('info')
        <b>Figyelem!</b> Ez az oldal csupán kilistázza, hogy a Napi és Havi cron command-ok milyen folyamatokat futtanak le, de ez nem jelenti azt, hogy a cron éppen fut!<br/>
        Ahhoz, hogy ezek a folyamatok ténylegesen időzítve legyenek, a szerveren kell beállítani az időzítést.
        @endalert

        <h3>Naponta futó feladatok</h3>
        {{ $daily }}
        <h3>Havonta futó feladatok</h3>
        {{ $monthly }}
    </div>
</div>

<style>
    th.signature {
        width: 300px;
    }
</style>