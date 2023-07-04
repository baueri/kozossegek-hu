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
                <a href="@route('portal.spiritual_movements')" class="nav-link"><span>Lelkiségi mozgalmak</span></a>
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
                            <a href="@route('portal.my_profile')" class="nav-link">@icon('user-circle') Fiókom</a>
                        </li>
                        <li class="nav-item">
                            <a href="@route('portal.my_groups')" class="nav-link">@icon('comments') Közösségeim</a>
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
                           <div class="p-3" style="width: 250px">
                               <form action="@route('doLogin')" method="post">
                                   <h5>Bejelentkezés</h5>
                                   <div class="form-group">
                                       <input type="text" class="form-control" name="username" placeholder="email vagy felhasználónév"/>
                                   </div>
                                   <div class="form-group">
                                       <input type="password" class="form-control" name="password" placeholder="jelszó"/>
                                   </div>
                                   <div id="g_id_onload"
                                        data-client_id="{{ env('GOOGLE_CLIENT_ID') }}"
                                        data-context="signin"
                                        data-ux_mode="popup"
                                        data-login_uri="https://kozossegek.serveo.net/social-login"
                                        data-auto_prompt="false">
                                   </div>

                                   <div class="g_id_signin mb-1"
                                        data-type="standard"
                                        data-shape="rectangular"
                                        data-theme="outline"
                                        data-text="signin_with"
                                        data-size="large"
                                        data-logo_alignment="left"
                                        data-locale="hu">
                                   </div>
                                   <div class="fb-login-button mb-2" data-width="225" data-size="large" data-button-type="login_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="true"></div><br/>
                                   <button type="submit" class="btn btn-primary">Belépés</button>
                               </form>
                           </div>
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
