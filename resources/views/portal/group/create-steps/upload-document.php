@extends('portal.group.create-steps.create-wrapper')
<h2>Igazolás feltöltése</h2>
<div class="alert alert-warning">
    Ahhoz, hogy biztosítani tudjuk az oldalon a hiteles, valóban működő és keresztény értékeket képviselő közösségeket, arra kérünk, hogy az <a href="">innen letölthető</a> igazolást
    a plébánia (vagy intézmény) vezetőjével aláíratva és lepecsételve töltsd fel a weboldalunkra.
    <br>
    <br>
    <p class="text-center">
        <strong>Ezt a lépést a regisztráció befejezéséhez kihagyhatod, de később mindenképp töltsd fel a kitöltött dokumentumot.</strong>
    </p>
</div>
<form method="post" action="">
    <label for="document" id="document-upload">Húz ide a fájlt a gépedről, vagy kattints ide a fájl kiválasztásához</label>
    <input type="file" style="display: none" id="document">
</form>
<style>
    #document-upload {
        text-align: center;
        color: #999;
        display: block;
        padding: 3em;
        border: 4px dashed var(--primary);
        cursor: pointer;
    }
</style>