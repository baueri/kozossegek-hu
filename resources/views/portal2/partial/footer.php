<div class="alert text-center cookiealert" role="alert">
    <div class="container p-3 has-text-centered">
        <b>Kedves látogató!</b> &#x1F36A; A honlapon a felhasználói élmény fokozásának érdekében cookie-kat használunk. <a href="/cookie-tajekoztato" target="_blank">További információ</a>
        <button type="button" class="button is-info is-small is-rounded acceptcookies">
            Rendben
        </button>
    </div>
</div>
<footer id="footer" class="footer has-background-black-bis has-text-light mt-6 pt-5 pb-5">
    <div class="container">
        <div class="columns">
            <div class="column is-3">
                <aside class="menu">
                    <div class="menu-label">kozossegek.hu</div>
                    <ul class="menu-list">
                        <li><a href="@route('portal.page', 'rolunk')">Rólunk</a></li>
                        <li><a href="@route('portal.page', 'impresszum')">Impresszum</a></li>
                        <li><a href="@route('portal.page', 'adatkezelesi-tajekoztato')">Adatvédelem</a></li>
                        <li><a href="@route('portal.page', 'iranyelveink')">Irányelveink</a></li>
                        <li><a href="@route('portal.page', 'rolunk')#contact">Kapcsolat</a></li>
                    </ul>
                </aside>
            </div>
            <div class="column has-text-right">
                <aside class="menu">
                    <div class="menu-label">Partnereink</div>
                    <div class="partnereink">
                        <a href="https://pasztoralis.hu/" title="Pasztorális helynökség Szeged" target="_blank" rel="noopener noreferrer">
                            <img src="/images/partnerek/szcsem_szines_latin.webp" alt="Pasztorális helynökség Szeged">
                        </a>
                        <a href="https://halo.hu/" title="Háló Közösségi és Kulturális Központ" target="_blank" rel="noopener noreferrer">
                            <img src="/images/partnerek/halo-logo.webp" alt="Háló Közösségi és Kulturális Központ">
                        </a>
                        <a href="https://fbe.hu/" title="Felebarátok egyesület" target="_blank" rel="noopener noreferrer">
                            <img src="/images/partnerek/felebaratok_egyesulet.webp" alt="Felebarátok egyesület">
                        </a>
                        <br/>
                        <a href="https://72tanitvany.hu/" title="Hetvenkét Tanítvány Mozgalom" target="_blank" rel="noopener noreferrer" class="t72-logo">
                            <img src="/images/partnerek/t72_2.webp" alt="Hetvenkét Tanítvány Mozgalom">
                        </a>
                        <a href="https://bizdramagad.hu/" title="Bízd rá magad" target="_blank" rel="noopener noreferrer" class="t72-logo">
                            <img src="/images/partnerek/bizd_ra_magad.webp" alt="Bízd rá magad">
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</footer>
<div id="footer-bottom has-background-black" style="background: #000">
    <div class="container">
        <div class="columns">
            <div class="column"><small>© 2021-{{ date('Y') }} kozossegek.hu</small></div>
            <div class="column has-text-right is-size-4">
                <a href="https://www.facebook.com/K%C3%B6z%C3%B6ss%C3%A9gekhu-107828477772892" title="Facebook" aria-label="Facebook" target="_blank" class="has-text-light"><i class="fab fa-facebook-square fs-3"></i> </a>
                <a href="https://www.instagram.com/kozossegek.hu/" title="Instagram" aria-label="Instagram" target="_blank" class="has-text-light"><i class="fab fa-instagram-square fs-3"></i> </a>
                <a href="https://github.com/baueri/kozossegek-hu/" title="Github" aria-label="Github" target="_blank" class="has-text-light"><i class="fab fa-github-square fs-3"></i> </a>
            </div>
        </div>
    </div>
</div>


{{ debugbar()->render() }}
@yield('portal2.footer_scripts')
<script src="/portal2/scripts.js"></script>