@section('header')
    @include('asset_groups.select2')
@endsection
@extends('portal')
<div id="main-finder" class="p-3 p-lg-5 p-lg-6">
    <div class="container main-block">
        <div class="text-white text-center" style="margin-bottom: 2em; max-width: 600px; margin: auto">
            @widget('FOKE')
        </div>
        <form method="get" id="finder" action="@route('portal.groups')">
            <input type="text" name="search" class="" placeholder="pl.: Budapest antiochia egyetemista">
            <button type="submit" class="btn btn-primary btn-lg">Keresés</button>
        </form>
    </div>
</div>
<div class="container main-block">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">A honlapról</h5>
                    <p class="card-text">Célunk egy olyan online platform megvalósítása volt, ahol a közösséget keresők egyszerűen és hatékonyan rátalálhatnak az igényeiknek megfelelő csoportra.</p>
                    <a href="@route('portal.page', 'rolunk')" class="card-link">bővebben...</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kiknek szól ez az oldal?</h5>
                    <p class="card-text">A weblappal azokat a keresztényeket szeretnénk megszólítani, akik szeretnének közösséghez tartozni, de nehezen találtak a lakóhelyükön, vagy éppen...</p>
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="jumbotron main-block">
    <div class="container">
    <div class="row">
        <div class="col-md-8 pt-5">
            <h2>Mert közösséghez tartozni jó!</h2>
            <p>
                Ide egy rövid szösszenetet tolhatunk, hogy miért fontos az, hogy közösségbe tartozzunk. A lenti link pedig átirányítana a 'közösségről' oldalra.
                Ha bizonytalan vagy abban, hogy van-e értelme közösségbe járni, olvasd el a <a href="https://777blog.hu/2016/09/20/5-erv-hogy-elkezdj-kozossegbe-jarni/" target="_blank" title="5 érv, hogy elkezdj közösségbe járni" style="text-decoration: underline">777 cikkét ezzel kapcsolatban</a>!
            </p>
            <a href="https://777blog.hu/2016/09/20/5-erv-hogy-elkezdj-kozossegbe-jarni/" target="_blank" class="btn btn-success">A közösségről</a>
        </div>
        <div class="col-md-4">
            <img src="/images/community.png">
        </div>
    </div>
    </div>
</div>
<div class="container main-block">
    <div class="row">
        <div class="col-md-7">
            <img src="/images/group_illustration1_alt.png">
        </div>
        <div class="col-md-5 align-middle h-100">
            <h3 class="text-center">Közösséget vezetek.<br>Hogyan tudom itt hirdetni?</h3>
            <p class="text-justify mt-3">
                Nagyon örülünk annak, ha te is hirdetnéd nálunk a közösséged! Bár még nincs közvetlen felület erre a honlapon,
                de tervbe vettük, hogy lehetőséget biztosítsunk közösségvezetőknek arra, hogy egyszerűen regisztrálni tudják a közösségük az oldalon.
            </p>
            <p>De addig is <a href="#">ezt a google űrlapot kitöltve</a> elküldheted az igényedet ehhez.</p>

        </div>
    </div>
</div>
<div class="container main-block text-center">
    <h3>Mire jó egy keresztény közösség?</h3>
    <p style="max-width: 560px; margin: auto;" class="mt-3 mb-3">Ide jöhet szöveg arról, hogy nézzék meg a shoeshine tv által rendezett 'kerekasztal beszélgetést' arról, hogy miben segít az, ha közösséghez tartozunk.</p>
    <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/aqqz1mbeGTU" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>