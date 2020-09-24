<!doctype html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kozossegek.hu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/bootstrap-margins-paddings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Baloo+Da+2&family=Oswald:wght@400;700&display=swap" rel="stylesheet">


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    @yield('header')
    
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="/js/scripts.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-sm bg-light navbar-light sticky-top">
        <div class="container">
            <a href="/" class="navbar-brand">
                <img src="/images/logo_xs.png">
            </a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/kozossegek" class="nav-link"><span>Közösséget keresek</span></a>
                </li>
                <li class="nav-item">
                    <a href="oldalak/rolunk.html" class="nav-link"><span>Rólunk</span></a>
                </li>
                <li class="nav-item">
                    <a href="a-kozossegekrol" class="nav-link">Közösség? Minek?</a>
                </li>
            </ul>

        </div>
    </nav>
    @yield('portal')
    <footer id="footer" class="text-white">
        <div class="container" id="footer-top">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <h5>A honlapról</h5>
                    <p class="">
                        <em>
                            Egy keresztény platform keresztény közösséget kereső fiatalok és idősek számára.
                            <a href="#">bővebben...</a>
                        </em>
                    </p>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="" class="nav-link">Közösséget keresek</a></li>
                        <li class="nav-item"><a href="" class="nav-link">Közösséget hirdetek</a></li>
                        <li class="nav-item"><a href="" class="nav-link">Rólunk</a></li>

                        <li class="nav-item"><a href="" class="nav-link">Visszajelzés küldése</a></li>
                        <li class="nav-item"><a href="" class="nav-link">Közösség jelentése</a></li>
                        <li class="nav-item"><a href="" class="nav-link">Adatkezelési tájékoztató</a></li>
                    </ul>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <h5>Légy naprakész!</h5>
                    <p><small>Add meg a városodat és az email címedet, amennyiben értesítést szeretnél kapni az új közösségekről!</small></p>
                    <div class="form-group"><input type="text" class="form-control" placeholder="városom"></div>
                    <div class="form-group"><input type="email" class="form-control" placeholder="email címem"></div>
                    <div class="form-group"></div><button type="submit" class="btn btn-primary w-100">Feliratkozom</button>
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
