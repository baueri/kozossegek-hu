@section('header')
<meta name="description"
      content="A kozossegek.hu egy katolikus közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül."/>
<meta name="keywords" content="közösség, katolikus, közösségkereső, keresztény, karizmatikus, imakör, dicsőítő"/>
<meta property="og:url" content="{{ get_site_url() }}"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="kozossegek.hu - @lang('find_your_church_group')"/>
<meta property="og:description"
      content="A kozossegek.hu egy katolikus közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül."/>
@og_image()
<meta property="og:locale" content="hu_HU"/>
<link rel="canonical" href="{{ get_site_url() }}"/>
<link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" @preload_css()>

@endsection
@extends('portal')
<div id="main-finder" class="text-light text-center py-3 py-md-5">
    <div class="container">
        <img src="/images/logo/logo190x190.webp" class="logo-home" alt="logo"/>
        <h1 class="my-3">kozossegek.hu</h1>
        <form class="form-inline justify-content-center mt-4" method="get" action="@route('portal.groups')">
            <div class="input-group rounded-pill bg-white p-1 shadow" style="max-width: 500px; width: 100%">
                <input type="text" name="search" class="form-control rounded-pill border-0 form-control-nofocus mx-1"
                       placeholder="@lang('search')" aria-label="@lang('search')" aria-describedby="search-addon">
                <div class="input-group-append">
                    <button class="btn btn-main rounded-pill" type="submit">@icon('search') @lang('search')</button>
                </div>
            </div>
        </form>
        <div class="mt-4">
<!--            <a href="@route('portal.groups')" class="btn btn-main rounded-pill my-2 m-md-0">@lang('menu.search_group')</a>-->
            <a href="@route('portal.register_group')" class="btn btn-outline-purple bg-white text-purple text-d rounded-pill shadow">
                @lang('menu.leading_a_group') @icon('arrow-right')
            </a>
        </div>
    </div>
</div>
<div class="container text-center">
    <div class="py-4 px-2 px-sm-0">
        {{ $intro }}
    </div>
</div>
<div class="bg-lightblue">
    <div class="container">
        @include('portal.partials.instructions')
    </div>
</div>
<div class="kozosseghez-tartozni">
    <div class="container">
        <div class="text-center text-light px-3 px-sm-0 py-4 py-sm-5">
            <h2 class="title-secondary mb-4 mb-sm-5">@lang('inspiration.title')</h2>
            <p>@lang('inspiration.description')</p>
            @if(getLang() === 'hu')
                <p>Kedvcsinálónak olvasd el a <a title="5 érv, hogy elkezdj közösségbe járni" href="https://777blog.hu/2016/09/20/5-erv-hogy-elkezdj-kozossegbe-jarni/" target="_blank" class="text-white" style="text-decoration: underline">777blog.hu írását</a>, hogy miért jó közösségbe járni!</p>
            @endif
        </div>
    </div>
</div>
<section class="bg-lightblue py-0 py-sm-5">
    <div class="container">
        <div class="text-light shadow">
            <div class="row">
                <div class="col-lg-6 d-none d-md-block" style="background: url('/images/kozosseget_vezetek_kicsi.webp') no-repeat center; background-size: cover"></div>
                <div class="col-lg-6 col-12 align-middle h-100 bg-main">
                    <div class="px-3 py-3 my-md-4 my-xs-3">
                        <h2 class="text-center title-secondary font-weight-bold my-4">Közösséget vezetek, szeretném hirdetni.<br/> Mit tegyek?</h2>
                        <p class="text-justify">
                            Nagyon örülünk annak, ha te is hirdetnéd nálunk a közösséged! Ehhez nem kell mást tenned, mint ellátogatnod a <a class="text-light" href="@route('portal.register_group')" target="_blank">közösséget vezetek</a> oldalra, majd az ott található űrlapot kitölteni és elküldeni nekünk. A regisztrációt követően, jóváhagyás után, közösséged a látogatók számára is elérhető lesz.
                        </p>
                        <p class="text-center mt-4">
                            <a class="btn btn-outline-light px-5" href="@route('portal.register_group')">Közösséget vezetek</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('portal/partials/testimonials')

@include('portal/partials/home_news')

<div class="bg-lightblue px-2 px-sm-5 py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-12">
                <h2 class="title-secondary mb-5 text-lg-right">Közösségek Magyarországon</h2>
                <div class="text-lg-right">
                    <p>Országszerte jelenleg több, mint 80 aktív katolikus közösség regisztrált be oldalunkra.</p>
                    <p>Keresd meg a neked való közösséget, vagy amennyiben te is vezetsz egyet, legyen a következő regisztrált közösség a tied!</p>
                </div>
            </div>
            <div class="col-lg-8 col-12">
                @component('open_street_map', ['types' => ['institute']])
            </div>
        </div>
    </div>
</div>
