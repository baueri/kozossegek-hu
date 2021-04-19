<nav id="header" class="navbar navbar-expand-sm fixed-top">
    <div class="container">
        <a href="/" class="navbar-brand ml-4 ml-sm-0 mt-0 mb-0 p-0 p-sm-1">
            <div class="logo-lg"></div>
            <img src="/images/logo_only.png" class="logo-sm" style="display:none;">
        </a>
        <input type="checkbox" style="display: none" id="toggle_main_menu" name="toggle_main_menu">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="@route('portal.groups')" class="nav-link"><span>Közösséget keresek</span></a>
            </li>
            <li class="nav-item">
                <a href="@route('portal.register_group')" class="nav-link"><span>Közösséget vezetek</span></a>
            </li>
            <li class="nav-item">
                <a href="@route('portal.page', ['slug' => 'a-kozosseg'])" class="nav-link">A közösségről</a>
            </li>
            <li class="nav-item">
                <a href="@route('portal.page', ['slug' => 'rolunk'])" class="nav-link"><span>Rólunk</span></a>
            </li>
            @auth
                <li class="nav-item nav-item-profile">
                    <a href="@route('portal.my_profile')" class="nav-link user-menu"><i class="fa fa-user-circle" style="font-size: 18px;"></i></a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a href="@route('portal.my_profile')" class="nav-link">Fiókom</a>
                        </li>
                        <li class="nav-item">
                            <a href="@route('portal.my_groups')" class="nav-link">Közösségeim</a>
                        </li>
                        @admin()
                            <li class="nav-item">
                                <a href="@route('admin.dashboard')" class="nav-link">@icon('cog') Admin</a>
                            </li>
                        @endadmin
                        <li class="nav-item">
                            <a href="@route('logout')" class="nav-link text-danger">@icon('sign-out-alt') Kijelentkezés</a>
                        </li>
                    </ul>
                </li>
            @else
                <li class="nav-item">
                    <a href="@route('login')" class="nav-link d-none d-lg-block">
                        <i class="far fa-user-circle" style="font-size: 18px;"></i>
                    </a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a href="@route('login')" class="nav-link">
                                Belépés
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="@route('portal.register')" class="nav-link">Regisztráció</a>
                        </li>
                    </ul>
                </li>
            @endauth
            <li class="nav-item divider-before">
                <a href="https://vp2.hu/" class="nav-link partner-header-link" title="Virtuális plébánia" target="_blank" rel="noopener noreferrer">
                    <img src="/images/ikon-vp2.png"/>
                    <span class="d-lg-none d-inline-block">Virtuális plébánia</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="https://miserend.hu/" class="nav-link partner-header-link" title="miserend.hu" target="_blank" rel="noopener noreferrer">
                    <img src="/images/ikon-miserend.png"/>
                    <span class="d-lg-none d-inline-block">miserend.hu</span>
                </a>
            </li>
        </ul>
        <label class="mobile-menu-toggle float-right mr-4 mr-sm-0 mb-0" for="toggle_main_menu"><i class="fa fa-bars"></i></label>
    </div>
</nav>
