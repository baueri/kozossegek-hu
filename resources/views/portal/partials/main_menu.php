@section('nav-pages')
    <li class="nav-item"><a href="@route('portal.page', ['slug' => 'rolunk'])" class="nav-link"><span>@lang('menu.about_us')</span></a></li>
    <li class="nav-item"><a href="@route('portal.page', ['slug' => 'rolunk'])#contact" class="nav-link"><span>@lang('menu.contact')</span></a></li>
    <li class="nav-item"><a href="@route('portal.page', ['slug' => 'a-kozosseg'])" class="nav-link"><span>@lang('menu.about_church_groups')</span></a></li>
    <li class="nav-item"><a href="@route('portal.page', 'iranyelveink')" class="nav-link"><span>Irányelveink</span></a></li>
@endsection

@section('nav-right')
    @auth
    <li class="nav-item nav-item-profile">
        <a href="#" class="nav-link user-menu" aria-label="Felhasználói menü">
            <small><i class="fa fa-user-circle"></i> {{ auth()->firstName() }}</small>
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
        <a href="#" class="nav-link d-none d-lg-block user-menu" aria-label="@lang('menu.login')">
            <label for="popup-login-username" class="mb-0" style="cursor:pointer;">
                <small><i class="far fa-user-circle"></i> Belépés</small>
            </label>
        </a>
        <ul class="submenu">
            <li class="nav-item" id="login-box">
                <div class="p-lg-3">
                    <form action="@route('doLogin')" method="post">
                        <label class="text-center w-100">@lang('menu.login')</label><br/>
                        
                        <div class="mb-3">
                            <input type="text" class="form-control" name="username" placeholder="@lang('menu.login.email')" id="popup-login-username"/>
                        </div>

                        <div class="mb-3">
                            <input type="password" class="form-control" name="password" placeholder="@lang('menu.login.password')"/>
                        </div>

                        <div>
                            @include('portal.partials.google-login', ['width' => 205])
                        </div>

                        <p class="text-center">
                            <button type="submit" class="btn btn-altblue">Belépés</button>
                        </p>

                        <p class="text-center">
                            <a href="@route('portal.register')">Regisztráció</a>
                        </p>
                    </form>
                </div>
            </li>
        </ul>
    </li>
    @endauth

    <li class="nav-item divider-before">
        <a href="https://vp2.hu/" class="nav-link partner-header-link" target="_blank" rel="noopener noreferrer">
            <img src="/images/ikon-vp2.png" alt="Virtuális plébánia"/>
            <span class="d-lg-none d-inline-block">Virtuális plébánia</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="https://miserend.hu/" class="nav-link partner-header-link" target="_blank" rel="noopener noreferrer">
            <img src="/images/ikon-miserend.png" alt="miserend.hu"/>
            <span class="d-lg-none d-inline-block">miserend.hu</span>
        </a>
    </li>
@endsection

<div id="header">
    <nav id="navbar-top" class="navbar navbar-expand-sm d-lg-flex d-none">
        <div class="container">
            <ul class="navbar-nav nav-pages mx-2">
                @yield('nav-pages')
            </ul>

            <ul class="navbar-nav nav-right">
                @yield('nav-right')
            </ul>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg" id="header-main">
        <div class="container position-relative">
            <a href="@route('home')" class="navbar-brand mx-2 mx-lg-0" aria-label="Főoldal">
                <img src="/images/logo/logo200x50.webp" class="logo-lg d-none d-md-block" alt="logo"/>
                <img src="/images/logo/logo42x42.webp" class="logo-sm d-block d-md-none" alt="logo">
            </a>

            <div class="d-inline-block d-lg-none flex-grow-1 me-3">
                @include('portal.partials.search_box')
            </div>

            <input type="checkbox" class="d-none" id="toggle_main_menu" name="toggle_main_menu">

            <div class="abxd d-lg-flex d-block">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="@route('portal.groups')" class="nav-link@active_link_class('portal.groups')">
                            <span>@lang('menu.search_group')</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="@route('portal.spiritual_movements')" class="nav-link@active_link_class('portal.spiritual_movements')">
                            <span>@lang('menu.religious_movements')</span>
                        </a>
                    </li>

                    @if($display_news)
                        <li class="nav-item">
                            <a href="@route('portal.blog')" class="nav-link@active_link_class('portal.blog')">
                                <span>@lang('menu.news')</span>
                            </a>
                        </li>
                    @endif
                </ul>
                <ul class="navbar-nav d-flex d-lg-none">
                    @yield('nav-pages')
                    @yield('nav-right')
                </ul>
                <div class="d-flex align-items-center ml-3">
                    <a href="@route('portal.register_group')" class="badge rounded-pill bg-dark py-2 px-3 text-light">
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