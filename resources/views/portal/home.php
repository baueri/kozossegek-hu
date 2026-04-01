@section('header')
<meta name="description"
    content="A kozossegek.hu egy katolikus közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül." />
<meta name="keywords" content="közösség, katolikus, közösségkereső, keresztény, karizmatikus, imakör, dicsőítő" />
<meta property="og:url" content="{{ get_site_url() }}" />
<meta property="og:type" content="website" />
<meta property="og:title" content="kozossegek.hu - @lang('find_your_church_group')" />
<meta property="og:description"
    content="A kozossegek.hu egy katolikus közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül." />
@og_image()
<meta property="og:locale" content="hu_HU" />
<link rel="canonical" href="{{ get_site_url() }}" />
<link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" @preload_css()>

@endsection
@extends('portal2026.portal')
<div id="main-finder" class="hero d-flex align-items-center justify-content-center text-center">
    <div class="hero-overlay"></div>

    <div class="container position-relative z-1">

        <h1 class="hero-title mb-3">
            Találd meg a közösséged!
        </h1>

        <p class="hero-subtitle mb-4">
            "Ahol ugyanis ketten vagy hárman összegyűlnek a nevemben..."
            <br><strong>Mt.18,20</strong>
        </p>

        <form class="d-flex justify-content-center" method="get" action="@route('portal.groups')">
            <div class="search-modern input-group rounded-pill bg-white p-1 shadow">
                <input type="text"
                    name="search"
                    class="form-control"
                    placeholder="Keresés">

                <button class="btn btn-orange px-4 rounded-pill" type="submit">
                    Keresés
                </button>
            </div>
        </form>

    </div>
</div>

<section class="py-5">
    <div class="container" style="max-width: 900px;">

        <div class="bg-white px-5 py-5 rounded-4 shadow border-0 text-center">

            <h2 class="fs-2 fs-md-1 fw-bold mb-4 fst-italic">
                {{ $intro['title'] }}
            </h2>

            <div class="mx-auto text-secondary" style="max-width: 700px;">
                {{ $intro['text'] }}
            </div>
        </div>

    </div>
</section>

<section class="py-6 how-it-works mb-5">
    <div class="container">
        <h2 class="text-center section-title my-5">
            Hogyan működik?
        </h2>

        <div class="row g-4 justify-content-center">

            <!-- 1 -->
            <div class="col-md-4">
                <div class="how-card text-center h-100">

                    <div class="how-icon">
                        <i class="fas fa-search"></i>
                    </div>

                    <h3>Keress rá!</h3>

                    <p>
                        Keress rá településre, lelkiségi mozgalomra,
                        vagy arra, ami számodra fontos egy közösségben!
                    </p>

                </div>
            </div>

            <!-- 2 -->
            <div class="col-md-4">
                <div class="how-card text-center h-100">

                    <div class="how-icon">
                        <i class="fas fa-mouse-pointer"></i>
                    </div>

                    <h3>Kattints rá!</h3>

                    <p>
                        A listában megtalálható közösségekre kattintva
                        többet megtudhatsz róluk!
                    </p>

                </div>
            </div>

            <!-- 3 -->
            <div class="col-md-4">
                <div class="how-card text-center h-100">

                    <div class="how-icon">
                        <i class="fas fa-comment"></i>
                    </div>

                    <h3>Vedd fel a kapcsolatot!</h3>

                    <p>
                        Ha megtetszik egy közösség, az adatlapján keresztül
                        felveheted a kapcsolatot a vezetővel.
                    </p>

                </div>
            </div>

        </div>
    </div>
</section>

<section class="mt-5 pt-5 mb-5">
    <div class="container">
        <h2 class="text-center section-title my-5">Legújabb közösségek</h2>
        <div class="row" id="kozossegek-list">
            @foreach($groups as $group)
            <div class="col-md-6 col-lg-4 mb-5">
                @include('portal.partials.kozosseg_card', ['group' => $group])
            </div>
            @endforeach
        </div>
        <div class="text-center">
            <a href="@route('portal.groups')" class="text-orange">
                További Közösségek
                <span class="arrow-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M5 12H19M19 12L13 6M19 12L13 18"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </span>
            </a>
        </div>
    </div>
</section>

<section class="cta-section py-5 mb-5">
    <div class="container">

        <div class="cta-card">

            <div class="row g-0 align-items-center">

                <!-- BAL OLDAL -->
                <div class="col-lg-6 p-5 text-white">

                    <h2 class="cta-title mb-3">
                        Közösséget vezetek,<br>
                        szeretném hirdetni.
                    </h2>

                    <h3 class="cta-highlight mb-4">
                        Mit tegyek?
                    </h3>

                    <p class="cta-text mb-4">
                        Nagyon örülünk annak, ha te is hirdetnéd nálunk a közösséged! Ehhez nem kell mást tenned, mint ellátogatnod a közösséget vezetek oldalra, majd az ott található űrlapot kitölteni és elküldeni nekünk. A regisztrációt követően, jóváhagyás után, közösséged a látogatók számára is elérhető lesz.
                    </p>

                    <a href="https://kozossegek.hu/kozosseg-regisztracio"
                        class="btn btn-orange btn-lg rounded-pill px-5">
                        Közösség regisztrálása
                    </a>

                </div>
            </div>

        </div>

    </div>
</section>

<section class="pb-5 communities-section mb-5">
    <div class="container">
        <div class="communities-card">
            <div class="row g-0 align-items-center">
                <div class="col-lg-5 p-5">
                    <h2 class="section-title mb-3">
                        Közösségek Magyarországon
                    </h2>
                    <div class="section-divider mb-4"></div>
                    <p class="text-muted mb-3">
                        Országszerte jelenleg több, mint <strong>{{ $total_groups }} aktív</strong>
                        katolikus közösség található.
                    </p>
                    <p class="text-muted mb-4">
                        Találd meg a neked valót, vagy ha te is vezetsz egyet,
                        csatlakozz és tedd láthatóvá!
                    </p>
                </div>

                <div class="col-lg-7 map-wrapper">
                    <div class="map-container">
                        @component('open_street_map', ['types' => ['institute']])
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>