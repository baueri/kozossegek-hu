@section('header')
    <link href="https://fonts.googleapis.com/css?family=Cardo:400,700|Oswald" rel="stylesheet">
    @include('asset_groups.select2')
@endsection
@section('header_content')
    <div id="main-finder" class="p-4 p-lg-5">
        <div class="container">
            <div class="text-white text-center" style="margin: auto">
                <img src="/images/logo_only_md.png" style="max-width: 240px; filter: brightness(0) invert(1);" class="mb-4"/>
                <h1>TALÁLD MEG A KÖZÖSSÉGED!</h1>
            </div>
            <form method="get" id="finder" class="mt-5" action="@route('portal.groups')">
                <input type="text" name="search" class="mb-5" placeholder="pl.: Budapest antiochia egyetemista">
                <button type="submit" class="btn btn-primary btn-lg">Keresés</button>
            </form>
        </div>
    </div>
@endsection
@extends('portal')
<div class="container main-block">
    <h2 class="text-center">Hogyan működik?</h2>
    <div class="row mt-5 mb-5" id="instructions">
        <div class="col-md-4 text-center">
<!--            <i class="fa fa-search"></i>-->
            <img src="/images/search.png" style="width: 100px;">
            <h6 class="text-danger mt-4 mb-3">Keresd meg!</h6>
            <p>Keress rá városodra, lelkiségi mozgalomra, közösség jellegére, vagy bármire, ami esetleg érdekelhet téged!</p>
        </div>
        <div class="col-md-4 text-center">
<!--            <i class="fa fa-mouse-pointer"></i>-->
            <img src="/images/mouse.png" style="width: 100px;">
            <h6 class="text-danger mt-4 mb-3">Kattints rá!</h6>
            <p>Ha a listában megtaláltad a potenciális közösséget, kattints rá, hogy többet megtudj róla!</p>
        </div>
        <div class="col-md-4 text-center">
<!--            <i class="fa fa-envelope"></i>-->
            <img src="/images/contact.png" style="width: 100px;">
            <h6 class="text-danger mt-4 mb-3">Írj nekik!</h6>
            <p>Amennyiben felkeltette az érdeklődésedet egy közösség, az adatlapján keresztül vedd fel a kapcsolatot a közösségvezetővel!</p>
        </div>
    </div>
</div>
<!--<div class="container main-block">-->
<!--    <div class="row">-->
<!--        <div class="col-md-6">-->
<!--            <div class="card h-100">-->
<!--                <div class="card-body">-->
<!--                    @widget('A_HONLAPROL')-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-md-6">-->
<!--            <div class="card h-100">-->
<!--                <div class="card-body">-->
<!--                    @widget('KISZ')-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<div class="jumbotron main-block mt-0">
    <div class="container">
    <div class="row">
        <div class="col-md-6 pt-5">
            @widget('MERT_JO')
            <a href="https://777blog.hu/2016/09/20/5-erv-hogy-elkezdj-kozossegbe-jarni/" target="_blank" class="btn btn-success">A közösségről</a>
        </div>
        <div class="col-md-6">
            <img src="/images/img2.jpg" alt="">
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
            @widget('KOZVEZ')
        </div>
    </div>
</div>
<div class="container main-block text-center">
    <h3>Mire jó egy keresztény közösség?</h3>
    <p style="max-width: 560px; margin: auto;" class="mt-3 mb-3">Ide jöhet szöveg arról, hogy nézzék meg a shoeshine tv által rendezett 'kerekasztal beszélgetést' arról, hogy miben segít az, ha közösséghez tartozunk.</p>
    <iframe height="400" src="https://www.youtube-nocookie.com/embed/aqqz1mbeGTU" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width: 100%; max-width: 720px;"></iframe>
</div>