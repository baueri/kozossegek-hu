@section('header')
<meta name="description"
      content="A kozossegek.hu egy katolikus közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül."/>
<meta name="keywords" content="közösség, katolikus, közösségkereső, keresztény, karizmatikus, imakör, dicsőítő"/>
<meta property="og:url" content="{{ get_site_url() }}"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="kozossegek.hu - @lang('find_your_church_group')"/>
<meta property="og:description"
      content="A kozossegek.hu egy katolikus közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül."/>
<meta property="og:image" content="{{ get_site_url() . $header_background }}"/>
<meta property="og:locale" content="hu_HU"/>
<link rel="canonical" href="{{ get_site_url() }}"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
      integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
      crossorigin=""/>
@endsection
@section('subtitle')
    @lang('find_your_church_group') -
@endsection
@extends('portal')
<div id="main-finder" class="p-5">
    <div class="container">
        <div class="text-white text-center" style="margin: auto">
            <img src="/images/logo/logo190x190.webp" class="mb-4 logo-home" alt="logo"/>
            <h1>KOZOSSEGEK.HU</h1>
            <h2 style="text-transform: uppercase">{{ lang('find_your_church_group') }}</h2>
        </div>
    </div>
</div>
<div class="container text-center">
    <div class="p-3 p-sm-4">
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
        <div class="text-center text-light p-4 p-sm-5">
            <h2 class="title-secondary mb-5">@lang('inspiration.title')</h2>
            <p>@lang('inspiration.description')</p>
            @if(getLang() === 'hu')
                <p>Kedvcsinálónak olvasd el a <a title="5 érv, hogy elkezdj közösségbe járni" href="https://777blog.hu/2016/09/20/5-erv-hogy-elkezdj-kozossegbe-jarni/" target="_blank" class="text-white" style="text-decoration: underline">777blog.hu írását</a>, hogy miért jó közösségbe járni!</p>
            @endif
        </div>
    </div>
</div>
<section class="bg-lightblue py-5">
    <div class="container">
        <div class="bg-altblue text-light">
            <div class="row">
                <div class="col-lg-6" style="background: url('/images/kozosseget_vezetek_kicsi.webp') no-repeat center; background-size: cover"></div>
                <div class="col-lg-6 align-middle h-100">
                    <div class="px-3 px-md-5 py-3 my-md-5 my-xs-3">
                        <h2 class="text-center title-secondary font-weight-bold mb-4">Közösséget vezetek, szeretném hirdetni.<br/> Mit tegyek?</h2>
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
@include('portal.partials.testimonials')

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
