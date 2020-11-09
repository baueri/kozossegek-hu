<?php use App\Auth\Auth; ?>
<!DOCTYPE HTML>
<html lang="hu">
<head>
    <meta charset="utf-8"/>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subtitle')kozossegek.hu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital@0;1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Crimson+Text|Work+Sans:400,700" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    @yield('header')

    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="/js/scripts.js"></script>

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
<body>
    <nav id="header" class="navbar navbar-expand-sm bg-light navbar-light sticky-top">
        <div class="container">
            <a href="/" class="navbar-brand ml-4 ml-sm-0 mt-0 mb-0 p-0 p-sm-1">
                <img src="/images/logo_sm_2.png" class="logo-lg" alt="kozossegek.hu">
                <img src="/images/logo_only.png" class="logo-sm" style="display:none;" alt="kozossegek.hu">
            </a>
            <input type="checkbox" style="display: none" id="toggle_main_menu" name="toggle_main_menu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="@route('portal.groups')" class="nav-link"><span>Közösséget keresek</span></a>
                </li>
                <li class="nav-item">
                    <a href="@route('portal.page', ['slug' => 'rolunk'])" class="nav-link"><span>Rólunk</span></a>
                </li>
                <li class="nav-item">
                    <a href="@route('portal.page', ['slug' => 'a-kozosseg'])" class="nav-link">A közösségről</a>
                </li>
                <?php if(Auth::loggedIn()): ?>
                <li class="nav-item">
                    <a href="@route('portal.my_profile')" class="nav-link text-danger"><i class="fa fa-user-circle" style="font-size: 20px;"></i></a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a href="@route('portal.my_profile')" class="nav-link">Profilom</a>
                        </li>
                        <li class="nav-item">
                            <a href="@route('portal.my_group')" class="nav-link">Közösségem</a>
                        </li>
                        <?php if (Auth::user()->isAdmin()): ?>
                            <li class="nav-item">
                                <a href="@route('admin.dashboard')" class="nav-link"><i class="fa fa-cog"></i> Admin</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="@route('logout')" class="nav-link text-danger"><i class="fa fa-sign-out-alt"></i> Kijelentkezés</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>
            <label class="mobile-menu-toggle float-right mr-4 mr-sm-0 mb-0" for="toggle_main_menu"><i class="fa fa-bars"></i></label>
        </div>

    </nav>
    @yield('portal')
    <footer id="footer" class="text-white">
        <div class="container" id="footer-top">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    @widget('LABA')
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="" class="nav-link">Rólunk</a></li>
                        <li class="nav-item"><a href="@route('portal.feedback')" class="nav-link">Visszajelzés küldése</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'adatkezelesi-tajekoztato')" class="nav-link">Adatkezelési tájékoztató</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'impresszum')" class="nav-link">Impresszum</a></li>
                    </ul>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <h5>Partnereink</h5>
                    <div class="partnereink">
                        <a href="https://pasztoralis.hu/" title="Pasztorális helynökség Szeged" target="_blank" rel="noopener noreferrer">
                            <img src="/images/szcsem_szines_latin.png" alt="Pasztorális helynökség Szeged">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer-bottom">
            <div class="container text-right">
                <small>© 2020 kozossegek.hu - Minden jog fenntartva!</small>
            </div>
        </div>
    </footer>
    @yield('footer')

    @if($show_debugbar)
    {{ debugbar()->render() }}
    @endif
</body>
</html>
