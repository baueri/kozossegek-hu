<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subtitle'){{ site_name() }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:wght@200|Open+Sans:300,400,600|Work+Sans:400,700|Raleway|Roboto+Condensed:wght@100,300;400|Poppins" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="search" type="application/opensearchdescription+xml" title="kozossegek.hu" href="/opensearch.xml">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    @yield('header')

    <link rel="stylesheet" href="/css/style.css?{{ filemtime('css/style.css') }}">
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
<body class="{{ is_prod() ? 'demo' : '' }} {{ is_home() ? 'home' : '' }} {{ $body_class ?? '' }}">
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/hu_HU/sdk.js#xfbml=1&version=v9.0&appId=784869592115351&autoLogAppEvents=1" nonce="lcQfw2hE"></script>
    <div id="fb-root"></div>
    @if($header_background)
        <div class="featured-header header-outer">
            @include('portal.partials.main_menu')
            <div class="featured-bg" style="background:url('{{ $header_background }}') no-repeat top center"></div>
            @yield('header_content')
        </div>
    @else
        <div class="header-outer">@include('portal.partials.main_menu')</div>
    @endif
    @yield('portal')
    <footer id="footer" class="text-white">
        <div class="container" id="footer-top">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="@route('portal.page', 'rolunk')" class="nav-link">Rólunk</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'impresszum')" class="nav-link">Impresszum</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'adatvedelmi-nyilatkozat')" class="nav-link">Adatvédelmi nyilatkozat</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'iranyelveink')" class="nav-link">Irányelveink</a></li>
                        <li class="nav-item"><a href="@route('portal.page', 'rolunk')#contact" class="nav-link">Kapcsolat</a></li>
                    </ul>
                </div>

                <div class="col-md-5 offset-3 col-sm-6 col-xs-12">
                    <h5>Partnereink</h5>
                    <div class="partnereink">
                        <a href="https://pasztoralis.hu/" title="Pasztorális helynökség Szeged" target="_blank" rel="noopener noreferrer">
                            <img src="/images/partnerek/szcsem_szines_latin.png" alt="Pasztorális helynökség Szeged">
                        </a>
                        <a href="https://halo.hu/" title="Háló Közösségi és Kulturális Központ" target="_blank" rel="noopener noreferrer">
                            <img src="/images/partnerek/halo-logo.png" alt="Háló Közösségi és Kulturális Központ">
                        </a>
                        <a href="https://fbe.hu/" title="Felebarátok egyesület" target="_blank" rel="noopener noreferrer">
                            <img src="/images/partnerek/felebaratok_egyesulet.png" alt="Felebarátok egyesület">
                        </a>
                        <a href="https://72tanitvany.hu/" title="Hetvenkét Tanítvány Mozgalom" target="_blank" rel="noopener noreferrer" class="t72-logo">
                            <img src="/images/partnerek/t72_2.png" alt="Felebarátok egyesület">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6"><small>© 2021 {{ date('Y') > 2021 ? ' - ' . date('Y') : '' }} kozossegek.hu - Minden jog fenntartva!</small></div>
                    <div class="col-sm-6 text-right">
                        <a href="https://www.facebook.com/K%C3%B6z%C3%B6ss%C3%A9gekhu-107828477772892" target="_blank" class="text-white"><i class="fab fa-facebook-square fs-3"></i> </a>
                        <a href="https://www.instagram.com/kozossegek.hu/" target="_blank" class="text-white"><i class="fab fa-instagram-square fs-3"></i> </a>
                    </div>
                </div>
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
    <script src="/js/scripts.js?{{ filemtime('js/scripts.js') }}"></script>
    <script src="/js/dialog.js?{{ filemtime('js/dialog.js') }}"></script>
    @yield('scripts')
    <script>
        @if(isset($user_notification))
            dialog.show({
                "title": "{{ $user_notification->title }}",
                "message": "{{ addslashes($user_notification->message) }}",
                "closable": false,
                "buttons": [
                    {"text": "Megértettem", "cssClass": "btn btn-primary", action(modal, callback) { callback(modal, true); modal.close(); }},
                ]
            }, () => {
                $.post("@route('api.approve_notification', $user_notification)");
            });
        @endif
    </script>
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
</body>
</html>
