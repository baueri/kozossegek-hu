<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('subtitle'){{ site_name() }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://challenges.cloudflare.com">

    <!-- Bootstrap 5 CSS (ÁTEMELVE HEAD-BE) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery marad -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    @yield('header')

    <link rel="stylesheet" href="/css/style.css?{{ filemtime('css/style.css') }}">
    <link href="/css/common.css" @preload_css()>
</head>

<body class="{{ !is_prod() ? 'demo' : '' }} {{ is_home() ? 'home' : '' }} {{ $body_class ?? '' }}">

<div id="fb-root"></div>

@include('portal.partials.main_menu')

@yield('portal2026.portal')

<footer id="footer" class="text-white">
    <div class="container" id="footer-top">
        <div class="row">
            <div class="col-md-5 col-sm-6 col-12">
                <h5>Partnereink</h5>
                <div class="partnereink">
                    <a href="https://pasztoralis.hu/" target="_blank" rel="noopener noreferrer">
                        <img @lazySrc() data-src="/images/partnerek/szcsem_szines_latin.webp" class="lazy">
                    </a>
                    <a href="https://halo.hu/" target="_blank" rel="noopener noreferrer">
                        <img @lazySrc() data-src="/images/partnerek/halo-logo.webp" class="lazy">
                    </a>
                    <a href="https://fbe.hu/" target="_blank" rel="noopener noreferrer">
                        <img @lazySrc() data-src="/images/partnerek/felebaratok_egyesulet.webp" class="lazy">
                    </a>
                    <br/>
                    <a href="https://72tanitvany.hu/" target="_blank" rel="noopener noreferrer" class="t72-logo">
                        <img @lazySrc() data-src="/images/partnerek/t72_2.webp" class="lazy">
                    </a>
                    <a href="https://bizdramagad.hu/" target="_blank" rel="noopener noreferrer" class="t72-logo">
                        <img @lazySrc() data-src="/images/partnerek/bizd_ra_magad.webp" class="lazy">
                    </a>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12 my-3 my-md-0">
                <h5>Linkek</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-1"><a href="@route('portal.page', 'rolunk')">Rólunk</a></li>
                    <li class="nav-item mb-1"><a href="@route('portal.page', 'impresszum')">Impresszum</a></li>
                    <li class="nav-item mb-1"><a href="@route('portal.page', 'iranyelveink')">Irányelveink</a></li>
                    <li class="nav-item mb-1"><a href="@route('portal.page', 'rolunk')#contact">Kapcsolat</a></li>
                </ul>
            </div>

            <div class="col-md-3 col-sm-6 col-12 my-3 my-md-0">
                <h5>Kapcsolat</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-1"><a href="mailto:{{$contact_email}}">{{$contact_email}}</a></li>
                    <li class="nav-item mb-1"><a href="@route('portal.page', 'adatkezelesi-tajekoztato')">Adatkezelés</a></li>
                    <li class="nav-item mb-1"><a href="@route('portal.page', 'adatvedelmi-nyilatkozat')">Adatvédelem</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div id="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <small>© 2021-{{ date('Y') }} kozossegek.hu</small>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="https://www.facebook.com/" target="_blank" class="text-white">
                        <i class="fab fa-facebook-square fs-3"></i>
                    </a>
                    <a href="https://www.instagram.com/" target="_blank" class="text-white">
                        <i class="fab fa-instagram-square fs-3"></i>
                    </a>
                    <a href="https://github.com/baueri/kozossegek-hu/" target="_blank" class="text-white">
                        <i class="fab fa-github-square fs-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

@yield('footer')

<div class="alert text-center cookiealert" role="alert">
    <b>Kedves látogató!</b> 🍪 Cookie-kat használunk.
    <a href="/cookie-tajekoztato" target="_blank">További információ</a>
    <button type="button" class="btn btn-altblue btn-sm acceptcookies">Rendben</button>
</div>

<!-- Bootstrap 5 JS (Popper benne van) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

@if(env('GOOGLE_LOGIN_ENABLED'))
<script src="https://accounts.google.com/gsi/client" async defer></script>
@endif

@if(is_prod())
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-43190044-6"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-43190044-6');
</script>
@endif

<script>
    const meili_enabled = {{ env ('MEILI_ENABLED') ? 'true' : 'false' }}
</script>

<script src="/js/scripts.js?{{ filemtime('js/scripts.js') }}"></script>
<script src="/js/dialog.js?{{ filemtime('js/dialog.js') }}"></script>

@yield('scripts')

{{ debugbar()->render() }}

</body>
</html>