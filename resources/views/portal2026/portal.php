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

<footer id="footer" class="text-white footer-site">
    <div class="container" id="footer-top">
        <div class="row">
            <div class="col-md-5 col-12 mb-4 mb-md-0">
                <h5 class="footer-section-title">Partnereink</h5>
                <div class="partnereink">
                    <a href="https://pasztoralis.hu/" title="Pasztorális helynökség Szeged" target="_blank" rel="noopener noreferrer">
                        <img @lazySrc() data-src="/images/partnerek/szcsem_szines_latin.webp" data-srcset="/images/partnerek/szcsem_szines_latin.webp" alt="Pasztorális helynökség Szeged" class="lazy">
                    </a>
                    <a href="https://halo.hu/" title="Háló Közösségi és Kulturális Központ" target="_blank" rel="noopener noreferrer">
                        <img @lazySrc() data-src="/images/partnerek/halo-logo.webp" data-srcset="/images/partnerek/halo-logo.webp" alt="Háló Közösségi és Kulturális Központ" class="lazy">
                    </a>
                    <a href="https://72tanitvany.hu/" title="Hetvenkét Tanítvány Mozgalom" target="_blank" rel="noopener noreferrer" class="t72-logo">
                        <img @lazySrc() data-src="/images/partnerek/t72_2.webp" data-srcset="/images/partnerek/t72_2.webp" alt="Hetvenkét Tanítvány Mozgalom" class="lazy">
                    </a>
                    <a href="https://bizdramagad.hu/" title="Bízd rá magad" target="_blank" rel="noopener noreferrer" class="t72-logo">
                        <img @lazySrc() data-src="/images/partnerek/bizd_ra_magad.webp" data-srcset="/images/partnerek/bizd_ra_magad.webp" alt="Bízd rá magad" class="lazy">
                    </a>
                    <a href="https://miserend.hu/" title="miserend.hu" target="_blank" rel="noopener noreferrer">
                        <img @lazySrc() data-src="/images/partnerek/miserend.webp" data-srcset="/images/partnerek/miserend.png" alt="miserend.hu" class="lazy miserend-logo">
                    </a>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12 mb-4 mb-md-0">
                <h5 class="footer-section-title">Linkek</h5>
                <ul class="nav flex-column footer-nav">
                    <li class="nav-item mb-1"><a href="@route('portal.page', 'rolunk')">Rólunk</a></li>
                    <li class="nav-item mb-1"><a href="@route('portal.page', 'impresszum')">Impresszum</a></li>
                    <li class="nav-item mb-1"><a href="@route('portal.page', 'iranyelveink')">Irányelveink</a></li>
                    <li class="nav-item mb-1"><a href="@route('portal.page', 'rolunk')#contact">Kapcsolat</a></li>
                </ul>
            </div>

            <div class="col-md-3 col-sm-6 col-12 mb-4 mb-md-0">
                <h5 class="footer-section-title">Kapcsolat</h5>
                <ul class="nav flex-column footer-nav">
                    <li class="nav-item mb-1"><a href="mailto:{{$contact_email}}">{{$contact_email}}</a></li>
                    <li class="nav-item mb-1"><a href="@route('portal.page', 'adatkezelesi-tajekoztato')">Adatkezelés</a></li>
                    <li class="nav-item mb-1"><a href="@route('portal.page', 'adatvedelmi-nyilatkozat')">Adatvédelem</a></li>
                    <li class="nav-item mb-1"><a href="#" class="js-open-cookie-settings">Cookie beállítások</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div id="footer-bottom">
        <div class="container">
            <div class="row align-items-center gy-2 gy-sm-0">
                <div class="col-12 col-sm-6 d-flex align-items-center">
                    <span class="footer-copy">© 2021-{{ date('Y') }} kozossegek.hu</span>
                </div>
                <div class="col-12 col-sm-6 d-flex justify-content-sm-end align-items-center gap-1 flex-wrap footer-social">
                    <a href="https://www.facebook.com/K%C3%B6z%C3%B6ss%C3%A9gekhu-107828477772892"
                       title="Facebook"
                       aria-label="Facebook"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="footer-social-link"><i class="fab fa-facebook-square fs-3"></i></a>
                    <a href="https://www.instagram.com/kozossegek.hu/"
                       title="Instagram"
                       aria-label="Instagram"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="footer-social-link"><i class="fab fa-instagram-square fs-3"></i></a>
                    <a href="https://github.com/baueri/kozossegek-hu/"
                       title="GitHub"
                       aria-label="GitHub"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="footer-social-link"><i class="fab fa-github-square fs-3"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

@yield('footer')

@include('partials.cookie-consent')

<!-- Bootstrap 5 JS (Popper benne van) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

@if(env('GOOGLE_LOGIN_ENABLED'))
<script src="https://accounts.google.com/gsi/client" async defer></script>
@endif

<script>
    const meili_enabled = {{ env ('MEILI_ENABLED') ? 'true' : 'false' }}
</script>

<script src="/js/cookie-consent.js?{{ filemtime('js/cookie-consent.js') }}"></script>
<script src="/js/scripts.js?{{ filemtime('js/scripts.js') }}"></script>
<script src="/js/dialog.js?{{ filemtime('js/dialog.js') }}"></script>

@yield('scripts')

</body>
</html>