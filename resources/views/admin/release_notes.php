@title('Verzióinformáció')
@extends('admin')

<h3>v0.2 pre-alpha</h3>
<ul>
    <li>[NEW]: Intézmény importálás (szinkronizálás)</li>
    <li>[NEW]: Fájfeltöltés, képbeszúrás cikkbe</li>
    <li>[CHANGED]: 'Város' szöveget lecseréltem 'település'-re</li>
    <li>[FIXED]: 500-as hiba, ha olyan kulcsszavakra keresünk, amik közül egyik se címke</li>
    <li>[FIXED]: Közösség admin oldalon is kötelezővé tettem azokat a mezőket, amik az adatbázisban nem lehetnek érték nélküliek</li>
</ul>
<h3>v0.1.2 pre-alpha</h3>
<ul>
    <li>[NEW]: Admin oldalon email sabolonok megtekintése. <a href="@route('admin.email_template.list')">Ugrás a sablonokhoz</a>.</li>
    <li>[NEW]: Admin oldali fiók regisztrációkor aktiváló email küldése az új felhasználónak</li>
    <li>[NEW]: Demo környezet alatt bekerült egy "debug sáv" az oldalak aljára, ez segít a fejlesztésben, hibajavításban</li>
    <li>[NEW]: "Közösséget keresek" oldalon a találatok elrendezése át lett alakítva
        <ul>
            <li>+1 bekerült a találati lista fölé jobbra két ikon két nézet váltásához</li>
        </ul>
    </li>
    <li>[CHANGED]: Látogatói oldalon a felső menübe bekerült egy user ikon
        <ul>
            <li>Ha be van lépve valaki, akkor megjelenik alatta a szokásos almenü</li>
            <li>Egyébként meg a login oldalra visz</li>
        </ul>
    </li>
    <li>[CHANGED]: Közösség "aktív" státusz át lett nevezve: "közzétéve"</li>
    <li>[CHANGED]: "Közösséget keresek" oldalon a kereső input mező leghátulra került</li>
    <li>[CHANGED]: Szintén a "közösséget keresek" oldalon a város címsorok lekerültek</li>
    <li>[FIXED]: Admin oldalon a közösségek listázásánál volt egy kis lekérdezés-beli kavarodás, javítva lett</li>
    <li>[FIXED]: Lomtárba helyezett(törölt) közösséget vissza lehet állítani nem töröltté</li>
    <li>[FIXED]: Vissza lapozó javítva lett</li>
</ul>
<h3>v0.1.1 pre-alpha</h3>
<ul>
    <li>[NEW]: Elfelejtett jelszó funkció fejlesztése</li>
    <li>[NEW:] Látogatói oldalon új közösség létrehozása</li>
    <li>[CHANGED]: Logó átalakítás: Magát a logót kicsit kisebbre vettem, a szöveget pedig nagyobbra.</li>
    <li>[CHANGED]: Layout módosítások</li>
    <li>[FIXED]: Csak a megtekinthető - tehát aktív, nem törölt, nem függőben levő - közösségek lettek kereshetőek, megtekinthetőek</li>
</ul>

<h3>v0.1.0 pre-alpha</h3>
<ul>
    <li>Új felhasználó létrehozása admin oldalon</li>
    <li>
        Felhasználói jogkör fejlesztés
        <ul>
            <li>Felhasználó admin oldalon jogkör beállítása felhasználónak</li>
            <li>Csak Szuper Admin léphet be az adminisztrációs oldalra</li>
        </ul>
    </li>
    <li>Látogatói oldalon belépett felhasználó profil módosítása</li>
    <li>Látogatói oldalon belépett felhasználó közösségének adatainak módosítása</li>
    <li>Közösség admin szerkesztő oldalon:
        <ul>
            <li>Felhasználó (karbantartó) hozzáadása közösséghez</li>
            <li>'Jóváhagyva' állapot külön kapcsolható a státusztól függetlenül</li>
        </ul>
    </li>
    <li>dizájn módosítások</li>
    <li>Login felület beágyazása a látogatói oldal dizájnjába</li>
    <li>
        <b>Tervbe véve:</b> Felhasználói oldalon közösség létrehozása
    </li>
</ul>

<h3>v0.0.8 pre-alpha</h3>
<ul>
    <li>Keresőmotor elkészítése (szabadszavas keresés)
        <ul>
            <li>Elkészült a keresőmotor kezdeti verziója</li>
            <li>A főoldalon most már csak egy keresőmező van, ami a megadott kulcsszavak alapján relevancia szerint sorba rendezve listázza kis a talált közösségeket</li>
            <li>Új közösség létrehozásakor/frissítésekor lefut egy kód, ami frissíti a generált kulcsszavakat a keresőmotor táblájában</li>
            <li>A <a href="@route('admin.group.list')">közösségek</a> admin oldalra bekerült egy "keresőmotor frissítése" gomb, ami az összes közösség kulcsszó-halmazát frissíti.<br>
                <small class="text-danger">Kis adatmennyiségnél ez még gyorsan lefut, de ha megnő a közösségek száma, akkor erre majd egy naponta háttérben futó szkriptet kell írni, hogy ne akassza le a weblapot.</small>
            </li>
        </ul>
    </li>
    <li>Widgetek fejlesztése
        <ul>
            <li>Szövegdobozokat lehet létrehozni, amiket a honlap különböző részeire lehet beágyazni.</li>
        </ul>
    </li>
    <li>"Korosztálytól független" korcsoport kiszedve</li>
    <li>Lábléc jobb oldali szekció cseréje partnerek szekcióra</li>
    <li>Rendszerjavítások</li>
</ul>

<h3>v0.0.7 pre-alpha</h3>
<ul>
    <li>címke javítás</li>
    <li>front-end módosítások</li>
    <li>Hasonló közösségek funkció egy közösség adatlapja alatt</li>
    <li>képfeltöltés intézményhez</li>
    <li>keresés intézmények között admin oldalon</li>
    <li>Most már lehet azt is megadni, hogy egy közösség mely napokon tartja az alkalmakat</li>
    <li>Gépház alól kivéve a "karbantartás" gomb</li>
</ul>

<h3>v0.0.6 pre-alpha</h3>
<ul>
    <li>Címkék, csoporthoz tartozó címkék szerkezeti átalakítása</li>
    <li>Címkék mentése, létrehozása, törlése</li>
    <li>Keresés címke szerint</li>
    <li>Keretrendszer fejlesztések</li>
    <li>Mappaszerkezeti refaktorálás</li>
</ul>


<h3>v0.0.3 pre-alpha</h3>
<ul>
    <li>Csoport jellemzők szerkeszthetősége</li>
    <li>Több korosztály is választható legyen</li>
    <li>Keretrendszer javítások</li>
    <li>Admin oldal mobilbaráttá alakítása</li>
</ul>


<h3>v0.0.2 pre-alpha</h3>
<ul>
    <li>Javítások</li>
    <li>Címke tábla migráció, feltöltés adattal</li>
    <li>Lelkiségi mozagalmas tábla migráció, feltöltés adattal</li>
    <li>Címkék, mozgalmak admin felület fejlesztése</li>
    <li>Közösségek tábla szerkezetének átalakítása, nézettábla átalakítás</li>
</ul>


<h3>v0.0.1 pre-alpha</h3>
<ul>
    <li>Keretrendszer fejlesztése</li>
    <li>kezdeti adatbázis migrációk létrehozása</li>
    <li>Admin felület fejlesztés</li>
    <li>Admin felület előkészítése adatfelvitelre</li>
    <li>Látogatói oldal első verziójának elkészítése</li>
    <li>Autentikáció</li>
    <li>Hibanaplózás, Hibakezelés (email küldés, felhasználóbarát hibaoldal megjelenítés...)</li>
</ul>
