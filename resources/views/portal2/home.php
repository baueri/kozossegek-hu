<com:section name="portal2.header">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
      integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
      crossorigin=""/>
</com:section>

<com:section name="portal2.footer_scripts">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
</com:section>

<com:template extends="portal2.main">
    <section id="main-finder" class="hero is-info is-small is-bold pt-6 pb-6">
        <div class="container hero-body">
            <div class="columns is-vcentered">
                <div class="column is-narrow">
                    <figure class="image is-128x128" style="filter: brightness(0) invert(1)"><img src="/images/logo/logo190x190.webp"/></figure>
                </div>
                <div class="column">
                    <h1 class="title has-text-light mb-0">kozossegek.hu</h1>
                    <h2 class="is-size-3 has-text-light">Találd meg a közösséged!</h2>
                </div>
            </div>
            <form method="get" class="mt-4">
                <div class="field has-addons is-justify-content-center">
                    <div class="control"><input class="input is-rounded" type="text" placeholder="Keresés"/></div>
                    <div class="control">
                        <div class="select is-rounded">
                            <select name="korosztaly">
                                <option value="">-- korosztály --</option>
                                <option value="tinedzser">tinédzser</option>
                                <option value="fiatal_felnott">fiatal felnőtt</option>
                                <option value="kozepkoru">középkorú</option>
                                <option value="nyugdijas">nyugdíjas</option>
                            </select>
                        </div>
                    </div>
                    <div class="control">
                        <button type="submit" class="button is-info is-rounded">
                            <span class="icon is-small"><i class="fa fa-search"></i></span>
                            <span>Keresés</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="hero">
        <div class="container is-max-desktop hero-body">
            <div class="container">
                <h1 class="title has-text-centered">Mi ez az oldal?</h1>
                <p class="mb-3">A kozossegek.hu egy <b>katolikus</b> közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül. Hisszük, hogy az ember alapszükséglete a közösséghez tartozás, hiszen ezáltal tud önmaga lenni, így tud megbirkózni az élet nehézségeivel, így válhat az élete teljessé.</p>
                <p class="mb-6">Kívánjuk, hogy ismerd fel azt az erőt, amely a keresztény közösségekben rejlik, találd meg saját helyedet és légy aktív tagja az Egyháznak!</p>
                <p class="has-text-centered has-text-weight-medium is-italic">“Ahol ugyanis ketten vagy hárman összegyűlnek a nevemben, ott vagyok közöttük.” Mt.18,20</p>
            </div>
        </div>
    </section>
    <section id="instructions" class="hero is-light has-text-centered">
        <div class="container is-max-desktop hero-body">
            <div class="container is-max-widescreen">
                <h1 class="title">Hogy működik?</h1>
                <div class="columns is-3 is-variable">
                    <div class="column">
                        <div class="card is-always-shady">
                            <div class="card-image pt-5">
                                <image src="/images/home-icons/computer.webp" alt="Keresd meg!"/>
                            </div>
                            <div class="card-content">
                                <p class="title is-5 has-text-danger">Keresd meg!</p>
                                <div class="content">
                                    <p>Keress rá településre, lelkiségi mozgalomra, <br/>a közösség jellegére, vagy arra, ami számodra fontos egy közösségben!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="card is-always-shady">
                            <div class="card-image pt-5 has-text-centered">
                                <image src="/images/home-icons/mouse.webp" alt="Kattints rá!"/>
                            </div>
                            <div class="card-content">
                                <p class="title is-5 has-text-danger">Kattints rá!</p>
                                <div class="content">
                                    <p>A listában megtalálható közösségekre kattintva többet megtudhatsz a részletekről!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="card is-always-shady">
                            <div class="card-image pt-5">
                                <image src="/images/home-icons/mail.webp" alt="Írj nekik!"/>
                            </div>
                            <div class="card-content">
                                <p class="title is-5 has-text-danger">Írj nekik!</p>
                                <div class="content">
                                    <p>Amennyiben felkeltette az érdeklődésedet egy közösség, az adatlapján keresztül vedd fel a kapcsolatot a közösségvezetővel!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="kozosseghez-tartozni" class="hero darken-bg has-text-shadow">
        <div class="hero-body has-text-centered has-text-light">
            <div class="container">
                <h1 class="title has-text-light">Mert közösséghez tartozni jó!</h1>
                <p class="mb-5">
                    Közösséghez tartozni lehetőség és felelősség is egyben. Lehetőség a lelki elmélyülésre és az emberi kapcsolatok mélyítésére. Ugyanakkor felelősség is, hogy az Istentől kapott készségeket, képességeket, talentumokat felhasználva mások segítségére lehessünk.
                </p>
                <p>
                    Kedvcsinálónak olvasd el a 777blog.hu írását, hogy miért jó közösségbe járni!
                </p>
            </div>
        </div>
    </section>
    <section class="hero is-light">
        <div class="container hero-body">
            <article class="columns featured">
                <div class="column is-6" style="background: url('/images/kozosseget_vezetek_kicsi.webp') no-repeat center; background-size: cover"></div>
                <div class="column is-6">
                    <div class="p-5">
                        <h3 class="heading post-category">Közösségvezetőknek</h3>
                        <h1 class="title post-title">Közösséget vezetek, szeretném hirdetni. Mit tegyek?</h1>
                        <p class="is-size-5 has-text-centered mb-4">Nagyon örülünk annak, ha te is hirdetnéd nálunk a közösséged!</p>
                        <p class="is-size-5 mb-4">Ehhez nem kell mást tenned, mint ellátogatnod a <a class="has-text-underline" href="@route('portal.register_group')" target="_blank">közösséget vezetek</a> oldalra, majd az ott található űrlapot kitölteni és elküldeni nekünk. A regisztrációt követően, jóváhagyás után, közösséged a látogatók számára is elérhető lesz.</p>
                        <p class="has-text-centered"><a href="#" class="button is-brown is-rounded">Közösséget vezetek</a></p>
                    </div>

                </div>
            </article>
        </div>
    </section>
    <section class="hero">
        <div class="container hero-body">
            <h1 class="title has-text-centered">A közösségről mondták</h1>
            <div>
                @include('portal2.home.testimonials')
            </div>
        </div>
    </section>
    <section class="">
        <div class="container is-max-desktop">
            <div class="columns">
                <div class="column is-5 has-text-right">
                    <h1 class="title">Közösségek Magyarországon</h1>
                    <p class="mb-3">Országszerte jelenleg több, mint 80 aktív katolikus közösség regisztrált be oldalunkra.</p>

                    <p>Keresd meg a neked való közösséget, vagy amennyiben te is vezetsz egyet, legyen a következő regisztrált közösség a tied!</p>
                </div>
                <div class="column is-7">
                    @component('open_street_map', ['types' => ['institute'], 'height' => 360])
                </div>
            </div>
        </div>
    </section>
</com:template>