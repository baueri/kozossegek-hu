@section('nav-pages')
    <li class="nav-item"><a href="@route('portal.page', ['slug' => 'rolunk'])" class="nav-link"><span>@lang('menu.about_us')</span></a></li>
    <li class="nav-item"><a href="@route('portal.page', ['slug' => 'rolunk'])#contact" class="nav-link"><span>@lang('menu.contact')</span></a></li>
    <li class="nav-item"><a href="@route('portal.page', ['slug' => 'a-kozosseg'])" class="nav-link"><span>@lang('menu.about_church_groups')</span></a></li>
    <li class="nav-item"><a href="@route('portal.page', 'iranyelveink')" class="nav-link"><span>Irányelveink</span></a></li>
@endsection

@section('nav-right')
    @auth
    <li class="nav-item nav-item-profile">
        <a href="#" onclick="return false;" class="nav-link user-menu" aria-label="Felhasználói menü">
            <small><i class="fa fa-user-circle" onclick="return false;"></i> {{ auth()->firstName() }}</small>
        </a>
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
    <li class="nav-item">
        <a href="#" onclick="return false;" class="nav-link d-none d-lg-block user-menu" aria-label="@lang('menu.login')">
            <label for="popup-login-username" class="mb-0" style="cursor:pointer;">
                <small><i class="far fa-user-circle"></i> Belépés</small>
            </label>
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
@endsection

<div class="" id="header">
    <nav id="navbar-top" class="navbar navbar-expand-sm d-lg-flex d-none">
        <div class="container">
            <ul class="navbar-nav nav-pages mx-2">
                @yield('nav-pages')
            </ul>
            <div style="margin-left: auto; margin-right: 15px;">
                @include('portal.partials.search_box')
            </div>
            <ul class="navbar-nav nav-right">
                @yield('nav-right')
            </ul>
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg" id="header-main">
        <div class="container position-relative">
            <a href="@route('home')" class="navbar-brand mx-2 mx-lg-0" aria-label="Főoldal">
                <img src="/images/logo/logo200x50.webp" class="logo-lg d-none d-md-block " alt="logo"/>
                <img src="/images/logo/logo42x42.webp" class="logo-sm d-block d-md-none" alt="logo">
            </a>
            <div class="d-inline-block d-lg-none flex-grow-1 mr-3">
                @include('portal.partials.search_box')
            </div>
            <input type="checkbox" style="display: none" id="toggle_main_menu" name="toggle_main_menu">
            <div class="abxd">
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
                    @if($display_news)
                        <li class="nav-item">
                            <a href="@route('portal.blog')" class="nav-link"><span>@lang('menu.news')</span></a>
                        </li>
                    @endif
                </ul>
                <ul class="navbar-nav d-flex d-lg-none">
                    @yield('nav-pages')
                    @yield('nav-right')
                </ul>
            </div>
            <label class="mobile-menu-toggle float-right mr-3 mb-0" for="toggle_main_menu"><i class="fa fa-bars py-3"></i></label>
        </div>
    </nav>
</div>
