<?php

use App\Auth\Auth;

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8"/>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subtitle')kozossegek.hu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:wght@200|Work+Sans:400,700|Merriweather|Roboto+Condensed:wght@300;400|" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="search" type="application/opensearchdescription+xml" title="kozossegek.hu" href="/opensearch.xml">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    @yield('header')

    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


    @if(is_prod())
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-43190044-6"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-43190044-6');
        </script>
    @endif
</head>
<body class="@if(!is_prod())demo@endif">
    <div class="home" style="background:url('{{ $header_background ?? '/images/img2.jpg' }}') no-repeat top center">
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
                    <?php if (Auth::loggedIn()) : ?>
                    <li class="nav-item nav-item-profile">
                        <a href="@route('portal.my_profile')" class="nav-link user-menu text-danger"><i class="fa fa-user-circle" style="font-size: 18px;"></i></a>
                        <ul class="submenu">
                            <li class="nav-item">
                                <a href="@route('portal.my_profile')" class="nav-link">Profilom</a>
                            </li>
                            <li class="nav-item">
                                <a href="@route('portal.my_groups')" class="nav-link">Közösségeim</a>
                            </li>
                            <?php if (Auth::user()->isAdmin()) : ?>
                                <li class="nav-item">
                                    <a href="@route('admin.dashboard')" class="nav-link"><i class="fa fa-cog"></i> Admin</a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a href="@route('logout')" class="nav-link text-danger"><i class="fa fa-sign-out-alt"></i> Kijelentkezés</a>
                            </li>
                        </ul>
                    </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a href="@route('login')" class="nav-link"><i class="fa fa-user-circle" style="font-size: 18px;"></i></a>
                        </li>
                    <?php endif; ?>
                </ul>
                <label class="mobile-menu-toggle float-right mr-4 mr-sm-0 mb-0" for="toggle_main_menu"><i class="fa fa-bars"></i></label>
            </div>
        </nav>
        @yield('header_content')
    </div>
    @yield('portal')
    <footer id="footer" class="text-white">
        <div class="container" id="footer-top">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="@route('portal.page', 'rolunk')" class="nav-link">Rólunk</a></li>
                        <!--<li class="nav-item"><a href="@route('portal.feedback')" class="nav-link">Visszajelzés küldése</a></li>-->
                        <li class="nav-item"><a href="@route('portal.page', 'impresszum')" class="nav-link">Impresszum</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'adatvedelmi-nyilatkozat')" class="nav-link">Adatvédelmi nyilatkozat</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'iranyelveink')" class="nav-link">Irányelveink</a></li>
                    </ul>
                </div>

                <div class="col-md-4 offset-4 col-sm-6 col-xs-12">
                    <h5>Partnereink</h5>
                    <div class="partnereink">
                        <a href="https://pasztoralis.hu/" title="Pasztorális helynökség Szeged" target="_blank" rel="noopener noreferrer">
                            <img src="/images/szcsem_szines_latin.png" alt="Pasztorális helynökség Szeged">
                        </a>
                        <a href="https://halo.hu/" title="Háló Közösségi és Kulturális Központ" target="_blank" rel="noopener noreferrer">
                            <img src="/images/halo-logo.png" alt="Háló Közösségi és Kulturális Központ">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer-bottom">
            <div class="container">
                <small>© 2021 {{ date('Y') > 2021 ? ' - ' . date('Y') : '' }} kozossegek.hu - Minden jog fenntartva!</small>
            </div>
        </div>
    </footer>
    @yield('footer')

    @if($show_debugbar)
    {{ debugbar()->render() }}
    @endif
    <div class="alert text-center cookiealert" role="alert">
        <b>Kedves látogató!</b> &#x1F36A; A honlapon a felhasználói élmény fokozásának érdekében cookie-kat használunk. <a href="/cookie-tajekoztato" target="_blank">További információ</a>
        <button type="button" class="btn btn-primary btn-sm acceptcookies">
            Rendben
        </button>
    </div>
    <script src="/js/scripts.js"></script>
</body>
</html>
