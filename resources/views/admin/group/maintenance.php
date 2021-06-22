@title('Karbantartás')
@extends('admin')
<div>
    <h4>Keresőmotor frissítés</h4>
    <p>
        Ha valami miatt egy adott közösség nem található meg pl az amúgy nála beállított jellemzők,
        leírás, név stb alapján, akkor a keresőmotor tábláját frissíteni lehet erre a gombra kattintva.<br/>
        Ha még ezután se működik a keresés, vedd fel a kapcsolatot a fejlesztővel.
    </p>
    <p>
        <a href="@route('admin.group.refresh_search_engine')" class="btn btn-sm btn-primary"><i class="fa fa-search-plus"></i> Keresőmotor frissítése</a>
    </p>
</div>
