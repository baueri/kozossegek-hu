<!DOCTYPE html>
<html lang="hu">
<head>
    <!--
                       &
                      &&&
                       &
                 &&&   &   &&&
              &&     &&&&&     &&
            &&     &&&   &&&     &
            &   &&&         &&&   &
           %& &&     (( ((    &&& &&
            &% (    ((( (((    ( &&
         && ((      ((( (((      (( &%
           (  ((               ((  (
                (((         (((
                   (((((((((

           "Keressetek és találtok..."

            Megtaláltad! Ha ezt itt látod, akkor valószínűleg konyítasz valamennyit a kódoláshoz ;)
            Kapcsolódj be te is a fejlesztésbe: https://github.com/baueri/kozossegek-hu/
      -->
    <meta charset="utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subtitle'){{ site_name() }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<!--    <link rel="search" type="application/opensearchdescription+xml" title="kozossegek.hu" href="opensearch.xml">-->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    @yield('header')

    <link rel="stylesheet" href="/css/style.css?{{ filemtime('css/style.css') }}">
    <link href="/css/common.css" @preload_css()>
</head>
<body class="{{ !is_prod() ? 'demo' : '' }} {{ is_home() ? 'home' : '' }} {{ $body_class ?? '' }}">
    <div id="fb-root"></div>
    @include('portal.partials.main_menu')
    @yield('portal')
    <footer id="footer" class="text-white">
        <div class="container" id="footer-top">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12 my-3 my-md-0">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="@route('portal.page', 'rolunk')" class="nav-link">Rólunk</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'impresszum')" class="nav-link">Impresszum</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'adatkezelesi-tajekoztato')" class="nav-link">Adatkezelés</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'adatvedelmi-nyilatkozat')" class="nav-link">Adatvédelem</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'iranyelveink')" class="nav-link">Irányelveink</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'rolunk')#contact" class="nav-link">Kapcsolat</a></li>
                    </ul>
                </div>

                <div class="col-md-5 offset-0 offset-md-3 col-sm-6 col-xs-12">
                    <h5>Partnereink</h5>
                    <div class="partnereink">
                        <a href="https://pasztoralis.hu/" title="Pasztorális helynökség Szeged" target="_blank" rel="noopener noreferrer">
                            <img @lazySrc() data-src="/images/partnerek/szcsem_szines_latin.webp" data-srcset="/images/partnerek/szcsem_szines_latin.webp" alt="Pasztorális helynökség Szeged" class="lazy">
                        </a>
                        <a href="https://halo.hu/" title="Háló Közösségi és Kulturális Központ" target="_blank" rel="noopener noreferrer">
                            <img @lazySrc() data-src="/images/partnerek/halo-logo.webp" data-srcset="/images/partnerek/halo-logo.webp" alt="Háló Közösségi és Kulturális Központ" class="lazy">
                        </a>
                        <a href="https://fbe.hu/" title="Felebarátok egyesület" target="_blank" rel="noopener noreferrer">
                            <img @lazySrc() data-src="/images/partnerek/felebaratok_egyesulet.webp" data-srcset="/images/partnerek/felebaratok_egyesulet.webp" alt="Felebarátok egyesület" class="lazy">
                        </a>
                        <br/>
                        <a href="https://72tanitvany.hu/" title="Hetvenkét Tanítvány Mozgalom" target="_blank" rel="noopener noreferrer" class="t72-logo">
                            <img @lazySrc() data-src="/images/partnerek/t72_2.webp" data-srcset="/images/partnerek/t72_2.webp" alt="Hetvenkét Tanítvány Mozgalom" class="lazy">
                        </a>
                        <a href="https://bizdramagad.hu/" title="Bízd rá magad" target="_blank" rel="noopener noreferrer" class="t72-logo">
                            <img @lazySrc() data-src="/images/partnerek/bizd_ra_magad.webp" data-srcset="/images/partnerek/bizd_ra_magad.webp" alt="Bízd rá magad" class="lazy">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6"><small>© 2021-{{ date('Y') }} kozossegek.hu</small></div>
                    <div class="col-sm-6 text-right">
                        <a href="https://www.facebook.com/K%C3%B6z%C3%B6ss%C3%A9gekhu-107828477772892" title="Facebook" aria-label="Facebook" target="_blank" class="text-white"><i class="fab fa-facebook-square fs-3"></i> </a>
                        <a href="https://www.instagram.com/kozossegek.hu/" title="Instagram" aria-label="Instagram" target="_blank" class="text-white"><i class="fab fa-instagram-square fs-3"></i> </a>
                        <a href="https://github.com/baueri/kozossegek-hu/" title="Github" aria-label="Github" target="_blank" class="text-white"><i class="fab fa-github-square fs-3"></i> </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    @yield('footer')
    <div class="alert text-center cookiealert" role="alert">
        <b>Kedves látogató!</b> &#x1F36A; A honlapon a felhasználói élmény fokozásának érdekében cookie-kat használunk. <a href="/cookie-tajekoztato" target="_blank">További információ</a>
        <button type="button" class="btn btn-altblue btn-sm acceptcookies">Rendben</button>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    @if(env('GOOGLE_LOGIN_ENABLED'))
        <script src="https://accounts.google.com/gsi/client" async defer></script>
    @endif

    @if(is_prod())
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-43190044-6"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-43190044-6');
        </script>

        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/hu_HU/sdk.js#xfbml=1&version=v17.0&appId={{ env('FACEBOOK_APP_ID') }}&autoLogAppEvents=1" nonce="HRNksHZS"></script>
    @endif
    <script>
        const meili_enabled = {{ env ('MEILI_ENABLED') ? 'true' : 'false' }}
    </script>
    <script src="/js/scripts.js?{{ filemtime('js/scripts.js') }}"></script>
    <script src="/js/dialog.js?{{ filemtime('js/dialog.js') }}"></script>
    @yield('scripts')
    @if(isset($display_legal_notice) && $display_legal_notice)
        <script>
            (() => {
                dialog.show({
                    "title": "Adatvédelmi tájékoztatónk módosult",
                    "message": "<p>Kedves Felhasználónk!<br/><br/> A <b>kozossegek.hu</b> adatkezelési tájékoztatója <b>{{ $legal_notice_date }}</b> napján módosult. Kérjük, ismerje meg a módosított adatvédelmi tájékoztatónkat.</p><p>" +
                        "<p class='text-center'><a href='/adatkezelesi-tajekoztato?accept-legal-notice' target='_blank'>Adatvédelmi tájékoztató <i class='fa fa-external-link-alt'></i></a></p>" +
                        "<p class='text-center'><small><b><u>Az oldal további böngészésével elfogadod az adatvédelmi tájékoztatót.</u></b></small></p>",
                    "closable": false,
                    "buttons": [
                        {"text": "Megértettem", "cssClass": "btn btn-primary", action(modal, callback) { callback(modal, true); modal.close(); }},
                    ]
                }, () => {
                    $.post("@route('api.accept_legal_notice')");
                });
            })();
        </script>
    @endif
    <noscript>
        <div class="modal fade show" tabindex="-1" aria-hidden="true" style="z-index: 1040; display: block">
            <div class="modal-dialog">
                <div class="modal-content bg-danger text-light">
                    <div class="modal-header text-center">
                        <h5 class="modal-title" style="width: 100%">Nincs engedélyezve a javascript</h5>
                    </div>
                    <div class="modal-body text-center">
                        <p>Az oldal zavartalan működéséhez kérjük engedélyezd a böngésződben a javascript-et.</p>
                        <p>További információ és segítség a javascript engedélyezéséhez:</p>
                        <a href="https://www.enablejavascript.io/hu" target="_blank" style="color: #fff; text-decoration: underline">
                            <b>https://www.enablejavascript.io/hu</b>
                            @icon('external-link-alt')
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show stacked" style="z-index: 1039;"></div>
    </noscript>
    {{ debugbar()->render() }}
</body>
</html>
