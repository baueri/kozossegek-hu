<mint-section name="nav-pages">
    <li class="nav-item"><a href="@route('portal.page', ['slug' => 'rolunk'])" class="nav-link"><span>@lang('menu.about_us')</span></a></li>
    <li class="nav-item"><a href="@route('portal.page', ['slug' => 'rolunk'])#contact" class="nav-link"><span>@lang('menu.contact')</span></a></li>
    <li class="nav-item"><a href="@route('portal.page', ['slug' => 'a-kozosseg'])" class="nav-link"><span>@lang('menu.about_church_groups')</span></a></li>
    <li class="nav-item"><a href="@route('portal.page', 'iranyelveink')" class="nav-link"><span>Irányelveink</span></a></li>
</mint-section>

<mint-section name="nav-right">
    @if($is_logged_in)
    <li class="nav-item nav-item-profile">
        <a href="#" class="nav-link user-menu" aria-label="Felhasználói menü">
            <small><i class="fa fa-user-circle"></i> {{ auth()->firstName() }}</small>
        </a>
        <ul class="submenu">
            <li class="nav-item">
                <a href="@route('portal.my_profile')" class="nav-link"><mod-icon :name="{'user-circle'}" /> @lang('menu.my_account')</a>
            </li>
            <li class="nav-item">
                <a href="@route('portal.my_groups')" class="nav-link"><mod-icon :name="{'comments'}" /> @lang('menu.my_groups')</a>
            </li>
            <li class="nav-item">
                <a href="@route('portal.my_events')" class="nav-link"><mod-icon :name="{'calendar-alt'}" /> @lang('menu.my_events')</a>
            </li>
            <li x:if="{$is_admin}" class="nav-item">
                <a href="@route('admin.dashboard')" class="nav-link"><mod-icon :name="{'cog'}" /> @lang('menu.admin')</a>
            </li>
            <li class="nav-item">
                <a href="@route('logout')" class="nav-link text-danger"><mod-icon :name="sign-out-alt" /> @lang('menu.logout')</a>
            </li>
        </ul>
    </li>
    @else
    <li class="nav-item">
        <a href="#" class="nav-link d-none d-lg-block user-menu" aria-label="@lang('menu.login')">
            <label for="popup-login-username" class="mb-0" style="cursor:pointer;">
                <small><i class="far fa-user-circle"></i> Belépés</small>
            </label>
        </a>
        <ul class="submenu">
            <li class="nav-item" id="login-box">
                <div class="p-lg-3">
                    <form class="auth-form auth-form--dropdown" action="@route('doLogin')" method="post">
                        @csrf
                        <span class="login-dropdown-heading">@lang('menu.login')</span>
                        <div class="mb-3">
                            <label for="popup-login-username" class="form-label">@lang('menu.login.email')</label>
                            <input type="text"
                                   class="form-control"
                                   name="username"
                                   id="popup-login-username"
                                   autocomplete="username"
                                   placeholder="@lang('menu.login.email')"/>
                        </div>
                        <div class="mb-3">
                            <label for="popup-login-password" class="form-label">@lang('menu.login.password')</label>
                            <input type="password"
                                   class="form-control"
                                   name="password"
                                   id="popup-login-password"
                                   autocomplete="current-password"
                                   placeholder="@lang('menu.login.password')"/>
                        </div>
                        @if($social_enabled)
                            <div class="auth-divider auth-divider--compact">vagy</div>
                            <div>
                                <mod-google-login />
                            </div>
                        @endif
                        <button type="submit" class="btn btn-orange w-100 rounded-pill py-2 mt-1">Belépés</button>
                        <div class="auth-form-footer--dropdown d-flex flex-column gap-1 align-items-center">
                            <a href="@route('portal.forgot_password')">Elfelejtett jelszó</a>
                            <a href="@route('portal.register')">Regisztráció</a>
                        </div>
                    </form>
                </div>
            </li>
        </ul>
    </li>
    @endif

    <li class="nav-item">
        <a href="https://miserend.hu/" title="miserend.hu" class="nav-link partner-header-link" target="_blank" rel="noopener noreferrer">
            <img src="/images/miserend_logo.png" style="height: 23px; width: auto;" alt="miserend.hu"/>
            <span class="d-lg-none d-inline-block">miserend.hu</span>
        </a>
    </li>
</mint-section>

<div id="header">
    <nav id="navbar-top" class="navbar navbar-expand-sm d-lg-flex d-none">
        <div class="container">
            <ul class="navbar-nav nav-pages mx-2">
                <mint-yield name="nav-pages" />
            </ul>

            <ul class="navbar-nav nav-right">
                <mint-yield name="nav-right" />
            </ul>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg" id="header-main">
        <div class="container position-relative">
            <a href="@route('home')" class="navbar-brand mx-2 mx-lg-0" aria-label="Főoldal">
                <img src="/images/logo/logo200x50.webp" class="logo-lg d-none d-md-block" alt="logo"/>
                <img src="/images/logo/logo42x42.webp" class="logo-sm d-block d-md-none" alt="logo">
            </a>

            <input type="checkbox" class="d-none" id="toggle_main_menu" name="toggle_main_menu">

            <label class="mobile-menu-backdrop" for="toggle_main_menu" aria-hidden="true"></label>

            <div class="abxd d-lg-flex d-block">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="@route('portal.groups')" class="nav-link@active_link_class('portal.groups')">
                            <span>@lang('menu.search_group')</span>
                        </a>
                    </li>
                    <li x:if="{ $display_events }" class="nav-item">
                        <a href="@route('event.list')" class="nav-link@active_link_class('event.list')">
                            <span>@lang('menu.events')</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="@route('portal.spiritual_movements')" class="nav-link@active_link_class('portal.spiritual_movements')">
                            <span>@lang('menu.religious_movements')</span>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav d-flex d-lg-none">
                    <mint-yield name="nav-pages" />
                    <mint-yield name="nav-right" />
                </ul>
                <div class="d-flex align-items-center ms-3">
                    <a href="@route('portal.register_group')" class="badge rounded-pill bg-dark py-2 px-3 text-light text-decoration-none">
                        @lang('menu.leading_a_group')
                    </a>
                </div>
            </div>

            <label class="mobile-menu-toggle float-end me-3 mb-0" for="toggle_main_menu">
                <i class="fa fa-bars py-3"></i>
            </label>
        </div>
    </nav>
</div>
