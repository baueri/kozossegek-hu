# kozossegek.hu

A kozossegek.hu egy katolikus közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek 
megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül.

A projekt publikussá tételével szeretnénk másokat is bevonni a közös munkába, fejlesztésbe, hogy még hatékonyabb
legyen az oldal karbantartása, az újdonságok elkészítése, esetleg mások ötleteit is meg tudjuk valósítani.

**Minden építő jellegű ötletet, fejlesztést örömmel fogadunk issue-k és pull requestek formájában!**

_Részletes fejlesztői dokumentáció jelenleg nincs, de idővel igyekszünk ennek is eleget tenni._

### Tech stack
- `php8.1`
- `mariadb`
- `html`, `css`

### Követelmények
- Docker. Telepítése: https://docs.docker.com/engine/install/

### Telepítés
#### Instant telepítés, alap beállításokkal
_(app port: 8000, pma port: 8001, username: admin, password: pw, email: amit lent megadsz)_
```shell
git clone git@github.com:baueri/kozossegek-hu.git \
  && cd kozossegek \
  && cp .env.example .env \
  && docker compose up -d --build \
  && docker exec kozossegek_app php console install --name=Admin --username=admin --email="your.eamil@kozossegek.hu" --password=pw --seed
```

### VAGY

#### Részletes telepítés

Klónozd le a projektet, majd lépj be a könyvtárba

```shell
git clone git@github.com:baueri/kozossegek-hu.git
```

Másold le a .env.example tartalmát a .env fájlba

```shell
cp .env.example .env
```

Az **alkalmazás** a `8000`-es, a **phpmyadmin** pedig a `8001`-es portokon lesznek kiszolgálva, ha ezen változtatnál,
írd át az `APP_PORT` és a `PMA_PORT` változókat a `.env` fájlban.

_A többi változót is igény szerint módosíthatod (sql usernév, jelszó, storage mappa stb)_

Indítsd el a dockert:

```shell
docker compose up -d --build
```

_A `-d` kapcsolóval a sikeres build után a háttérben fognak futni a containerek._

Miután a dockeres telepítés megtörtént, a `kozossegek_app` containerben futtasd az `install.php`-t:

```shell
docker exec kozossegek_app php install.php
```

A telepítőben

1. létrejön az admin fiókod
2. felkerül néhány dummy közösség, illetve a térkép modul és a keresőmotor frissül. Ez 1-2 percet igénybe vehet.

Ha minden sikeresen lefutott, akkor az alkalmazás a http://localhost:8280 linken elérhető lesz.

### Docker containerek
A kozossegek.hu projektben három containert futtatunk:

- **kozossegek_app**: Ide van az apache szerver felrakva, és az alkalmazás is itt fut.
- **kozossegek_pma**: Phpmyadmin image van behúzva. A phpmyadmin innen érhető el: http://localhost:8281
  - Alapértelmezetten a **user:** root, **password:** pw adatokkal tudsz belépni.
- **kozossegek_mysql**: Ez az sql container, szintén a fenti login adatokkal lehet használni az sql cli-t.

## A keretrendszerről
A kozossegek.hu egyedi keretrendszer alatt fut, `MVC` struktúrával. Ebben a fejezetben a legalapvetőbb dolgokat írjuk le, amivel már bele tudsz kapcsolódni a fejlesztésbe.

A **route**-ok határozzák meg az oldal belépési pontját, amik nagyrészt egy **controller** class adott **metódusára** mutatnak.

A **controller** osztály metódusa tartalmazza a business logic-et, amit szükség szerint lehet külön service-be is kivezetni.  

A megjelenítést pedig egy saját fejlesztésű, szintaktikájában a laravel `blade`-jére hasonlító templating rendszer valósítja meg.

Az adatbázis táblák entitásainak kezelésére két implementációt használunk:

- Az entitások reprezentálására model osztályok
- Ezek szerkesztésére, lekérésére, létrehozására pedig a query builderek.


### Routing
Az aloldalak struktúrája `xml`-ben írt route fájlokban vannak meghatározva. Az egyes aloldalakra vonatkozó szabályokat (engedélyezett request method, middleware) szintén itt lehet meghatározni.
Az alapvető útvonalak négy xml fájlba vannak rendezve szerepüktől függően.

- `routes/web.xml`
- `routes/api.xml`
- `routes/admin.xml`
- `routes/admin_api.xml`

**Új route útvonal létrehozása**
Új route-ot az adott xml fájlban a `<routes></routes>` gyökér tag közé kell felvinni

Általános használata:
```xml
<route method="get" uri="route-to-page" controller="App\Path\To\Controller" use="routeEntrypoint"/>
```

Ez a fenti route létrehozza a `kozossegek.hu/route-to-page` végpontot, ami az `App\Path\To\Controller` osztály `routeEntrypoint` metódusát fogja lefuttatni

#### Controllerek
A controller osztályokban vannak a route-ok belépési pontjai implementálva.

```php
<?php

namespace App\Portal\Controllers;

use Framework\Http\Controller;

class MyController extends Controller
{
    public function myEntryPoint(SomeService $service)
    {
        // some business logic here
    }
}
```

**Note: dependency injection**

A fenti példában is látszik, hogy a controller `myEntryPoint` metódusának van egy `SomeService` függősége.
A keretrendszer egy egyszer dependency injection resolverrel automatikusan megpróbálja **rekurzívan** feloldani a controller metódus függőségeit (és a függőségeinek függőségeit, ha vannak).
Ez segíthet abban, hogy egy service-t ne kelljen manuálisan példányosítani annak további függőségeivel.

### Adatbázis modellek
Az adatbázis sorok php-s reprezentálására a `Framework\Model\Entity`-ből származtatott osztályokat használjuk.
Minden ilyen entity implementációnak van egy hozzá kapcsolódó query builderje is, ami a `Framework\Model\EntityQueryBuilder`-ből van származtatva.

Példa a használatra:

```php
<?php

class MyController extends \Framework\Http\Controller
{
    public function users(\App\QueryBuilders\Users $users)
    {
        return $users->where('email', 'test@gmail.com')->first();
    }
}

```

**Entitás létrehozása**
```php
$user = $users->create(['name' => 'Test user', 'email', 'test@gmail.com']); // egy User példányt ad vissza
```

**Entitás adatainak frissítése**
```php
$user = $users->find(1); // egyetlen sor lekérése id alapján
$users->save($user, ['name' => 'Másik user'])
```

**Sor törlése adatbázisból**

Kétféleképpen lehet törölni sort a query builder használatával:
```php
$users->deleteModel($user); // model átadásával

// vagy

$users->where('email', 'test@gmail.com')->delete() // sql-lel
```

**Note: adott entitás query builderjét a `query()` metódushívással tudod példányosítani**
```php
$users = Users::query();
$users->where('...')->orderBy('name asc')->get();
```

Természetesen van lehetőség a query builder helyett a plain sql használatára is:

```php
$testUsers = db()->select('select * from users where name like %?%', ['test']);
```

**Fontos: plain sql esetén is mindenképp bindig-ot használjunk, ne írjunk sql-t konkatenálással**
```php
// NE CSINÁLD:
db()->first('select * from users where email="' . request()->get('email') . "'");
```

### Templating
A megjelenítést az egyedi fejlesztésű, `blade` által inspirált, de annál egyszerűbb template engine generálja.
Ezek is gyakorlatilag php fájlok, amik bizonyos kényelmi előnyökkel szolgálnak.

A template fájlokat a `resources/views` mappában tároljuk

`resources/views/minta.php`
```html
@title('Ez egy minta oldal')
@extends('portal')

<h3>Hello {{ $nev }}, ez egy minta oldal...</h3>
<p>...ami köré a portal.php template fájl kerül, így erre a template-re a látogai oldal dizájnja, szerkezete kerül</p>
```

A `@title()` direktíva a fejléc szekcióba jeleníti meg azt a szöveget, amit paraméterben átadunk

Az `@extends()` direktíva határozza meg, hogy mely másik template-et akarjuk a tartalom köré berakni. Az ez alá kerülő tartalom, html kód kerül a `portal.php` fájlban a `@yield('portal')` helyére.

Lehetőség van dinamikus tartalmat is megjeleníteni, erre a `{{ $valtozo_neve }}` szintaxist lehet használni.

#### Template megjelenítése
Egy template tartalmát legegyszerűbben a `view()` függvényhívással lehet megjeleníteni
```php
<?php

class MyController extends \Framework\Http\Controller
{
    public function mintaOldal()
    {
        return view('minta', ['nev' => 'Minta János']);
    }
}
```

#### használható template utasítások:

Iteráció egy tömbön

```html
<ul>
@foreach($array as $value)
    <li>{{ $value }}</li>
@endforeach
</ul>
```
Feltételvizsgálat
```html
@if($a === 'a')

@else

@endif
```

Másik template fájl betöltése
```html
<p>Some content</p>

@include('path.to.another.template')

<p>Some other content</p>
```

Route linkjének lekérése

```html
<a href="@route('admin.dashboard')">Admin felület</a>
```

