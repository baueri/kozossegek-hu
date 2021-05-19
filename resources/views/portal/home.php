@section('header')
<link href="https://fonts.googleapis.com/css?family=Cardo:400,700|Oswald" rel="stylesheet">
<meta name="description"
      content="A kozossegek.hu egy katolikus közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül."/>
<meta name="keywords" content="közösség, katolikus, közösségkereső, keresztény, karizmatikus, imakör, dicsőítő"/>
<meta property="og:url" content="{{ get_site_url() }}"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="kozossegek.hu - Találd meg a közösséged!"/>
<meta property="og:description"
      content="A kozossegek.hu egy katolikus közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül."/>
<meta property="og:image" content="{{ get_site_url() . $header_background }}"/>
<meta property="og:locale" content="hu_HU"/>
<link rel="canonical" href="{{ get_site_url() }}"/>
@include('asset_groups.select2')
@endsection
@section('header_content')
<div id="main-finder" class="p-4 p-lg-5">
    <div class="container">
        <div class="text-white text-center" style="margin: auto">
            <img src="/images/logo_only_md.png" class="mb-4"/>
            <h1>KOZOSSEGEK.HU</h1>
            <h4>TALÁLD MEG A KÖZÖSSÉGED!</h4>
        </div>
        <form method="get" id="finder" class="mt-5 text-center" action="@route('portal.groups')">
            <div id="search-group" class="rounded-pill bg-white py-1 px-1">
                <div class="row">
                    <div class="col-lg-4 border-right mb-2 mb-lg-0">
                        <input type="text" class="form-control rounded-pill" placeholder="kulcsszó, pl.:  egyetemista..." name="search">
                    </div>
                    <div class="col-lg-3 border-right mb-2 mb-lg-0">
                        <input type="text" class="form-control rounded-pill" placeholder="város" name="varos">
                    </div>
                    <div class="col-lg-3 mb-2 mb-lg-0">
                        <select class="form-control rounded-pill" style="color:#aaa" name="korosztaly">
                            <option value="">-- korosztály --</option>
                            <option value="tinedzser">tinédzser</option>
                            <option value="fiatal_felnott">fiatal felnőtt</option>
                            <option value="kozepkoru">középkorú</option>
                            <option value="nyugdijas">nyugdíjas</option>
                        </select>
                    </div>
                    <div class="col-lg-2"><button type="submit" class="btn btn-darkblue rounded-pill px-3 w-100"><i class="fa fa-search"></i> Keresés</button> </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('portal')
<div class="jumbotron main-block mt-0">
    <div class="container text-center">
        <h2 class="mb-4 title-secondary">Mi ez az oldal?</h2>
        <p>A kozossegek.hu egy katolikus közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül. Hisszük, hogy az ember alapszükséglete a közösséghez tartozás, hiszen ezáltal tud önmaga lenni, így tud megbirkózni az élet nehézségeivel, így válhat az élete teljessé.</p>
        <p>Kívánjuk, hogy ismerd fel azt az erőt, amely a keresztény közösségekben rejlik, találd meg saját helyedet és légy aktív tagja az Egyháznak!</p>
        <p><strong>"Ahol ugyanis ketten vagy hárman összegyűlnek a nevemben, ott vagyok közöttük.” Mt.18,20</strong></p>
    </div>
</div>
<div class="container main-block">
    <h2 class="text-center title-secondary">Hogyan működik?</h2>
    <div id="instructions" class="row mt-5 mb-5">
        <div class="col-md-4 text-center"><img style="width: 100px;" src="/images/search.png" alt="" />
            <h6 class="text-danger mt-4 mb-3">Keresd meg!</h6>
            <p>Keress rá településre, lelkiségi mozgalomra, <br/>a közösség jellegére, vagy arra, ami számodra fontos egy közösségben!</p>
        </div>
        <div class="col-md-4 text-center"><img style="width: 100px;" src="/images/mouse.png" alt="" />
            <h6 class="text-danger mt-4 mb-3">Kattints rá!</h6>
            <p>A listában megtalálható közösségekre kattintva többet megtudhatsz a részletekről!</p>
        </div>
        <div class="col-md-4 text-center"><img style="width: 100px;" src="/images/contact.png" alt="" />
            <h6 class="text-danger mt-4 mb-3">Írj nekik!</h6>
            <p>Amennyiben felkeltette az érdeklődésedet egy közösség, az adatlapján keresztül vedd fel a kapcsolatot a közösségvezetővel!</p>
        </div>
    </div>
</div>
<div class="jumbotron main-block mt-0 pt-0 pb-0 kozosseghez-tartozni">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-6 col-12 pt-5">
                <div class="shadowed">
                    <h2 class="title-secondary display-5">Mert közösséghez tartozni jó!</h2>
                    <p>Közösséghez tartozni lehetőség és felelősség is egyben. Lehetőség a lelki elmélyülésre és az emberi kapcsolatok mélyítésére. Ugyanakkor felelősség is, hogy az Istentől kapott készségeket, képességeket, talentumokat felhasználva mások segítségére lehessünk.</p>
                    <p>Kedvcsinálónak olvasd el a <a title="" href="https://777blog.hu/2016/09/20/5-erv-hogy-elkezdj-kozossegbe-jarni/" target="_blank" data-original-title="5 érv, hogy elkezdj közösségbe járni">777blog.hu írását,</a> hogy miért jó közösségbe járni!</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container main-block">
    <div class="row">
        <div class="col-md-6"><img style="width: 100%;" src=" /storage/uploads/kozosseget_vezetek.jpg" alt="" /></div>
        <div class="col-md-6 align-middle h-100 mt-3 mt-md-0">
            <h3 class="text-center title-secondary mb-4">Közösséget vezetek, szeretném hirdetni.<br/> Mit tegyek?</h3>
            <p align="justify">Nagyon örülünk annak, ha te is hirdetnéd nálunk a közösséged! Ehhez nem kell mást tenned, mint ellátogatnod a <a href="https://kozossegek.hu/kozosseg-regisztracio" target="_blank">közösséget vezetek</a> oldalra, majd az ott található űrlapot kitölteni és elküldeni nekünk. A regisztrációt követően, jóváhagyás után, közösséged a látogatók számára is elérhető lesz.</p>
            <p class="text-center"><a class="btn btn-darkblue" href="/kozosseg-regisztracio">Közösséget vezetek</a></p>
        </div>
    </div>
</div>