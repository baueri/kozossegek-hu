@title('Verzióinformáció')
@extends('admin')
<h3>v1.4.0 (2021.06.24)</h3>
<ul>
    <li>[CHANGED]: Email és telefonszámkezelés át lett alakítva, most már a felhasználóhoz (közösség karbantartóhoz) vannak ezek kapcsolva a közösség helyett</li>
    <li>[CHANGED]: Közösségi és profil oldali űrlapok átalakítva lettek</li>
    <li>[FIXED]: Intézmény és közösség keresésnél javítva lett a lekérdez, amikor valaki speciális karaktert (pl, pont, csillag, kötőjel) írt be a keresőmezőbe.</li>
    <li>[FIXED]: A "közösséget vezetek" oldalon a "van már fiókom" gombra középső egérrel való kattintásra a főoldalra vitt az oldal.</li>
</ul>
<h3>v1.3.1 (2021.06.14)</h3>
<ul>
    <li>[FIXED]: Javítva lett a hiba ami miatt a korosztályszűrő mindig az először kiválaszott korosztály közösségeire szűrt rá</li>
    <li>[FIXED]: Kisebb felbontású képernyőkön (laptop pl) a keresőben a jellemzők kicsúsztak a képről.</li>
    <li>[CHANGED]: Amikor nincs még szűrés megadva, akkor egy alapértelmezett szöveg jelenik meg 'placeholder'-nek.</li>
</ul>
<h3>v1.3.0 (2021.06.08)</h3>
<ul>
    <li>
        [NEW]: Lelkiségi mozgalom aloldal a látogatói oldalra
        <ul>
            <li>Listázás fejlesztés</li>
            <li>adatlap fejlesztés</li>
        </ul>
    </li>
    <li>[CHANGED]: Keretrendszer fejlesztések</li>
</ul>
<h3>v1.2.2 (2021.06.02)</h3>
<ul>
    <li>[CHANGED]: Honlapos kapcsolattartó email cím le lett cserélve</li>
    <li>[CHANGED]: Felekezet választó ki lett szedve a kódból</li>
    <li>[CHANGED]: 404-es illetve HoneyPot-os hibákról nem jönnek most már hiba email-ek</li>
    <li>[CHANGED]: Találati lista oldalon csak akkor jelenik meg, ha adtak meg valamilyen szűrési feltételt.</li>
    <li>[CHANGED]: Találati lista oldalon a közösségek fotóinak magassága növelve lett</li>
    <li>[FIXED]: Találati lista oldalon a közösségek responzív elrendezése optimalizálva lett</li>
    <li>[FIXED]: A 'hasonló közösségek' szekcióban most már csak jóváhagyott aktív közösségek jelennek meg</li>
    <li>[FIXED]: Az általános js és css fájlok elérési útvonala végére bekerült a módosítási idő, hogy újratöltse a böngésző, ha módosulnak a fájlok</li>
    <li>[NEW]: noscript tag tartalom megjelenítése az oldalon, ha a böngészőben nincs engedélyezve a javascript.</li>
</ul>
<h3>v1.2.1 (2021.05.24)</h3>
<ul>
    <li>[CHANGED]: Admin oldali design módosítások</li>
    <li>[CHANGED]: Admin intézményi lista átalakítás</li>
    <li>[CHANGED]: Admin közösségi lista átalakítás</li>
    <li>[CHANGED]: Keretrendszer fejlesztések, javítások</li>
    <li>[CHANGED]: Ikonkészlet (fontawesome) helyi használata CDN helyett</li>
</ul>
<h3>v1.2 (2021.05.19)</h3>
<ul>
    <li>[NEW]: Admin vezérlőpultba bekerült két doboz:
        <ul>
            <li>Statisztikai adatok</li>
            <li>Gyorsfunkciók létrehozáshoz</li>
        </ul>
    </li>
    <li>[NEW]: Eseménynapló a gépházba</li>
    <li>[FIXED]: Email ellenőrző script-ben a reguláris kifejezés javítva lett</li>
    <li>[CHANGED]: Látogatói oldalon (főoldalon és a 'közösséget keresek' oldalon a szűrő átalakításra került</li>
    <li>[CHANGED]: Most már csak akkor naplózzuk a kereséseket, ha valamilyen szűrőt alkalmazott a látogató</li>
    <li>[CHANGED]: Látogatói oldalon a 'Profilom' és 'Közösségeim' oldalak menüje vízszintesre lett alakítva, hogy több hely legyen.</li>
</ul>
<h3>v1.1 (2021.04.19)</h3>
<ul>
    <li>[NEW]: Az info@kozossegek.hu-ra küldött kapcsolatfelvételi üzenetre a levelező kliensben most már lehet közvetlenül válaszolni.</li>
    <li>[NEW]: .well-known/security.txt fájl generálása</li>
    <li>[NEW]: Meta információk elhelyzése a főoldalra</li>
    <li>[FIXED]: "nem vagyok robot" funckió javítása</li>
    <li>[FIXED]: Közösség regisztrációkor / mentéskor nem állítódott be a feltöltött fotó</li>
    <li>[FIXED]: Admin oldali intézménykereső sql hiba javítás</li>
    <li>[FIXED]: Egyes linkek felugró szövegei kattintás után nem tűntek el, ha a link új lapon nyílt meg.</li>
    <li>[FIXED]: Közösség regisztrációs űrlap elemeinek ellenőrzésének javítása</li>
    <li>[CHANGED]: Főoldal átalakítás
        <ul>
            <li>A főoldal most már nem szerkeszthető az admin felületen, csak közvetlenül a fájlban.</li>
        </ul>
    </li>
    <li>[CHANGED]: Hibalevél tartalmának kiegészítése további információkkal</li>
    <li>[CHANGED]: Fejléc menü átalakítás</li>
    <li>[CHANGED]: Közösséget keresek oldal szűrő átalakítás</li>
    <li>[CHANGED]: Code beautifying</li>
    <li>[CHANGED]: .htaccess fájlban tiltásra kerültek a wordpress-es linkek, hogy ne a keretrendszer legyen leterhelve ezekkel a kérésekkel</li>
    <li>[CHANGED]: Látogató oldali dizájn ráncfelvarrás</li>
    <li>[CHANGED]: Partnerek közé bekerült a 72 tanítvány mozgalom</li>
    <li>[CHANGED]: Látogatói oldalon megerősítő felugró ablak megjelenítése saját közösség törlése esetén</li>
</ul>
<h3>v1.0</h3>
<ul>
    <li>[NEW]: Sima fiók regisztráció</li>
    <li>[CHANGED]: Csoport létrehozása űrlapon a gyakoriság alapértelmezetten "hetente"</li>
    <li>[CHANGED]: Admin felhasználók listázás átalakítása</li>
    <li>[CHANGED]: Részletesebb hibaüzenetek küldése emailben</li>
</ul>
<h3>v0.7 beta</h3>
<ul>
    <li>[NEW]: Miserend.hu templomok integrálása a rendszerbe
        <ul>
            <li>miserend.hu-ról integrált intézményeknek lehet képet választani</li>
            <li>webcím, alternatív név megadása</li>
        </ul>
    </li>
    <li>[CHANGED]: Regisztrációs email-be ideiglenes jelszó generálása (fiók aktiválásból jelszó megadás kiszedése)</li>
    <li>[CHANGED]: Tábla indexek beállítása gyorsításhoz</li>
    <li>[CHANGED]: Látogatói oldai átalakítások</li>
    <li>[CHANGED]: Látogatói oldai menü elemek sorrendjének módosítása</li>
    <li>[CHANGED]: Közösség regisztrációnál jelszó bekérése új fiók esetén</li>
    <li>[FIXED]: html template meghívás esemény regisztrálás javítás</li>
    <li>[FIXED]: Keretrendszer javítások, fejlesztések</li>
    <li>[FIXED]: Közösségvezetői email küldés javítás</li>
</ul>
<h3>v0.6 beta</h3>
<ul>
    <li>[NEW]: Facebook megosztási gomb a közösség adatlapján</li>
    <li>[NEW]: Közösség adatlapján a csatlakozás módja is ki van íratva</li>
    <li>[NEW]: Új kisközösség kialakítása aloldal, hozzá az almenü az "A közösségről" alá</li>
    <li>
        [NEW]: SEO optimalizálások
        <ul>
            <li>sitemap.xml és robots.txt bekötése</li>
            <li>meta tag-ek beszúrása aloldalakra és közösségi adatlapokra</li>
        </ul>
    </li>
    <li>[NEW]: Admin oldalon közösségek listázásánál sordbarendezési lehetőségek</li>
    <li>[NEW]: Közösség és intézmény képfeltöltésnél kártékony kód ellenőrzés</li>
    <li>[CHANGED]: Közösség adatlap elrendezés módosítása</li>
    <li>[FIXED]: Template directive felismerés javítása</li>
</ul>
<h3>V0.5 beta</h3>
<ul>
    <li>[NEW]: Közösség regisztrációs oldalon bejelentkzési lehetőség</li>
    <li>[NEW]: Fiók törlése gomb + funkció látogatói oldalon</li>
    <li>[NEW]: Törölt oldal visszaállítása</li>
    <li>[NEW]: Belépésnél request paraméterben megadható, hogy sikeres belépés után hova irányítson át az oldal</li>
    <li>[NEW]: Adminisztrációs oldalon figyelmeztető szövegek törölt sorok (közösségek, oldalak) szerkesztő oldalán</li>
    <li>[CHANGED]: Fiók törlésekor a hozzá tartozó közösség(ek) törlése.</li>
    <li>[CHANGED]: Sikeres fiókaktiválás után automatikus beléptetés</li>
    <li>[CHANGED]: Egy-két helyen szövegcsere</li>
    <li>[CHANGED]: Kódformázások</li>
    <li>[CHANGED]: Keretrendszer fejlesztés, javítás</li>
    <li>[CHANGED]: Profil oldali kisebb igazítások, javítások</li>
    <li>[FIXED]: Igazolás feltöltés nem történt meg regisztrációkor</li>
    <li>[FIXED]: Nem lett email kiküldve sikeres regisztráció esetén</li>
    <li>[FIXED]: Regisztrációnál függőben levő intézmény nem került rögzítésre</li>
    <li>[FIXED]: Közösség frissítésénél nem mentődött el a csatlakozás módja</li>
    <li>[FIXED]: Belépésnél nem volt ellenőrizve, hogy a belépni kívánó felhasználó törölve van-e az adatbázisban</li>
    <li></li>
</ul>
<h3>v0.4 beta</h3>
<ul>
    <li>[NEW]: Teszt üzemmód bekapcsolása</li>
    <li>[NEW]: Süti tájékoztató</li>
    <li>[NEW]: Kapcsolatfelvételi űrlap</li>
    <li>[NEW]: Felhasználói oldalon adott aloldal szerkesztése, ha az admin be van lépve</li>
    <li>[CHANGED]: Nagyobb arculati ráncfelvarrások, simítások</li>
    <li>[CHANGED]: Mappaszerkezet átalakítása</li>
</ul>

<h3>v0.3 pre-alpha</h3>
<ul>
    <li>[NEW]: Az admin oldali vizuális szerkesztő panelbe bekerült a youtube videó beágyazási lehetőség is</li>
    <li>[NEW]: Admin oldalon az intézmények listázását kicsit átalalkítottam (előnézeti kép bekerült, plusz néhány oszlop szövege le lett vágva)</li>
    <li>[NEW]: Admin oldalon a közösségek listája bővült plusz infókkal
        <ul>
            <li>Fénykép bélyeg</li>
            <li>Közösség adatlap megtekintése felhasználói oldalon (szem ikon)</li>
            <li>Jóváhagyva oszlopban a szöveg helyett ikon</li>
            <li>van-e feltöltött igazolás (word doksi ikon) a közösséghez</li>
        </ul>
    </li>
    <li>[NEW]: Admin felületen az email-ek szövegét most már lehet szerkeszteni
        <ul>
            <li>+1 a listába leírás is került, hogy tudjuk, hogy melyik levelet mikor küldjük ki</li>
        </ul>
    </li>
    <li>[NEW]: Háló közösség logója bekerült a láblécbe</li>
    <li>[NEW]: Látogatói oldalon a közösség regisztrációs felület elkészült</li>
    <li>[NEW]: Látogatói oldalon egy felhasználó több közösséget is beregisztrálhat</li>
    <li>[NEW]: Látogatói oldalon a közösség jellemzők ikonjainak cseréje, a keresődobozban ikonok levétele</li>
    <li>[CHANGED]: Egy-két kép le lett cserélve a látogatói oldalon</li>
    <li>[CHANGED]: 'Közösséget vezetek. Hogyan tudom itt hirdetni?' főoldali szekcióban lecseréltem a drive-os linket a 'Közösséget vezetek' oldalra mutató linkre</li>
    <li>[CHANGED]: 'Mire jó egy keresztény közösség?' című főoldali szekció widget-be került</li>
    <li>[FIXED]: Lábléc linkek javításra kerültek</li>
    <li>[FIXED]: Folyamatos keretrendszer javítás, fejlesztés</li>
</ul>

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
