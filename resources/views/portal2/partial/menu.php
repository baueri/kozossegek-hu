<nav id="main-menu" class="navbar is-light" role="navigation" aria-label="main navigation">
    <div class="container">
    <div class="navbar-brand">
        <a class="navbar-item" href="/">
            <img src="/images/logo/logo200x50.webp" width="112" height="28">
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>
    <div id="navbarBasicExample" class="navbar-menu" style="font-size: .9rem">
        <div class="navbar-start">
            <a href="@route('portal.groups')" class="navbar-item">Közösséget keresek</a>
            <a href="@route('portal.register_group')" class="navbar-item">Közösséget vezetek</a>

            <div href="@route('portal.register_group')" class="navbar-item has-dropdown is-hoverable">
                <a href="#" class="navbar-link">Mozgalmak, lelkészségek</a>
                <div class="navbar-dropdown">
                    <a href="@route('portal.spiritual_movements')" class="navbar-item">
                        Lelkiségi mozgalmak
                    </a>
                    <a class="navbar-item">
                        Szerzetesrendek
                    </a>
                    <a class="navbar-item">
                        Egyetemi lelkészségek
                    </a>
                </div>
            </div>
            <a href="@route('portal.page', ['slug' => 'a-kozosseg'])" class="navbar-item">A közösségről</a>
            <a href="@route('portal.page', ['slug' => 'rolunk'])" class="navbar-item">Rólunk</a>
        </div>

        <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                    <a class="button is-brown is-rounded is-small">
                        <strong>Regisztráció</strong>
                    </a>
                    <a class="button is-outlined is-rounded is-small">
                        Belépés
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
</nav>