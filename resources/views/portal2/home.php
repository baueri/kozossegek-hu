@extends('portal2.main')
<section id="main-finder" class="section is-medium darken-bg">
    <div class="container">
        <div class="has-text-shadow columns is-vcentered">
            <div class="column is-narrow">
                <figure class="image is-96x96" style="filter: brightness(0) invert(1)"><img src="/images/logo/logo190x190.webp"/></figure>
            </div>
            <div class="column">
                <h1 class="title has-text-light">kozossegek.hu</h1>
                <h2 class="subtitle has-text-light">Találd meg a közösséged!</h2>
            </div>
        </div>
        <form method="get" class="mt-4">
            <div class="field has-addons">
                <div class="control"><input class="input is-rounded" type="text" placeholder="Keresés"></div>
                <div class="control">
                    <div class="select is-rounded">
                        <select name="korosztaly">
                            <option>-- korosztály --</option>
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
    <div class="hero-body">
        <div class="container">
            <h1 class="title has-text-centered">Mi ez az oldal?</h1>
            <p class="mb-3">A kozossegek.hu egy katolikus közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül. Hisszük, hogy az ember alapszükséglete a közösséghez tartozás, hiszen ezáltal tud önmaga lenni, így tud megbirkózni az élet nehézségeivel, így válhat az élete teljessé.</p>
            <p class="mb-3">Kívánjuk, hogy ismerd fel azt az erőt, amely a keresztény közösségekben rejlik, találd meg saját helyedet és légy aktív tagja az Egyháznak!</p>
            <p><strong>"Ahol ugyanis ketten vagy hárman összegyűlnek a nevemben, ott vagyok közöttük.” Mt.18,20</strong></p>
        </div>
    </div>
</section>
<section id="instructions" class="hero is-light has-text-centered">
    <div class="hero-body">
        <div class="container is-max-widescreen">
            <h1 class="title">Hogy működik?</h1>
            <div class="columns is-7 is-variable">
                <div class="column">
                    <div class="card">
                        <div class="card-image pt-5">
                            <image src="/images/home-icons/computer.webp" alt="Keresd meg!"/>
                        </div>
                        <div class="card-content">
                            <p class="title is-5">Keresd meg!</p>
                            <div class="content">
                                <p>Keress rá településre, lelkiségi mozgalomra, <br/>a közösség jellegére, vagy arra, ami számodra fontos egy közösségben!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="card">
                        <div class="card-image pt-5 has-text-centered">
                            <image src="/images/home-icons/mouse.webp" alt="Kattints rá!"/>
                        </div>
                        <div class="card-content">
                            <p class="title is-5">Kattints rá!</p>
                            <div class="content">
                                <p>A listában megtalálható közösségekre kattintva többet megtudhatsz a részletekről!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="card">
                        <div class="card-image pt-5">
                            <image src="/images/home-icons/mail.webp" alt="Írj nekik!"/>
                        </div>
                        <div class="card-content">
                            <p class="title is-5">Írj nekik!</p>
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