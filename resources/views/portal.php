<?php use App\Auth\Auth; ?>
<!doctype html>
<html lang="hu">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subtitle')kozossegek.hu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Da+2&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    @yield('header')

    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="/js/scripts.js"></script>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
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
            <a href="/" class="navbar-brand ml-4 ml-sm-0 mt-1 mb-1 mt-sm-0 mb-sm-0">
                <img src="/images/logo_sm.png" class="logo-lg">
                <img src="/images/logo_only.png" class="logo-sm" style="display:none;">
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
                    <a href="@route('portal.my_profile')" class="nav-link text-success">{{ Auth::user()->firstName() }} <i class="fa fa-caret-down"></i></a>
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
                        <a href="https://pasztoralis.hu/" title="Pasztorális helynökség Szeged" target="_blank"><img src="/images/szcsem_szines_latin.png"></a>
                    </div>
                    <!-- <h5>Légy naprakész!</h5>
                    <p><small>Add meg a városodat és az email címedet, amennyiben értesítést szeretnél kapni az új közösségekről!</small></p>
                    <div class="form-group"><input type="text" class="form-control" placeholder="városom"></div>
                    <div class="form-group"><input type="email" class="form-control" placeholder="email címem"></div>
                    <div class="form-group"></div><button type="submit" class="btn btn-primary w-100">Feliratkozom</button> -->
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
</body>
</html>
