<nav id="header" class="navbar navbar-expand-sm fixed-top">
    <div class="container">
        <a href="@route('home')" class="navbar-brand ml-4 ml-sm-0 mt-0 mb-0 p-0 p-sm-1" aria-label="Főoldal">
            <div class="logo-lg"></div>
            <img src="/images/logo/logo42x42.webp" class="logo-sm" style="display:none;">
        </a>
        <input type="checkbox" style="display: none" id="toggle_main_menu" name="toggle_main_menu">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="@route('portal.groups')" class="nav-link"><span>@lang('menu.search_group')</span></a>
            </li>
            <li class="nav-item">
                <a href="@route('portal.register_group')" class="nav-link"><span>@lang('menu.leading_a_group')</span></a>
            </li>
            <li class="nav-item">
                <a href="@route('portal.spiritual_movements')" class="nav-link"><span>@lang('menu.religious_movements')</span></a>
            </li>
            <li class="nav-item">
                <a href="@route('portal.page', ['slug' => 'a-kozosseg'])" class="nav-link">@lang('menu.about_church_groups')</a>
            </li>
            <li class="nav-item">
                <a href="@route('portal.page', ['slug' => 'rolunk'])" class="nav-link"><span>@lang('menu.about_us')</span></a>
            </li>
            @auth
                <li class="nav-item nav-item-profile">
                    <a href="#" class="nav-link user-menu" aria-label="Felhasználói menü"><i class="fa fa-user-circle" style="font-size: 18px;" onclick="return false;"></i></a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a href="@route('portal.my_profile')" class="nav-link">@icon('user-circle') @lang('menu.my_account')</a>
                        </li>
                        <li class="nav-item">
                            <a href="@route('portal.my_groups')" class="nav-link">@icon('comments') @lang('menu.my_groups')</a>
                        </li>
                        @admin()
                            <li class="nav-item">
                                <a href="@route('admin.dashboard')" class="nav-link">@icon('cog') @lang('menu.admin')</a>
                            </li>
                        @endadmin
                        <li class="nav-item">
                            <a href="@route('logout')" class="nav-link text-danger">@icon('sign-out-alt') @lang('menu.logout')</a>
                        </li>
                    </ul>
                </li>
            @else
                <li class="nav-item px-2">
                    <a href="#" class="nav-link d-none d-lg-block" aria-label="@lang('menu.login')">
                        <label for="popup-login-username" class="mb-0" style="cursor:pointer;"><i class="far fa-user-circle" style="font-size: 18px;"></i></label>
                    </a>
                    <ul class="submenu">
                       <li class="nav-item" id="login-box">
                           <div class="p-lg-3">
                               <form action="@route('doLogin')" method="post">
                                   <label class="text-center w-100">@lang('menu.login')</label><br/>
                                   <div class="form-group">
                                       <input type="text" class="form-control" name="username" placeholder="@lang('menu.login.email')" id="popup-login-username"/>
                                   </div>
                                   <div class="form-group">
                                       <input type="password" class="form-control" name="password" placeholder="@lang('menu.login.password')"/>
                                   </div>
                                   <div>
                                       @include('portal.partials.google-login', ['width' => 205])
<!--                                       <div class="fb-login-button mb-3" data-width="238px" data-size="medium" data-button-type="login_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="true"></div><br/>-->
                                   </div>
                                   <p class="text-center">
                                       <button type="submit" class="btn btn-altblue">Belépés</button>
                                   </p>
                                   <p class="text-center">
                                       <a href="@route('portal.register')" class="">Regisztráció</a>
                                   </p>
                               </form>
                           </div>
                       </li>
                    </ul>
                </li>
            @endauth
            <li class="nav-item divider-before">
                <a href="https://vp2.hu/" class="nav-link partner-header-link" title="Virtuális plébánia" aria-label="Virtuális plébánia" target="_blank" rel="noopener noreferrer">
                    <img src="/images/ikon-vp2.png" alt="Virtuális plébánia"/>
                    <span class="d-lg-none d-inline-block">Virtuális plébánia</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="https://miserend.hu/" class="nav-link partner-header-link" title="miserend.hu" target="_blank" aria-label="miserend.hu" rel="noopener noreferrer">
                    <img src="/images/ikon-miserend.png" alt="miserend.hu"/>
                    <span class="d-lg-none d-inline-block">miserend.hu</span>
                </a>
            </li>
        </ul>
        <label class="mobile-menu-toggle float-right mr-4 mr-sm-0 mb-0" for="toggle_main_menu"><i class="fa fa-bars"></i></label>
    </div>
</nav>
